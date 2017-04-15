<?php

namespace API\SharedDataBundle\Repository;

use BlueDot\BlueDotInterface;
use BlueDot\Entity\PromiseInterface;
use BlueDot\Entity\Entity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Library\Admin\DeckCreator;

class TheoryRepository extends AbstractRepository
{
    /**
     * @var BlueDotInterface $blueDot
     */
    private $blueDot;
    /**
     * @var array $uploadDirs
     */
    private $uploadDirs;
    /**
     * [__construct description]
     * @param BlueDotInterface $blueDot     [description]
     * @param array            $uploadDirs  [description]
     * @param DeckCreator      $deckCreator [description]
     */
    private $deckCreator;
    /**
     * @var TheoryRepository $instance
     */
    private static $instance;
    /**
     * TheoryRepository constructor.
     * @param BlueDotInterface $blueDot
     * @param array $uploadDirs
     * @param DeckCreator $deckCreator;
     */
    public function __construct(BlueDotInterface $blueDot, array $uploadDirs, DeckCreator $deckCreator)
    {
        $blueDot->useApi('theory');

        $this->blueDot = $blueDot;
        $this->uploadDirs = $uploadDirs;
        $this->deckCreator = $deckCreator;

        self::$instance = $this;
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function create(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.insert.create_theory', $data);

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function rename(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.update.rename_theory', $data);

        return $this->createResultResolver($promise);
    }
    /**
     * @param TheoryDeck $deck
     * @return PromiseInterface
     */
    public function createTheoryDeck(TheoryDeck $deck)
    {
        $parameters = array(
            'create_sound' => null,
            'create_theory_deck' => array(
                'theory_id' => $deck->getTheoryId(),
                'internal_name' => $deck->getInternalName(),
                'deck_data' => $deck->getDeckData(),
                'internal_description' => $deck->getInternalDescription(),
                'show_on_page' => $deck->getShowOnPage(),
                'ordering' => $deck->getOrdering(),
            ),
        );

        if ($deck->hasSounds()) {
            $sounds = $deck->getSounds();

            foreach ($sounds as $sound) {
                $fileData = $this->generateSoundFilename($sound);
                $absoluteFullPath = realpath($sound->getAbsolutePath()).'/'.$fileData['fileName'].'mp3';
                $relativeFullPath = $sound->getRelativePath().$fileData['fileName'].'mp3';

                $soundFile = $sound->getSoundFile();

                $tempDir = realpath($sound->getAbsolutePath()).'/temp/';
                $tempDirFile = realpath($sound->getAbsolutePath()).'/temp/'.$fileData['concat'];
                $soundFile->move($tempDir, $fileData['concat']);

                exec(sprintf('/usr/bin/sox -t %s %s %s', 'mp3', $tempDirFile, $absoluteFullPath), $output);

                if (file_exists($tempDirFile)) {
                    unlink($tempDirFile);
                }

                $soundParameter = array(
                    'relative_path' => $sound->getRelativePath(),
                    'absolute_path' => realpath($sound->getAbsolutePath()),
                    'file_name' => $fileData['concat'],
                    'absolute_full_path' => $absoluteFullPath,
                    'relative_full_path' => $relativeFullPath,
                    'client_original_name' => $soundFile->getClientOriginalName(),
                );

                $parameters['create_sound'][] = $soundParameter;
            }
        }

        $this->deckCreator
            ->setFileName($deck->getInternalName().'.html.twig')
            ->createDeckTwigFile($deck->getDeckData());

        return $this->blueDot->execute('scenario.create_theory_deck', $parameters);
    }
    /**
     * @param TheoryDeck $deck
     * @return PromiseInterface
     */
    public function updateTheoryDeck(TheoryDeck $deck)
    {
        $parameters = array(
            'select_sounds' => array(
                'deck_id' => $deck->getId(),
            ),
            'remove_deck_sounds' => null,
            'remove_theory_sounds' => null,
            'create_sounds' => null,
            'update_theory_deck' => array(
                'deck_id' => $deck->getId(),
                'internal_name' => $deck->getInternalName(),
                'deck_data' => $deck->getDeckData(),
                'internal_description' => $deck->getInternalDescription(),
                'show_on_page' => $deck->getShowOnPage(),
                'ordering' => $deck->getOrdering(),
            ),
            'select_deck' => array(
                'deck_id' => $deck->getId(),
            ),
        );

        if ($deck->hasSounds()) {
            $sounds = $deck->getSounds();

            foreach ($sounds as $sound) {
                $fileData = $this->generateSoundFilename($sound);
                $absoluteFullPath = realpath($sound->getAbsolutePath()).'/'.$fileData['fileName'].'mp3';
                $relativeFullPath = $sound->getRelativePath().$fileData['fileName'].'mp3';

                $soundFile = $sound->getSoundFile();

                $tempDir = realpath($sound->getAbsolutePath()).'/temp/';
                $tempDirFile = realpath($sound->getAbsolutePath()).'/temp/'.$fileData['concat'];
                $soundFile->move($tempDir, $fileData['concat']);

                exec(sprintf('/usr/bin/sox -t %s %s %s', 'mp3', $tempDirFile, $absoluteFullPath), $output);

                if (file_exists($tempDirFile)) {
                    unlink($tempDirFile);
                }

                $parameters['remove_deck_sounds'] = array(
                    'deck_id' => $deck->getId(),
                );

                $parameters['remove_theory_sounds'] = array(
                    'deck_id' => $deck->getId(),
                );

                $soundParameter = array(
                    'relative_path' => $sound->getRelativePath(),
                    'absolute_path' => realpath($sound->getAbsolutePath()),
                    'file_name' => $fileData['concat'],
                    'absolute_full_path' => $absoluteFullPath,
                    'relative_full_path' => $relativeFullPath,
                    'client_original_name' => $soundFile->getClientOriginalName(),
                );

                $parameters['create_sounds'][] = $soundParameter;
            }
        }

        $this->deckCreator
            ->setFileName($deck->getInternalName().'.html.twig')
            ->createDeckTwigFile($deck->getDeckData());

        return $this->blueDot->execute('scenario.update_theory_deck', $parameters)
            ->success(function(PromiseInterface $promise) use ($deck) {
                if ($deck->hasSounds()) {
                    $filesToDelete = $promise->getResult()->get('files_to_delete');

                    if (is_array($filesToDelete)) {
                        foreach ($filesToDelete as $file) {
                            if (file_exists($file)) {
                                unlink($file);
                            }
                        }
                    }

                    if (is_string($filesToDelete)) {
                        if (file_exists($filesToDelete)) {
                            unlink($filesToDelete);
                        }
                    }
                }

                return $promise;
            })
            ->failure(function(PromiseInterface $promise) {
            })
            ->getResult();
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function findTheoryByName(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.select.find_theory_by_name', $data);

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function findAllByLesson(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.select.find_all_theories_by_lesson', $data);

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function findTheoryById(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.select.find_theory_by_id', $data);

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function findDecksByTheory(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.select.find_decks_by_theory', $data);

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function findDeckByInternalName(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.select.find_deck_by_internal_name', $data);

        return $this->createResultResolver($promise);
    }
    /**
     * @param $id
     * @return PromiseInterface
     */
    public function findDeckById($id) : PromiseInterface
    {
        return $this->blueDot->execute('simple.select.find_deck_by_id', array(
            'deck_id' => $id,
        ));
    }

    /**
    * @param $id
    * @return PromiseInterface
    */
    public function findSoundsByDeckId(int $deckId)
    {
        return $this->blueDot->execute('simple.select.find_sounds_by_deck', array(
            'deck_id' => $deckId,
        ));
    }
    /**
     * @param Request $request
     * @return Theory
     */
    public static function createTheoryEntity(Request $request)
    {
        $request = $request->request;

        $theory = new Theory();

        if ($request->has('theory_id')) {
            $theory->setId($request->get('theory_id'));
        }

        return $theory
            ->setName($request->get('name'))
            ->setLessonId($request->get('lesson_id'));
    }
    /**
     * @param Request $request
     * @return TheoryDeck
     */
    public function createTheoryDeckEntity(Request $request)
    {
        $deck = new TheoryDeck();

        if ($request->request->has('deck_id')) {
            $deckId = $request->request->get('deck_id');

            $deck->setId($deckId);

            if ($deckId === 'null') {
                $deck->setId(null);
            }
        }

        if ($request->files->has('soundFile')) {
            $deck->setSounds(TheoryRepository::createSoundEntity($request));
        }

        $deck->setShowOnPage($request->request->get('show_on_page'));

        return $deck
            ->setTheoryId($request->request->get('theory_id'))
            ->setInternalName($request->request->get('internal_name'))
            ->setDeckData(($request->request->has('deck_data')) ? $request->request->get('deck_data') : null)
            ->setOrdering(($request->request->has('ordering')) ? $request->request->get('ordering') : null)
            ->setInternalDescription(($request->request->has('internal_description')) ? $request->request->get('internal_description') : null);
    }
    /**
     * @param Request $request
     * @return array
     */
    public function createSoundEntity(Request $request) : array
    {
        if ($request->files->has('soundFile')) {
            $sounds = array();
            $soundFiles = $request->files->get('soundFile');

            foreach ($soundFiles as $soundFile) {
                if (!$soundFile instanceof UploadedFile) {
                    return array();
                }

                $sound = new Sound();

                $sound
                    ->setRelativePath($this->uploadDirs['sounds_relative_path'])
                    ->setAbsolutePath($this->uploadDirs['sounds_absolute_path'])
                    ->setSoundFile($soundFile);

                $sounds[] = $sound;
            }

            return $sounds;
        }

        return array();
    }

    private function generateSoundFilename(Sound $sound)
    {
        $fileName = md5(uniqid()).'.';
        $image = $this->blueDot->execute('simple.select.find_sound_by_filename', array(
            'file_name' => $fileName.'mp3',
        ))->getResult();

        if (!$image instanceof Entity) {
            return array(
                'fileName' => $fileName,
                'extension' => $sound->getSoundFile()->guessExtension(),
                'concat' => $fileName.$sound->getSoundFile()->guessExtension(),
            );
        }

        $this->generateSoundFilename($sound);
    }
}
