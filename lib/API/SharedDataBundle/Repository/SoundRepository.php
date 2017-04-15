<?php

namespace API\SharedDataBundle\Repository;

use AdminBundle\Entity\Sound;
use BlueDot\BlueDotInterface;
use BlueDot\Entity\PromiseInterface;
use BlueDot\Entity\Entity;

class SoundRepository
{
    /**
     * @var BlueDotInterface $blueDot
     */
    private $blueDot;
    /**
     * SoundRepository constructor.
     * @param BlueDotInterface $blueDot
     */
    public function __construct(BlueDotInterface $blueDot)
    {
        $this->blueDot = $blueDot;
    }
    /**
     * @param Sound $sound
     * @return PromiseInterface
     */
    public function createSound(Sound $sound) : PromiseInterface
    {
        $fileName = $this->generateSoundFilename($sound);
        $absoluteFullPath = realpath($sound->getAbsolutePath()).'/'.$fileName;
        $relativeFullPath = $sound->getRelativeFullPath().$fileName;

        $soundFile = $sound->getSoundFile();

        $soundFile->move($sound->getAbsolutePath(), $fileName);

        return $this->blueDot->execute('simple.insert.create_sound', array(
            'relative_path' => $sound->getRelativePath(),
            'absolute_path' => $sound->getAbsolutePath(),
            'file_name' => $fileName,
            'absolute_full_path' => $absoluteFullPath,
            'relative_full_path' => $relativeFullPath,
        ));
    }

    private function generateSoundFilename(Sound $sound)
    {
        $fileName = md5(uniqid()).'.';
        $image = $this->blueDot->execute('simple.select.find_sound_by_filename', array(
            'file_name' => $fileName.$sound->getSoundFile()->guessExtension()
        ))->getResult();

        if (!$image instanceof Entity) {
            return $fileName.$sound->getSoundFile()->guessExtension();
        }

        $this->generateSoundFilename($sound);
    }
}