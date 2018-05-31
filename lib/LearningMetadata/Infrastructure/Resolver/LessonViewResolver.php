<?php

namespace LearningMetadata\Infrastructure\Resolver;

use AdminBundle\Entity\Language;
use LearningMetadata\Business\ViewModel\Lesson\LessonView;
use LearningMetadata\Repository\Implementation\LanguageRepository;
use Library\Infrastructure\Helper\Deserializer;
use Library\Infrastructure\Helper\ModelValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class LessonViewResolver implements ArgumentValueResolverInterface
{
    /**
     * @var Deserializer $deserializer
     */
    private $deserializer;
    /**
     * @var ModelValidator $modelValidator
     */
    private $modelValidator;
    /**
     * @var LanguageRepository $languageRepository
     */
    private $languageRepository;
    /**
     * @var LessonView $lessonView
     */
    private $lessonView;
    /**
     * LessonViewResolver constructor.
     * @param Deserializer $deserializer
     * @param ModelValidator $modelValidator
     * @param LanguageRepository $languageRepository
     */
    public function __construct(
        Deserializer $deserializer,
        ModelValidator $modelValidator,
        LanguageRepository $languageRepository
    ) {
        $this->deserializer = $deserializer;
        $this->modelValidator = $modelValidator;
        $this->languageRepository = $languageRepository;
    }
    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return bool
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if (LessonView::class !== $argument->getType()) {
            return false;
        }

        if ($request->request->has('lesson')) {
            $lessonRequest = $request->request->get('lesson')['lesson'];

            $lessonView = $this->deserializer->create($lessonRequest, LessonView::class);

            if ($lessonView instanceof LessonView) {
                $this->modelValidator->tryValidate($lessonView);

                if ($this->modelValidator->hasErrors()) {
                    $message = sprintf(
                        '%s is not valid. Errors: %s',
                        LessonView::class,
                        $this->modelValidator->getErrorsString()
                    );

                    throw new \RuntimeException($message);
                }

                $language = $this->getLanguage($lessonRequest['languageId']);

                $lessonView->setLanguage($language);

                $this->lessonView = $lessonView;

                return true;
            }
        }

        return false;
    }
    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return \Generator
     */
    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        yield $this->lessonView;
    }
    /**
     * @param int $languageId
     * @return Language
     */
    private function getLanguage(int $languageId): Language
    {
        /** @var Language $language */
        $language = $this->languageRepository->find($languageId);

        if (!$language instanceof Language) {
            $message = sprintf(
                'Language with id \'%d\' not found',
                $languageId
            );

            throw new \RuntimeException($message);
        }

        return $language;
    }
}