<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 07.10.2018
 * Time: 16:46
 */

namespace StarCitizenWiki\MediaWikiApi\Exceptions;

use Exception;
use StarCitizenWiki\MediaWikiApi\Api\Response\MediaWikiResponse;

/**
 * Api Error occurred
 */
class ApiErrorException extends Exception
{
    /**
     * @var \StarCitizenWiki\MediaWikiApi\Api\Response\MediaWikiResponse
     */
    private $response;

    /**
     * ApiErrorException constructor.
     *
     * @param \StarCitizenWiki\MediaWikiApi\Api\Response\MediaWikiResponse $response
     */
    public function __construct(MediaWikiResponse $response = null)
    {
        $this->response = $response;
    }

    /**
     * Get the Guzzle Response
     *
     * @return \StarCitizenWiki\MediaWikiApi\Api\Response\MediaWikiResponse
     */
    public function getResponse(): MediaWikiResponse
    {
        return $this->response;
    }
}
