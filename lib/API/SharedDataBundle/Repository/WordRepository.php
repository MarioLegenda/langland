<?php

namespace API\SharedDataBundle\Repository;

use Library\App\ImageResize;
use BlueDot\BlueDotInterface;
use BlueDot\Entity\Entity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class WordRepository extends AbstractRepository
{
    /**
     * @var BlueDotInterface $blueDot
     */
    private $blueDot;
    /**
     * @var ImageResize $imageResize
     */
    private $imageResize;
    /**
     * @var string $uploadsRelativePath
     */
    private $uploadsRelativePath;
    /**
     * @var string $uploadsAbsPath
     */
    private $uploadsAbsPath;
    /**
     * WordRepository constructor.
     * @param BlueDotInterface $blueDot
     * @param ImageResize $imageResize
     * @param string $uploadsRelativePath
     * @param string $uploadsAbsPath
     */
    public function __construct(
        BlueDotInterface $blueDot,
        ImageResize $imageResize,
        string $uploadsRelativePath,
        string $uploadsAbsPath
    )
    {
        $blueDot->useApi('words');

        $this->blueDot = $blueDot;
        $this->imageResize = $imageResize;
        $this->uploadsRelativePath = $uploadsRelativePath;
        $this->uploadsAbsPath = realpath($uploadsAbsPath).'/';
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function create(array $data) : ResultResolver
    {
        $findWorkingLanguageParam = array(
            'user_id' => $data['user_id'],
        );

        $createWordParam = array(
            'word' => $data['word'],
            'type' => $data['type'],
        );

        $createImageParam = null;

        if ($data['image'] instanceof UploadedFile) {
            $image = $data['image'];
            $fileName = $this->generateImageFilename($image);

            $createImageParam = array(
                'relative_path' => $this->uploadsRelativePath,
                'absolute_path' => $this->uploadsAbsPath,
                'file_name' => $fileName,
                'absolute_full_path' => $this->uploadsAbsPath.$fileName,
                'relative_full_path' => $this->uploadsRelativePath,
            );
        }

        $createWordCategoryParam = null;

        if (array_key_exists('category', $data)) {
            $createWordCategoryParam = array(
                'category_id' => $data['category'],
            );
        }

        $createTranslationsParam = array();
        foreach ($data['translations'] as $translation) {
            $createTranslationsParam['translation'][] = $translation['translation'];
        }

        $promise = $this->blueDot->execute('scenario.create_word', array(
            'find_working_language' => $findWorkingLanguageParam,
            'create_word' => $createWordParam,
            'create_image' => $createImageParam,
            'create_word_categories' => $createWordCategoryParam,
            'create_translations' => $createTranslationsParam,
        ));

        if ($promise->isSuccess()) {
            if ($data['image'] instanceof File) {
                $fileName = $this->generateImageFilename($data['image']);

                $this->imageResize
                    ->setWidth(380)
                    ->setHeight(380)
                    ->resizeAndSave($data['image'], $this->uploadsAbsPath.$fileName);
            }
        }

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function remove(array $data) : ResultResolver
    {
        $resultResolver = $this->findWordById($data);

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $resultResolver;
        }

        $data = array(
            'word_id' => $resultResolver->getData()['id']
        );

        $resultResolver = $this->findImageByWord($data);

        if ($resultResolver->getStatus() === Status::SUCCESS) {
            $imageData = $resultResolver->getData();

            $fullPath = $imageData['absolute_full_path'];

            if (is_readable($fullPath)) {
                unlink($fullPath);
            }
        }

        $promise = $this->blueDot->execute('scenario.remove_word', array(
            'remove_translations' => $data,
            'remove_word' => $data,
            'remove_word_category' => $data,
            'remove_word_image' => $data,
        ));

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     *
     * Receives array('user_id' => 1)
     */
    public function findAllWordsByWorkingLanguageComplex(array $data)
    {
        $promise = $this->blueDot->execute('callable.find_words_complex', $data);

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     *
     * Receives array('user_id' => 1)
     */
    public function findAllWordsByWorkingLanguageSimple(array $data)
    {
        $promise = $this->blueDot->execute('scenario.find_words_simple', array(
            'find_working_language' => $data,
        ));

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     *
     * Receives array('user_id' => 1, 'word_id' => 1)
     */
    public function findSingleWordByWorkingLanguageComplex(array $data)
    {
        $promise = $this->blueDot->execute('callable.find_single_word_complex', $data);

        return $this->createResultResolver($promise);
    }

    private function generateImageFilename(File $file)
    {
        $fileName = md5(uniqid()).'.';
        $image = $this->blueDot->execute('simple.select.find_word_image_by_filename', array(
            'file_name' => $fileName.$file->guessExtension()
        ));

        if (!$image instanceof Entity) {
            return $fileName.$file->guessExtension();
        }

        $this->generateImageFilename($file);
    }
}