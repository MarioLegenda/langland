<?php

namespace AdminBundle\Entity;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class TheoryDeck
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var int $theoryId
     */
    private $theoryId;
    /**
     * @var string $internalName
     */
    private $internalName;
    /**
     * @var string $deckData
     */
    private $deckData = null;
    /**
     * @var bool $showOnPage
     */
    private $showOnPage = false;
    /**
     * @var string $internalDescription
     */
    private $internalDescription = null;
    /**
     * @var int $ordering
     */
    private $ordering = null;
    /**
     * @var Sound[] $sounds
     */
    private $sounds = array();
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * @return mixed
     */
    public function getTheoryId()
    {
        return $this->theoryId;
    }
    /**
     * @param mixed $theoryId
     * @return TheoryDeck
     */
    public function setTheoryId($theoryId) : TheoryDeck
    {
        $this->theoryId = $theoryId;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getInternalName()
    {
        return $this->internalName;
    }
    /**
     * @param mixed $internalName
     * @return TheoryDeck
     */
    public function setInternalName($internalName) : TheoryDeck
    {
        $this->internalName = $internalName;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getDeckData()
    {
        return $this->deckData;
    }
    /**
     * @param mixed $deckData
     * @return TheoryDeck
     */
    public function setDeckData($deckData) : TheoryDeck
    {
        if (empty($deckData)) {
            $this->deckData = null;

            return $this;
        }

        $this->deckData = $deckData;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getShowOnPage()
    {
        return $this->showOnPage;
    }
    /**
     * @param mixed $showOnPage
     * @return TheoryDeck
     */
    public function setShowOnPage($showOnPage) : TheoryDeck
    {
        if (is_bool($showOnPage)) {
            $this->showOnPage = $showOnPage;

            return $this;
        }

        $this->showOnPage = ($showOnPage === 'true') ? true : false;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getOrdering()
    {
        return $this->ordering;
    }
    /**
     * @param mixed $ordering
     * @return TheoryDeck
     */
    public function setOrdering($ordering) : TheoryDeck
    {
        if (empty($ordering)) {
            $this->ordering = null;

            return $this;
        }

        $this->ordering = $ordering;

        return $this;
    }
    /**
     * @return string
     */
    public function getInternalDescription()
    {
        return $this->internalDescription;
    }
    /**
     * @param string $internalDescription
     * @return TheoryDeck
     */
    public function setInternalDescription($internalDescription) : TheoryDeck
    {
        if (empty($internalDescription)) {
            $this->internalDescription = null;

            return $this;
        }

        $this->internalDescription = $internalDescription;

        return $this;
    }
    /**
     * @param Sound $sound
     * @return TheoryDeck
     */
    public function addSound(Sound $sound) : TheoryDeck
    {
        $this->sounds[] = $sound;

        return $this;
    }
    /**
     * @return bool
     */
    public function hasSounds() : bool
    {
        if (empty($this->sounds)) {
            return false;
        }

        foreach ($this->sounds as $sound) {
            if (!$sound instanceof Sound) {
                return false;
            }
        }

        return true;
    }
    /**
     * @return mixed
     */
    public function getSounds()
    {
        return $this->sounds;
    }
    /**
     * @param array $sounds
     * @return TheoryDeck
     */
    public function setSounds(array $sounds) : TheoryDeck
    {
        if (empty($sounds)) {
            return $this;
        }

        foreach ($sounds as $sound) {
            $this->addSound($sound);
        }

        return $this;
    }

    public function validateSounds(ExecutionContextInterface $context)
    {
        $sounds = $this->getSounds();

        if (empty($sounds)) {
            return;
        }

        $formats = array('mpga');
        foreach ($sounds as $sound) {
            $soundFile = $sound->getSoundFile();

            if (in_array($soundFile->guessExtension(), $formats) === false) {
                $context->buildViolation(sprintf('Allowed formats for audio files are %s', implode(', ', $formats)))
                    ->atPath('internalName')
                    ->addViolation();
            }

            break;
        }
    }
    /**
     * @param Request $request
     * @return TheoryDeck
     */
    public static function createFromRequest(Request $request)
    {
        $deck = new TheoryDeck();

        if ($request->request->has('deck_id')) {
            $deckId = $request->request->get('deck_id');

            $deck->setId($deckId);

            if ($deckId === 'null') {
                $deck->setId(null);
            }
        }

        $deck->setShowOnPage($request->request->get('show_on_page'));

        return $deck
            ->setTheoryId($request->request->get('theory_id'))
            ->setInternalName($request->request->get('internal_name'))
            ->setDeckData(($request->request->has('deck_data')) ? $request->request->get('deck_data') : null)
            ->setOrdering(($request->request->has('ordering')) ? $request->request->get('ordering') : null)
            ->setInternalDescription(($request->request->has('internal_description')) ? $request->request->get('internal_description') : null);
    }
}
