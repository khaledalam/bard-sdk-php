<?php

declare(strict_types=1);

namespace Khaledalam\BardSdkPhp\ApiKey;

/**
 * Class ApiKey: I will implement this class once api key became ready from Google side.
 */
class ApiKey implements ApiKeyFactory
{
    public function __construct()
    {
        // @TODO: Implement __construct() method.
    }

    public function ask(string $question): array
    {
        // @TODO: Implement ask() method.
        return [];
    }

    public function getApiKeyName(): string
    {
        // @TODO: Implement getApiKeyName() method.
        return '';
    }

    public function getApiKeyValue(): string
    {
        // @TODO: Implement getApiKeyValue() method.
        return '';
    }

    public function getBaseURL(): string
    {
        // @TODO: Implement getBaseURL() method.
        return '';
    }

    public function isStillSupported(): bool
    {
        return false;
    }
}