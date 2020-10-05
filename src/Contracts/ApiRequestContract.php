<?php

declare(strict_types=1);

namespace StarCitizenWiki\MediaWikiApi\Contracts;

use StarCitizenWiki\MediaWikiApi\Api\Response\MediaWikiResponse;

/**
 * Interface ApiRequestContract
 */
interface ApiRequestContract
{
    /**
     * Return all query Params
     *
     * @return array
     */
    public function queryParams(): array;

    /**
     * The Request Method for this Query
     *
     * @return string
     */
    public function requestMethod(): string;

    /**
     * If the Request needs to be signed
     *
     * @return bool
     */
    public function needsAuthentication(): bool;

    /**
     * Send the Request
     *
     * @param array $requestConfig Optional Guzzle client config
     *
     * @return MediaWikiResponse
     */
    public function request(array $requestConfig = []): MediaWikiResponse;
}
