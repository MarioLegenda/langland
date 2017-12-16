<?php

namespace Tests\LearningSystem\TestHelpers;

class Questions
{
    private $questions = [
            'speaking_languages' => [
                'question' => 'Except your native language, how many languages do you already speak?',
                'name' => 'speaking_languages',
                'value' => 0,
            ],
            'profession' => [
                'question' => 'In what field do you currently work in?',
                'name' => 'profession',
                'value' => 'Education',
            ],
            'person_type' => [
                'question' => 'Are you a risk taker or a \'sure thing\' person?',
                'name' => 'person_type',
                'value' => 0,
            ],
            'learning_time' => [
                'question' => 'What is the best time of day for you to learn?',
                'name' => 'learning_time',
                'value' => 'evening',
            ],
            'free_time' => [
                'question' => 'How much free time do you have in a day?',
                'name' => 'free_time',
                'value' => 1,
            ],
            'memory' => [
                'question' => 'Would you say that you have a better short term or long term memory? Or is it something in between?',
                'name' => 'memory',
                'value' => 1,
            ],
            'challenges' => [
                'question' => 'Do you embrace challenges?',
                'name' => 'challenges',
                'value' => 0,
            ],
            'stressful_job' => [
                'question' => 'Is your job stressful?',
                'name' => 'stressful_job',
                'value' => 0,
            ],
        ];
    /**
     * @param string $name
     * @param $value
     * @return Questions
     */
    public function setQuestion(string $name, $value): Questions
    {
        if (!$this->hasQuestion($name)) {
            throw new \RuntimeException(sprintf('Question \'%s\' not found', $name));
        }

        $this->questions[$name]['value'] = $value;

        return $this;
    }
    /**
     * @param string $name
     * @return bool
     */
    public function hasQuestion(string $name): bool
    {
        return array_key_exists($name, $this->questions);
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        $questions = [];

        foreach ($this->questions as $name => $question) {
            $questions[$name] = [
                'name' => $question['name'],
                'value' => $question['value'],
            ];
        }

        return $questions;
    }
}