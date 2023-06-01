<?php

declare(strict_types=1);

use Khaledalam\BardSdkPhp\ApiKey\ApiKeyCookie;
use Khaledalam\BardSdkPhp\Bard;
use PHPUnit\Framework\TestCase;

class BardTest extends TestCase
{
    public function testUseInvalidApikey(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Failed to fetch SNlM0e.");

        (new Bard(new ApiKeyCookie("Wrong api key value")));
    }

    public function testUseInvalidApikeyType(): void
    {
        $this->expectException(\TypeError::class);
        (new Bard("test"));
    }
}


