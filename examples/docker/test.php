<?php

require_once(__DIR__ . '/vendor/autoload.php');

use Khaledalam\BardSdkPhp\ApiKey\ApiKeyCookie;
use Khaledalam\BardSdkPhp\Bard;

try {
    // __Secure-1PSID value
    $apiKey = '_YOUR_COOKIE_VALUE_';

    $question = "What is the recent stable version LTS of Ubuntu?";

    $bard = new Bard(new ApiKeyCookie($apiKey));

    $answer = $bard->ask($question);

    print_r($answer['content']);
    /*
    Output (May-2023):

    The most recent stable version of Ubuntu LTS is Ubuntu 22.04 LTS, codenamed "Jammy Jellyfish." It was released on April 21, 2022, and will be supported with free security and maintenance updates for five years, until April 2027.

    Ubuntu LTS releases are designed for use in enterprise and server environments, where reliability and stability are critical. They are also a good choice for home users who want a reliable and long-lasting operating system.
    */

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
