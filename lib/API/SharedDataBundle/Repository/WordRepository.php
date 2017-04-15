<?php

namespace API\SharedDataBundle\Repository;

use Library\App\ImageResize;
use BlueDot\BlueDotInterface;
use BlueDot\Entity\Entity;
use Symfony\Component\HttpFoundation\File\File;

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
        $fileName = null;

        $insertWordParam = array(
            'language_id' => $data['language_id'],
            'word' => $data['word'],
            'type' => $data['type'],
        );

        $wordImageParam = null;

        if ($data['image'] instanceof File) {
            $wordImageParam = array(
                'relative_path' => $this->uploadsRelativePath,
                'absolute_path' => $this->uploadsAbsPath,
                'file_name' => $fileName,
                'absolute_full_path' => $this->uploadsAbsPath.$fileName,
                'relative_full_path' => $this->uploadsRelativePath.$fileName,
            );
        }

        $translationsParam = array(
            'translation' => $data['translations'],
        );

        $categoryParam = null;

        if (!is_null($data['category_id'])) {
            $categoryParam = array('category_id' => $data['category_id']);
        }

        $promise = $this->blueDot->execute('scenario.insert_word', array(
            'insert_word' => $insertWordParam,
            'insert_word_image' => $wordImageParam,
            'insert_translation' => $translationsParam,
            'insert_word_category' => $categoryParam,
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
     */
    public function findImageByWord(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.select.find_image_by_word', $data);

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function findWordByWord(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.select.find_word_by_word', $data);

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function findWordById(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.select.find_word_by_id', $data);

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function scheduleRemoval(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.update.schedule_word_removal', $data);

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