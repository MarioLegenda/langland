<?php

namespace Armor\Infrastructure\Communicator\Session;

use AdminBundle\Entity\Language as ForeignDomainModel;
use AdminBundle\Entity\Language;
use Armor\Infrastructure\Model\Language as DomainModel;
use Armor\Repository\LanguageRepository;
use Library\Infrastructure\Helper\SerializerWrapper;
use Library\Infrastructure\Logic\DomainCommunicatorInterface;
use Armor\Infrastructure\Model\Language as ArmorLanguageModel;

class LanguageSessionCommunicator implements DomainCommunicatorInterface
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var LanguageRepository $languageRepository
     */
    private $languageRepository;
    /**
     * @var SerializerWrapper $serializerWrapper
     */
    private $serializerWrapper;
    /**
     * @var ForeignDomainModel $foreignDomainModel
     */
    private $foreignDomainModel;
    /**
     * @var DomainModel $domainModel
     */
    private $domainModel;
    /**
     * LanguageSessionCommunicator constructor.
     * @param LanguageRepository $languageRepository
     * @param SerializerWrapper $serializerWrapper
     */
    public function __construct(
        LanguageRepository $languageRepository,
        SerializerWrapper $serializerWrapper
    ) {
        $this->languageRepository = $languageRepository;
        $this->serializerWrapper = $serializerWrapper;
    }
    /**
     * @param int $id
     */
    public function initializeSession(int $id): void
    {
        $this->id = $id;
    }
    /**
     * @return object
     */
    public function getForeignDomainModel(): object
    {
        if (!$this->foreignDomainModel instanceof ArmorLanguageModel) {
            $domainModel = $this->getDomainModel();

            $this->foreignDomainModel = $this->serializerWrapper->convertFromTo(
                $domainModel,
                ['communication_model'],
                ArmorLanguageModel::class
            );
        }

        return $this->foreignDomainModel;
    }
    /**
     * @inheritdoc
     */
    public function getDomainModel(): object
    {
        if (!$this->domainModel instanceof Language) {
            $this->domainModel = $this->getLanguage();
        }

        return $this->domainModel;
    }
    /**
     * @return Language
     */
    private function getLanguage(): Language
    {
        /** @var Language $language */
        $language = $this->languageRepository->find($this->id);

        return $language;
    }
}