<?php

declare(strict_types=1);

namespace Khaledalam\BardSdkPhp\Traits;

use RuntimeException;

trait ScraperTrait
{
    /**
     * Scrape content from web page using Regex and cURL.
     *
     * @param string $pageURL
     * @param string $regex
     * @return string
     */
    public function scrapeByRegex(string $pageURL, string $regex): string
    {
        $ch = curl_init($pageURL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_COOKIE, $this->getApiKeyName() . '=' . $this->getApiKeyValue());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $result = [];

        preg_match($regex, $response, $result);

        if (count($result) !== 2)
        {
            throw new RuntimeException('Failed to fetch SNlM0e.');
        }

        return $result[1];
    }
}