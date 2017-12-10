<?php

namespace LearningSystem\Infrastructure\Observer;

interface Subject
{
    public function attach(Observer $observer): Subject;
    public function notify(\Closure $postUpdateEvent = null): void;
}