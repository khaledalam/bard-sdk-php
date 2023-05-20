<?php

declare(strict_types=1);

namespace Khaledalam\BardSdkPhp\ApiKey;

interface ApiKeyFactory
{
    public function ask(string $question): array;

    public function getApiKeyName(): string;

    public function getApiKeyValue(): string;

    public function getBaseURL(): string;

    public function isStillSupported(): bool;
}