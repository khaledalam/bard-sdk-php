<?php

require_once('./vendor/autoload.php');

use Khaledalam\BardSdkPhp\ApiKey\ApiKeyCookie;
use Khaledalam\BardSdkPhp\Bard;

try {
    // __Secure-1PSID value
    $apiKey = '_YOUR_COOKIE_VALUE_'; // <- Change this value

    $question1 = "What is the date time now in UK?";
    $question2 = "What is the date time now in Japan?";

    $bard = new Bard(new ApiKeyCookie($apiKey));

    $answer1 = $bard->ask($question1);

    $answer2 = $bard->ask($question2);

    print_r($answer1);

    print_r($answer2);

    // ...

} catch (Exception $e)
{
    die("Error: " . $e->getMessage());
}