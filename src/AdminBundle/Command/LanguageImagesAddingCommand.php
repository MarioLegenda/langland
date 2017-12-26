<?php

namespace AdminBundle\Command;

use AdminBundle\Entity\Image;
use AdminBundle\Entity\Language;
use LearningMetadata\Repository\Implementation\LanguageRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class LanguageImagesAddingCommand extends Command
{
    /**
     * @var array $uploadDirs
     */
    private $uploadDirs;
    /**
     * @var LanguageRepository $languageRepository
     */
    private $languageRepository;
    /**
     * LanguageImagesAddingCommand constructor.
     * @param array $imageUpload
     * @param LanguageRepository $languageRepository
     */
    public function __construct(
        array $imageUpload,
        LanguageRepository $languageRepository
    ) {
        $this->uploadDirs = $imageUpload;
        $this->languageRepository = $languageRepository;

        parent::__construct();
    }
    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this->setName('language:add:images');
    }
    /**
     * @inheritdoc
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $answers = $this->interactive($input, $output);

        $resolved = $this
            ->validate($answers)
            ->resolve($answers);

        $icon = $resolved['icon'];
        $coverImage = $resolved['language_image'];

        $this->save($resolved['language'], [
            'icon' => $icon,
            'cover_image' => $coverImage,
        ]);
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return array
     */
    private function interactive(InputInterface $input, OutputInterface $output): array
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $questions = [
            'language' => 'Language name: ',
            'icon' => 'Icon file path: ',
            'language_image' => 'Language image file path: '
        ];

        $answers = [
            'language' => null,
            'icon' => null,
            'language_image' => null,
        ];

        foreach ($questions as $key => $question) {
            $answers[$key] = trim($helper->ask($input, $output, new Question($question)));
        }

        return $answers;
    }
    /**
     * @param Language $language
     * @param array $images
     */
    private function save(Language $language, array $images)
    {
        $jsonImages = [];

        /** @var \SplFileInfo $image */
        foreach ($images as $imageName => $image) {
            $imageObject = new Image();

            $imageObject
                ->setName($image->getBasename())
                ->setRelativePath(sprintf('/images/%s', $language->getName()))
                ->setOriginalName($image->getBasename())
                ->setFullPath($image->getRealPath())
                ->setTargetDir($this->uploadDirs['image_upload_dir']);

            $jsonImages[$imageName] = $imageObject->toArray();
        }

        $language->setImages($jsonImages);

        $this->languageRepository->persistAndFlush($language);
    }
    /**
     * @param array $answers
     * @return array
     */
    private function resolve(array $answers): array
    {
        $languageName = $answers['language'];

        $language = $this->languageRepository->findOneBy([
            'name' => $languageName,
        ]);

        if (!$language instanceof Language) {
            $message = sprintf('Language %s does not exist', $languageName);
            throw new \RuntimeException($message);
        }

        $icon = new \SplFileInfo($answers['icon']);
        $coverImage = new \SplFileInfo($answers['language_image']);

        return [
            'language' => $language,
            'icon' => $icon,
            'language_image' => $coverImage,
        ];
    }
    /**
     * @param array $answers
     * @return LanguageImagesAddingCommand
     */
    private function validate(array $answers): LanguageImagesAddingCommand
    {
        if (empty($answers['language'])) {
            throw new \RuntimeException('Language name argument should not be empty');
        }

        if (empty($answers['icon'])) {
            throw new \RuntimeException('Icon argument should not be empty');
        }

        if (!file_exists($answers['icon'])) {
            $message = sprintf('Icon %s does not exist', $answers['icon']);
            throw new \RuntimeException($message);
        }

        if (empty($answers['language_image'])) {
            throw new \RuntimeException('Language cover image argument should not be empty');
        }

        if (!file_exists($answers['language_image'])) {
            $message = sprintf('Language cover image %s does not exist', $answers['language_image']);
            throw new \RuntimeException($message);
        }

        return $this;
    }
}