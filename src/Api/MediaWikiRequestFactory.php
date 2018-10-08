<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 06.10.2018
 * Time: 17:38
 */

namespace StarCitizenWiki\MediaWikiApi\Api;

use GuzzleHttp\Client;
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
    const MEDIAWIKI_API_URL = 'mediawiki.api_url';

    /**
     * @var \StarCitizenWiki\MediaWikiApi\Contracts\ApiRequestContract
     */
    private $apiRequest;

    /**
     * MediaWikiRequestFactory constructor.
     *
     * @param \StarCitizenWiki\MediaWikiApi\Contracts\ApiRequestContract $apiRequest
     */
    public function __construct(ApiRequestContract $apiRequest)
    {
        $this->apiRequest = $apiRequest;
    }

    /**
     * @return \StarCitizenWiki\MediaWikiApi\Api\Response\MediaWikiResponse
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getResponse(): MediaWikiResponse
    {
        $client = $this->makeClient();

        $response = $client->request(
            $this->apiRequest->requestMethod(),
            $this->makeRequestUrl(),
            $this->getRequestOptions()
        );

        return MediaWikiResponse::fromGuzzleResponse($response);
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
            return sprintf('%s?%s', $url, http_build_query($this->apiRequest->queryParams()));
        }

        return (string) $url;
    }

    /**
     * @return \GuzzleHttp\Client
     */
    private function makeClient(): Client
    {
        $mediaWikiRequest = $this->makeMediawikiRequestObject();

        try {
            $header = $mediaWikiRequest->toHeader();
        } catch (Exception $e) {
            $header = 'Authorization: OAuth';
        }
        $header = explode(':', $header);

        return new Client(
            [
                'headers' => [
                    $header[0] => $header[1],
                ],
            ]
        );
    }

    /**
     * @return \MediaWiki\OAuthClient\Request
     */
    private function makeMediawikiRequestObject(): Request
    {
        if ($this->apiRequest->needsAuthentication()) {
            $manager = app(ApiManager::class);
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
        } else {
            $mediaWikiRequest = Request::fromRequest(
                $this->apiRequest->requestMethod(),
                config(self::MEDIAWIKI_API_URL),
                $this->apiRequest->queryParams()
            );
        }

        return $mediaWikiRequest;
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
