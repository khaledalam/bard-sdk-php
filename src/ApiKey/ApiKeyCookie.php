<?php

declare(strict_types=1);

namespace Khaledalam\BardSdkPhp\ApiKey;

use Exception;
use JsonException;
use Khaledalam\BardSdkPhp\Traits\ScraperTrait;
use RuntimeException;

/**
 * Class ApiKeyCookie: Reverse engineering of Google's Bard chatbot API.
 */
class ApiKeyCookie implements ApiKeyFactory
{
    use ScraperTrait;

    public string   $name           = '__Secure-1PSID';
    public string   $value;

    private string  $baseURL;
    private array   $headers;

    private string  $conversationId = "";
    private string  $responseId     = "";
    private string  $choiceId       = "";
    private int     $params_reqid   = 101010;
    private string  $body_at        = "";

    /**
     * Using session id "__Secure-1PSID" cookie value.
     *
     * @param string $apiKeyValue
     * @throws Exception
     */
    public function __construct(string $apiKeyValue)
    {
        $this->value = $apiKeyValue;
        $this->configure();
    }

    /**
     * Ask questions to bard chatbot and get response.
     *
     * @throws JsonException
     * @throws Exception
     */
    public function ask(string $question): array
    {
        [$data, $params] = $this->getPayload($question);

        $response = $this->curlExec($data, $params);

        $retLines = explode(PHP_EOL, $response);

        $retDic = json_decode($retLines[3], false, 512, JSON_THROW_ON_ERROR)[0][2];

        if ($retDic === null)
        {
            die("Error");
        }

        $retDecodedAnswer = json_decode($retDic, false, 512, JSON_THROW_ON_ERROR);

        $retAnswer = [
            'content' => $retDecodedAnswer[0][0]        ?? 'N/A',
            'conversationId' => $retDecodedAnswer[1][0] ?? 'N/A',
            'responseId' => $retDecodedAnswer[1][1]     ?? 'N/A',
            'factualityQueries' => $retDecodedAnswer[3] ?? 'N/A',
            'textQuery' => $retDecodedAnswer[2][0]      ?? 'N/A',
            'choices' => array_map(static function($item): array
                {
                    $obj['id'] = $item[0];
                    $obj['content'] = $item[1];
                    return $obj;
                }, $retDecodedAnswer[4]
            ),
        ];

        $this->conversationId = $retAnswer['conversationId'];
        $this->responseId = $retAnswer['responseId'];
        $this->choiceId = $retAnswer['choices'][0]['id'];
        $this->params_reqid += 100000;

        return $retAnswer;
    }

    /**
     * Get name of api key. e.g. {xkey: yValue} it returns "xKey"
     *
     * @return string
     */
    public function getApiKeyName(): string
    {
        return $this->name;
    }

    /**
     * Get value of api key. e.g. {xkey: yValue} it returns "yValue"
     *
     * @return string
     */
    public function getApiKeyValue(): string
    {
        return $this->value;
    }

    /**
     * Get base URL that will be used to send questions to bard.
     *
     * @return string
     */
    public function getBaseURL(): string
    {
        return "https://bard.google.com/_/BardChatUi/data/assistant.lamda.BardFrontendService/StreamGenerate";
    }

    /**
     * Indicate if this Api method allowed or not!
     * @TODO make it configurable and fetch from env variable.
     *
     * @return bool
     */
    public function isStillSupported(): bool
    {
        return true;
    }

    /**
     * @TODO randomize user-agent.
     *
     * @throws Exception
     */
    private function configure(): void
    {
        if (!$this->isStillSupported())
        {
            throw new RuntimeException('Provided api key type is not supported.');
        }

        $this->baseURL = $this->getBaseURL();
        $this->headers = [
            "Host" => "bard.google.com",
            "X-Same-Domain" => "1",
            "User-Agent" => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_1_7) AppleWebKit/534.22 (KHTML, like Gecko) Chrome/54.0.2127.252 Safari/536",
            "Content-Type" => "application/x-www-form-urlencoded;charset=UTF-8",
            "Origin" => "https://bard.google.com",
            "Referer" => "https://bard.google.com/",
        ];

        // Scrape "at" from random updates or faq bard pages.
        $scrapeValue = $this->scrapeByRegex('https://bard.google.com/' . ['updates', 'faq'][random_int(0,1)], "/SNlM0e\":\"(.*?)\"/");
        $this->body_at = $scrapeValue;

        // Recent payload validation was: 20 May 2023
        $digits = 6; // "_reqid" 6 digits
        $this->params_reqid = random_int(10 ** ($digits - 1), (10 ** $digits) -1);
    }

    /**
     * @param array $data
     * @param array $params
     * @return bool|string
     */
    private function curlExec(array $data, array $params)
    {
        $ch = curl_init($this->baseURL . '?' . http_build_query($params));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIE, $this->getApiKeyName() . '=' . $this->getApiKeyValue());
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10000);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    /**
     * Recent payload validation was: 20 May 2023.
     *
     * @throws Exception
     */
    private function getPayload(string $question): array
    {
        $params = [
            "bl" => "boq_assistant-bard-web-server_20230514.20_p0",
            "rt" => "c",
            "_reqid" => $this->params_reqid,
        ];

        $dataFReqInner = [
            [$question], null,
            [$this->conversationId, $this->responseId, $this->choiceId],
        ];
        $data = [
            "f.req" => json_encode([null, json_encode($dataFReqInner, JSON_THROW_ON_ERROR)], JSON_THROW_ON_ERROR),
            "at" => $this->body_at
        ];

        return [$data, $params];
    }
}