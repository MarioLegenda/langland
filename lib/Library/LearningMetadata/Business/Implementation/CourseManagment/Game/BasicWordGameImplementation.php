<?php

namespace Library\LearningMetadata\Business\Implementation\CourseManagment\Game;

use AdminBundle\Entity\Course;
use Library\Infrastructure\Helper\Deserializer;
use Library\LearningMetadata\Presentation\Template\TemplateWrapper;
use Library\LearningMetadata\Repository\Implementation\CourseManagment\Game\BasicWordGameRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;

class BasicWordGameImplementation
{
    /**
     * @var BasicWordGameRepository $basicWordGameRepository
     */
    private $basicWordGameRepository;
    /**
     * @var TemplateWrapper $templateWrapper
     */
    private $templateWrapper;
    /**
     * @var Deserializer $deserializer
     */
    private $deserializer;
    /**
     * @var LoggerInterface $logger
     */
    private $logger;

    public function __construct(
        BasicWordGameRepository $basicWordGameRepository,
        TemplateWrapper $templateWrapper,
        Deserializer $deserializer,
        LoggerInterface $logger
    ) {
        $this->basicWordGameRepository = $basicWordGameRepository;
        $this->templateWrapper = $templateWrapper;
        $this->deserializer = $deserializer;
        $this->logger = $logger;
    }
    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->basicWordGameRepository->findAll();
    }
    /**
     * @param Course $course
     * @return Response
     */
    public function getListPresentation(Course $course)
    {
        $template = '::Admin/Template/Panel/CourseManager/_listing.html.twig';
        $data = [
            'listing_title' => 'Games',
            'course' => $course,
            'games' => $this->findAll(),
            'template' => '/BasicWordGame/index.html.twig',
        ];

        return new Response($this->templateWrapper->getTemplate($template, $data), 200);
    }
}