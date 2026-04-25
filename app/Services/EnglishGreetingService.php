<?php

namespace App\Services;

class EnglishGreetingService implements GreetingServiceInterface
{
    public function greet(string $name): string
    {
        return "Hello, {$name}! Welcome to our application.";
    }
}
