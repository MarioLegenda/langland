<?php

namespace API\SharedDataBundle\Repository;

use BlueDot\Entity\Promise;
use BlueDot\Exception\BlueDotRuntimeException;
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
                'relative_full_path' => $this->uploadsRelativePath.$fileName,
                'original_name' => $image->getClientOriginalName(),
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
        try {
            $result = $this->blueDot->execute('scenario.find_words_simple', array(
                'find_working_language' => $data,
            ))->getResult();
        } catch (BlueDotRuntimeException $e) {
            return $this->createResultResolver(new Promise(array()));
        }

        $words = $result['select_all_words'];

        if (is_array($words)) {
            return $this->createResultResolver(new Promise($words));
        }

        if ($words instanceof Entity) {
            return $this->createResultResolver(new Promise($words->toArray()));
        }
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