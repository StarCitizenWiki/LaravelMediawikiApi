<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: Hanne
 * Date: 08.10.2018
 * Time: 13:58
 */

namespace StarCitizenWiki\MediaWikiApi\Api\Response;

use GuzzleHttp\Psr7\Response;
use function GuzzleHttp\json_decode;

/**
 * Mediaw Wiki Response Object
 */
class MediaWikiResponse
{
    private const MEDIA_WIKI_API_ERROR = 'MediaWiki-API-Error';

    /**
     * @var \GuzzleHttp\Psr7\Response
     */
    private $rawResponse;

    /**
     * @var string Raw Response Body
     */
    private $rawBody;

    /**
     * Parsed Body
     *
     * @var array
     */
    private $body;

    /**
     * @var int HTTP Status Code
     */
    private $status;

    /**
     * @var array Response Headers
     */
    private $headers;

    /**
     * MediaWikiResponse constructor.
     * @param string                         $body     Response Body
     * @param int                            $status   HTTP Status
     * @param array                          $headers  HTTP Repsonse Headers
     * @param \GuzzleHttp\Psr7\Response|null $response Raw Guzzle Response
     */
    public function __construct(string $body, int $status, array $headers, ?Response $response = null)
    {
        $this->rawBody = $body;
        $this->status = $status;
        $this->headers = $headers;
        $this->rawResponse = $response;

        $this->setBody();
    }

    /**
     * Create a MediaWiki Response from Guzzle
     *
     * @param \GuzzleHttp\Psr7\Response $response
     *
     * @return \StarCitizenWiki\MediaWikiApi\Api\Response\MediaWikiResponse
     */
    public static function fromGuzzleResponse(Response $response)
    {
        return new self((string) $response->getBody(), $response->getStatusCode(), $response->getHeaders(), $response);
    }

    /**
     * Check if the Response has errors
     *
     * @return bool
     */
    public function hasErrors(): bool
    {
        return isset($this->body['error']) || $this->rawResponse->hasHeader(self::MEDIA_WIKI_API_ERROR);
    }

    /**
     * Get Api Errors
     *
     * @return array
     */
    public function getErrors(): array
    {
        if (!$this->hasErrors()) {
            return [];
        }

        $errors = [];

        if (isset($this->body['error'])) {
            $errors = $this->body['error'];
        }

        if (isset($this->headers[self::MEDIA_WIKI_API_ERROR])) {
            $errors = array_merge($errors, $this->headers[self::MEDIA_WIKI_API_ERROR]);
        }

        return $errors;
    }

    /**
     * Check if Response has Warnings
     *
     * @return bool
     */
    public function hasWarnings(): bool
    {
        return isset($this->body['warnings']);
    }

    /**
     * Get Api Warnings
     *
     * @return array
     */
    public function getWarnings(): array
    {
        if (!$this->hasWarnings()) {
            return [];
        }

        return $this->body['warnings'] ?? [];
    }

    /**
     * Get the Query Response
     *
     * @return array
     */
    public function getQuery(): array
    {
        return $this->body['query'] ?? [];
    }

    /**
     * Parses the Response Body
     * Sets an Error if content type is json, but the content can't be decoded
     */
    private function setBody(): void
    {
        if (str_contains($this->headers['Content-Type'][0] ?? '', 'application/json')) {
            try {
                $this->body = json_decode($this->rawBody, true);
            } catch (\InvalidArgumentException $e) {
                $this->body = [
                    'error' => [
                        'code' => 'invalidbody',
                        'info' => $e->getMessage(),
                    ],
                ];
            }
        }
    }
}
