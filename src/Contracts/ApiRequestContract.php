<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 06.10.2018
 * Time: 17:02
 */

namespace StarCitizenWiki\MediaWikiApi\Contracts;

use GuzzleHttp\Psr7\Response;

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
     * @return \GuzzleHttp\Psr7\Response
     */
    public function request(): Response;
}
