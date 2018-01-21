<?php

namespace LearningSystem\Infrastructure;

class Questions
{
    /**
     * @var array $questions
     */
    private $questions = [
        'speaking_languages' => [
            'question' => 'Except your native language, how many languages do you already speak?',
            'name' => 'speaking_languages',
            'answers' => [],
        ],
        'profession' => [
            'question' => 'In what field do you currently work in?',
            'name' => 'profession',
            'answers' => [
                'arts_and_entertainment' => 'Arts and entertainment',
                'business' => 'Business',
                'industrial_and_manufacturing' => 'Industrial and manufacturing',
                'law_enforcement_and_armed_forces' => 'Law Enforcement and Armed Forces',
                'science_and_technology' => 'Science and technology',
                'healthcare_and_medicine' => 'Healthcare and medicine',
                'service_oriented_occupation' => 'Service oriented occupation',
            ],
        ],
        'person_type' => [
            'question' => 'Are you a risk taker or a \'sure thing\' person?',
            'name' => 'person_type',
            'answers' => [
                'risk_taker' => 'Risk taker',
                'sure_thing' => '\'Sure thing\' person'
            ],
        ],
        'learning_time' => [
            'question' => 'What is the best time of day for you to learn?',
            'name' => 'learning_time',
            'answers' => [
                'morning' => 'Morning',
                'evening' => 'Evening',
                'early_afternoon' => 'Early afternoon',
                'late_afternoon' => 'Late afternoon',
                'night' => 'Night'
            ],
        ],
        'free_time' => [
            'question' => 'How much free time do you have in a day?',
            'name' => 'free_time',
            'answers' => [
                '30_minutes' => '30 minutes',
                '1_hour' => '1 hour',
                '1_hour_and_a_half' => '1 hour and a half',
                '2_hours' => '2 hours',
                'all_time' => 'I\'ve got all the time in the world',
            ],
        ],
        'memory' => [
            'question' => 'Would you say that you have a better short term or long term memory? Or is it something in between?',
            'name' => 'memory',
            'answers' => [
                'short_term' => 'Short term memory',
                'long_term' => 'Long term memory',
                'in_between' => 'Somewhere in between',
            ],
        ],
        'challenges' => [
            'question' => 'Do you embrace challenges?',
            'name' => 'challenges',
            'answers' => [
                'likes_challenges' => 'I embrace challenges',
                'dislike_challenges' => 'I don\'t like challenges',
            ],
        ],
        'stressful_job' => [
            'question' => 'Is your job stressful?',
            'name' => 'stressful_job',
            'answers' => [
                'stressful_job' => 'Yes',
                'nonstressful_job' => 'No',
            ],
        ],
    ];
    /**
     * @return array
     */
    public function getQuestions(): array
    {
        return $this->questions;
    }
    /**
     * @param string $type
     * @return array|null
     */
    public function getQuestion(string $type): ?array
    {
        if (!$this->hasQuestion($type)) {
            return null;
        }

        return $this->questions[$type];
    }
    /**
     * @param string $type
     * @return bool
     */
    public function hasQuestion(string $type): bool
    {
        return array_key_exists($type, $this->questions);
    }
    /**
     * @param string $type
     * @throws \RuntimeException
     */
    public function checkValidQuestion(string $type)
    {
        if (!$this->hasQuestion($type)) {
            $message = sprintf('Invalid question type \'%s\'', $type);
            throw new \RuntimeException($message);
        }
    }
    /**
     * @param string $type
     * @param string $answer
     * @param array|\Closure[] $customValidators
     * @throws \RuntimeException
     */
    public function checkValidAnswer(string $type, string $answer, array $customValidators = [])
    {
        $this->checkValidQuestion($type);

        $answers = $this->getQuestion($type)['answers'];

        if (array_key_exists($type, $customValidators)) {
            $customValidators[$type]->__invoke($type, $answer);

            return;
        }

        if (!array_key_exists($answer, $answers)) {
            $message = sprintf('Invalid question answer \'%s\' for type \'%s\'', $answer, $type);
            throw new \RuntimeException($message);
        }
    }
    /**
     * @param array $types
     * @throws \RuntimeException
     */
    public function areTypesValid(array $types)
    {
        foreach ($types as $type => $value) {
            if (!$this->hasQuestion($type)) {
                $message = sprintf('Invalid question type \'%s\' given', $type);
                throw new \RuntimeException($message);
            }
        }
    }
    /**
     * @return array
     */
    public function getValidTypes(): array
    {
        return array_keys($this->questions);
    }


}