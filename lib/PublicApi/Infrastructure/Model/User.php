<?php

namespace PublicApi\Infrastructure\Model;

use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class User
 * @package PublicApi\Infrastructure\Model
 *
 * @Serializer\ExclusionPolicy("none")
 */
class User
{
    /**
     * @var int $id
     * @Type("int")
     * @Assert\Type("int")
     * @Assert\NotNull(message="id cannot be null")
     */
    private $id;
    /**
     * @var LanguageSession $currentLanguageSession
     * @Type("PublicApi\Infrastructure\Model\LanguageSession")
     */
    private $currentLanguageSession;
    /**
     * @var LanguageSession[] $languageSessions
     * @Type("array<PublicApi\Infrastructure\Model\LanguageSession>")
     */
    private $languageSessions;
    /**
     * @var string $name
     * @Type("string")
     * @Assert\Type("string")
     * @Assert\NotBlank(message="name cannot be null")
     */
    private $name;
    /**
     * @var string $lastname
     * @Type("string")
     * @Assert\Type("string")
     * @Assert\NotBlank(message="lastname cannot be null")
     */
    private $lastname;
    /**
     * @var string $username
     * @Type("string")
     * @Assert\Type("string")
     * @Assert\NotBlank(message="username cannot be null")
     */
    private $username;
    /**
     * @var bool $enabled
     * @Type("bool")
     * @Assert\Type("bool")
     * @Assert\NotNull(message="name cannot be null")
     */
    private $enabled;
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    /**
     * @param LanguageSession[] $languageSessions
     */
    public function setLanguageSessions(array $languageSessions): void
    {
        $this->languageSessions = $languageSessions;
    }
    /**
     * @return LanguageSession[]
     */
    public function getLanguageSessions(): array
    {
        return $this->languageSessions;
    }
    /**
     * @param LanguageSession $currentLanguageSession
     */
    public function setCurrentLanguageSession(LanguageSession $currentLanguageSession): void
    {
        $this->currentLanguageSession = $currentLanguageSession;
    }
    /**
     * @return LanguageSession
     */
    public function getCurrentLanguageSession(): LanguageSession
    {
        return $this->currentLanguageSession;
    }
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }
    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }
    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
    /**
     * @param ExecutionContextInterface $context
     * @param $payload
     * @Assert\Callback
     */
    public function validateLanguageSession(ExecutionContextInterface $context, $payload)
    {
        /** @var LanguageSession $currentLanguageSession */
        $currentLanguageSession = $this->getCurrentLanguageSession();

        if (!empty($currentLanguageSession)) {
            if (!$currentLanguageSession instanceof LanguageSession) {
                $message = sprintf(
                    'currentLanguageSession has to be an instance of %s',
                    LanguageSession::class
                );

                $context
                    ->buildViolation($message)
                    ->atPath('currentLanguageSession')
                    ->addViolation();
            }
        }

        /** @var LanguageSession[] $languageSessions */
        $languageSessions = $this->getLanguageSessions();

        if (!empty($languageSessions)) {
            foreach ($languageSessions as $languageSession) {
                if (!$languageSession instanceof LanguageSession) {
                    $message = sprintf(
                        'languageSessions array can contain only instances of %s',
                        LanguageSession::class
                    );

                    $context
                        ->buildViolation($message)
                        ->atPath('languageSessions')
                        ->addViolation();
                }
            }
        }
    }
}