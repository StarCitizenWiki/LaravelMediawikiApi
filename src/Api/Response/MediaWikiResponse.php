<?php

declare(strict_types=1);

namespace StarCitizenWiki\MediaWikiApi\Api\Response;

use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

use function GuzzleHttp\json_decode;

/**
 * MediaWiki Response Object
 */
class MediaWikiResponse
{
    private const MEDIA_WIKI_API_ERROR = 'MediaWiki-API-Error';

    /**
     * @var Response|null
     */
    private ?Response $rawResponse;

    /**
     * @var string Raw Response Body
     */
    private string $rawBody;

    /**
     * Parsed Body
     *
     * @var array
     */
    private array $body;

    /**
     * @var int HTTP Status Code
     */
    private int $status;

    /**
     * @var array Response Headers
     */
    private array $headers;

    /**
     * MediaWikiResponse constructor.
     *
     * @param string        $body     Response Body
     * @param int           $status   HTTP Status
     * @param array         $headers  HTTP Response Headers
     * @param Response|null $response Raw Guzzle Response
     */
    public function __construct(string $body, int $status, array $headers, ?Response $response = null)
    {
        $this->rawBody = $body;
        $this->status = $status;
        $this->headers = $headers;
        $this->rawResponse = $response;

        $this->checkResponse();
        $this->setBody();
    }

    /**
     * Create a MediaWiki Response from Guzzle
     *
     * @param Response $response
     *
     * @return MediaWikiResponse
     */
    public static function fromGuzzleResponse(Response $response): MediaWikiResponse
    {
        return new self((string)$response->getBody(), $response->getStatusCode(), $response->getHeaders(), $response);
    }

    /**
     * Check if the Response was successful
     *
     * @return bool
     */
    public function successful(): bool
    {
        return !$this->hasErrors() && !$this->hasWarnings() && $this->status === 200;
    }

    /**
     * Check if the Response has errors
     *
     * @return bool
     */
    public function hasErrors(): bool
    {
        return isset($this->body['error']) || ($this->rawResponse !== null && $this->rawResponse->hasHeader(
            self::MEDIA_WIKI_API_ERROR
        ));
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
     * Get Api Errors
     *
     * @return array
     */
    public function getErrors(): array
    {
        if (!$this->hasErrors()) {
            return [];
        }

        $errors = $this->body['error'] ?? [];

        if (empty($errors) && isset($this->headers[self::MEDIA_WIKI_API_ERROR])) {
            $errors = $this->headers[self::MEDIA_WIKI_API_ERROR];
        }

        if (!is_array($errors)) {
            $errors = [$errors];
        }

        return $errors;
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
     * @return array
     */
    public function getBody(): array
    {
        return $this->body ?? [];
    }

    /**
     * Checks if the Guzzle Response was successful
     */
    private function checkResponse(): void
    {
        if (null !== $this->rawResponse && $this->rawResponse->getStatusCode() !== 200) {
            $this->setError(
                HttpResponse::$statusTexts[$this->rawResponse->getStatusCode()] ?? 'undefined',
                $this->rawBody
            );
        }
    }

    /**
     * Adds an Error to the Error array
     *
     * @param string $code
     * @param string $message
     */
    private function setError(string $code, string $message): void
    {
        $error = [
            'code' => $code,
            'info' => $message,
        ];

        if (isset($this->body['error'])) {
            $this->body['error'][] = $error;
        } else {
            $this->body['error'] = $error;
        }
    }

    /**
     * Parses the Response Body
     * Sets an Error if content type is json, but the content can't be decoded
     */
    private function setBody(): void
    {
        if (!array_key_exists('Content-Type', $this->headers)) {
            $this->setError('missing-content-type-header', 'Content-Type Header is missing');

            return;
        }

        if (strpos($this->headers['Content-Type'][0] ?? '', 'application/json') !== false) {
            try {
                $this->body = json_decode($this->rawBody, true);
            } catch (InvalidArgumentException $e) {
                $this->setError('invalidbody', $e->getMessage());
            }
        }
    }
}
