<?php

declare(strict_types=1);

namespace StarCitizenWiki\MediaWikiApi\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use MediaWiki\OAuthClient\Exception;
use MediaWiki\OAuthClient\Request;
use MediaWiki\OAuthClient\SignatureMethod\HmacSha1;
use StarCitizenWiki\MediaWikiApi\Api\Response\MediaWikiResponse;
use StarCitizenWiki\MediaWikiApi\Contracts\ApiRequestContract;

/**
 * Create and Send a Request to a MediaWiki Api
 */
class MediaWikiRequestFactory
{
    private const MEDIAWIKI_API_URL = 'mediawiki.api_url';

    /**
     * @var ApiRequestContract
     */
    private ApiRequestContract $apiRequest;

    /**
     * @var Client The guzzle client
     */
    private Client $client;

    /**
     * MediaWikiRequestFactory constructor.
     *
     * @param ApiRequestContract $apiRequest
     */
    public function __construct(ApiRequestContract $apiRequest)
    {
        $this->apiRequest = $apiRequest;
    }

    /**
     * Makes a request with the given ApiRequestContract and returns a MediaWikiResponse from Guzzle
     *
     * @param array $requestConfig Optional request config passed directly into the Guzzle client creation
     *
     * @return MediaWikiResponse
     */
    public function getResponse(array $requestConfig = []): MediaWikiResponse
    {
        if ($this->client === null) {
            $this->makeClient($requestConfig);
        }

        try {
            $response = $this->client->request(
                $this->apiRequest->requestMethod(),
                $this->makeRequestUrl(),
                $this->getRequestOptions()
            );
        } catch (RequestException $e) {
            if (!$e->hasResponse()) {
                $response = new Response(
                    $e->getCode(),
                    [],
                    sprintf(
                        "Request URI: %s\nRequest Body: %s\nRequest Method: %s",
                        $e->getRequest()->getUri(),
                        (string)$e->getRequest()->getBody() ?: 'empty',
                        $e->getRequest()->getMethod()
                    )
                );
            } else {
                $response = $e->getResponse();
            }
        }

        return MediaWikiResponse::fromGuzzleResponse($response);
    }

    /**
     * Get the current guzzle client
     *
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Set a guzzle client to be used for the request
     *
     * @param Client $client
     */
    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    /**
     * @param array $requestConfig
     *
     * @return void
     */
    private function makeClient(array $requestConfig): void
    {
        $mediaWikiRequest = $this->makeRequestObject();

        try {
            $header = $mediaWikiRequest->toHeader();
        } catch (Exception $e) {
            $header = 'Authorization: OAuth';
        }
        $header = explode(':', $header);

        $baseConfig = [
            'timeout' => config('mediawiki.request.timeout', 1.0),
            'http_errors' => false,
            'headers' => [
                $header[0] => $header[1],
            ],
        ];

        $this->client = new Client(array_replace_recursive($baseConfig, $requestConfig));
    }

    /**
     * Creates the request object
     *
     * @return Request
     */
    private function makeRequestObject(): Request
    {
        if ($this->apiRequest->needsAuthentication()) {
            return $this->makeSignedRequestObject();
        }

        return new Request(
            $this->apiRequest->requestMethod(),
            config(self::MEDIAWIKI_API_URL),
            $this->apiRequest->queryParams()
        );
    }

    /**
     * Creates a signed request object
     *
     * @return Request
     */
    private function makeSignedRequestObject(): Request
    {
        $manager = app('mediawikiapi.manager');

        $mediaWikiRequest = Request::fromConsumerAndToken(
            $manager->getConsumer(),
            $manager->getToken(),
            $this->apiRequest->requestMethod(),
            config(self::MEDIAWIKI_API_URL),
            $this->apiRequest->queryParams()
        );

        $mediaWikiRequest->signRequest(
            new HmacSha1(),
            $manager->getConsumer(),
            $manager->getToken()
        );

        return $mediaWikiRequest;
    }

    /**
     * Create the Request URL
     *
     * @return string
     */
    private function makeRequestUrl(): string
    {
        $url = config(self::MEDIAWIKI_API_URL);

        if (strtoupper($this->apiRequest->requestMethod()) === 'GET') {
            $url = sprintf('%s?%s', $url, http_build_query($this->apiRequest->queryParams()));
        }

        return (string)$url;
    }

    /**
     * @return array
     */
    private function getRequestOptions(): array
    {
        if ('post' === strtolower($this->apiRequest->requestMethod())) {
            $data = [
                'form_params' => $this->apiRequest->queryParams(),
            ];
        }

        return $data ?? [];
    }
}
