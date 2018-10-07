<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 06.10.2018
 * Time: 17:38
 */

namespace StarCitizenWiki\MediawikiApi\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use MediaWiki\OAuthClient\Request;
use MediaWiki\OAuthClient\SignatureMethod\HmacSha1;
use StarCitizenWiki\MediawikiApi\Contracts\ApiRequestContract;
use StarCitizenWiki\MediawikiApi\Exceptions\ApiErrorException;

class MediawikiRequestFactory
{
    const MEDIAWIKI_API_URL = 'mediawiki.api_url';
    const MEDIA_WIKI_API_ERROR = 'MediaWiki-API-Error';
    /**
     * @var \StarCitizenWiki\MediawikiApi\Contracts\ApiRequestContract
     */
    private $apiRequest;

    public function __construct(ApiRequestContract $apiRequest)
    {
        $this->apiRequest = $apiRequest;
    }

    /**
     * @return \GuzzleHttp\Psr7\Response
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \MediaWiki\OAuthClient\Exception
     * @throws \StarCitizenWiki\MediawikiApi\Exceptions\ApiErrorException
     */
    public function getResponse(): Response
    {
        $client = $this->makeClient();

        if ($this->apiRequest->requestMethod() === 'POST') {
            $response = $client->request(
                $this->apiRequest->requestMethod(),
                config(self::MEDIAWIKI_API_URL),
                $this->getRequestOptions()
            );
        } else {
            $response = $client->request(
                $this->apiRequest->requestMethod(),
                sprintf('%s?%s', config(self::MEDIAWIKI_API_URL), http_build_query($this->apiRequest->queryParams())),
                $this->getRequestOptions()
            );
        }

        if ($response->hasHeader(self::MEDIA_WIKI_API_ERROR)) {
            $errors = implode(', ', $response->getHeader(self::MEDIA_WIKI_API_ERROR));

            throw new ApiErrorException("Api Error: {$errors}");
        }

        return $response;
    }

    /**
     * @return \GuzzleHttp\Client
     *
     * @throws \MediaWiki\OAuthClient\Exception
     */
    private function makeClient(): Client
    {
        $mediaWikiRequest = $this->makeMediawikiRequestObject();

        $header = $mediaWikiRequest->toHeader();
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
