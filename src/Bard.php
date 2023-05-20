<?php

declare(strict_types=1);

namespace Khaledalam\BardSdkPhp;

use Khaledalam\BardSdkPhp\ApiKey\ApiKeyFactory;

class Bard
{
    private ApiKeyFactory $apiKey;

    public function __construct(ApiKeyFactory $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function ask(string $question): array
    {
        return $this->apiKey->ask($question);
    }
}



