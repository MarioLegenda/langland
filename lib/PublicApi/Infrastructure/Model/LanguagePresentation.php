<?php

namespace PublicApi\Infrastructure\Model;

use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class LanguagePresentation
 * @package PublicApi\Language\Infrastructure\Model
 *
 * @Serializer\ExclusionPolicy("none")
 * @Serializer\VirtualProperty(
 *     "urls",
 *     exp="object.parseUrls()",
 * )
 */
class LanguagePresentation
{
    /**
     * @var int $id
     * @Type("int")
     * @Serializer\Groups("language_presentation")
     * @Assert\NotBlank(message="id cannot be blank")
     * @Assert\Type("int", message="id has to be an integer")
     */
    private $id;
    /**
     * @var string $name
     * @Type("string")
     * @Serializer\Groups("language_presentation")
     * @Assert\NotBlank(message="name cannot be blank")
     * @Assert\Type("string", message="name has to be an string")
     */
    private $name;
    /**
     * @var string $description
     * @Type("string")
     * @Serializer\Groups("language_presentation")
     * @Assert\NotBlank(message="description cannot be blank")
     * @Assert\Type("string", message="description has to be an string")
     */
    private $description;
    /**
     * @var bool $showOnPage
     * @Type("bool")
     * @Serializer\Groups("language_presentation")
     * @Assert\NotBlank(message="id cannot be blank")
     * @Assert\Type("bool", message="showOnPage has to be a boolean")
     */
    private $showOnPage;
    /**
     * @var array $images
     * @Type("array")
     * @Serializer\Accessor(setter="parseImages")
     * @Serializer\Groups("language_presentation")
     * @Assert\NotBlank(message="id cannot be blank")
     * @Assert\Type("array", message="images has to be an array")
     */
    private $images;
    /**
     * @var bool $alreadyLearning
     * @Type("bool")
     * @Serializer\Groups("language_presentation")
     * @Assert\NotNull(message="alreadyLearning cannot be null")
     * @Assert\Type("bool", message="alreadyLearning has to be a boolean")
     */
    private $alreadyLearning = false;
    /**
     * @var array $urls
     * @Type("array")
     * @Serializer\Groups("language_presentation")
     * @Assert\NotNull(message="urls cannot be null")
     * @Assert\Type("array", message="urls has to be an array")
     */
    private $urls;
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
    public function getDescription(): string
    {
        return $this->description;
    }
    /**
     * @return array
     */
    public function getImages(): array
    {
        return $this->images;
    }

    public function setAlreadyLearning(): void
    {
        $this->alreadyLearning = true;
    }
    /**
     * @return bool
     */
    public function isAlreadyLearning(): bool
    {
        return $this->alreadyLearning;
    }
    /**
     * @return array
     */
    public function getUrls(): array
    {
        return $this->urls;
    }
    /**
     * @return bool
     */
    public function getShowOnPage(): bool
    {
        return $this->showOnPage;
    }
    /**
     * @param array $images
     */
    public function parseImages(array $images)
    {
        $parsed = [];
        $parsed['cover'] = sprintf(
            '%s/%s',
            $images['cover_image']['relativePath'],
            $images['cover_image']['originalName']
        );

        $parsed['icon'] = sprintf(
            '%s/%s',
            $images['icon']['relativePath'],
            $images['icon']['originalName']
        );

        $this->images = $parsed;
    }

    public function parseUrls(): void
    {
        $this->urls = [
            'backend_url' => null,
            'frontend_url' => sprintf(
                'language/%s/%d',
                $this->getName(),
                $this->getId())
        ];
    }
}