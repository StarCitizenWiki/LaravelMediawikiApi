<?php

declare(strict_types=1);

namespace StarCitizenWiki\MediaWikiApi\Exceptions;

use Exception;
use StarCitizenWiki\MediaWikiApi\Api\Response\MediaWikiResponse;

/**
 * Api Error occurred
 */
class ApiErrorException extends Exception
{
    /**
     * @var MediaWikiResponse|null
     */
    private ?MediaWikiResponse $response;

    /**
     * ApiErrorException constructor.
     *
     * @param MediaWikiResponse|null $response
     */
    public function __construct(MediaWikiResponse $response = null)
    {
        parent::__construct();

        $this->response = $response;
    }

    /**
     * Get the Guzzle Response
     *
     * @return MediaWikiResponse
     */
    public function getResponse(): MediaWikiResponse
    {
        return $this->response;
    }
}
