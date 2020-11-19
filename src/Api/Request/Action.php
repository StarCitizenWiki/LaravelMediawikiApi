<?php

declare(strict_types=1);

namespace StarCitizenWiki\MediaWikiApi\Api\Request;

use StarCitizenWiki\MediaWikiApi\Contracts\ApiRequestContract;

/**
 * A wrapper for generic actions
 */
class Action extends AbstractBaseRequest implements ApiRequestContract
{
    /**
     * @var string GET|POST
     */
    private string $requestMethod;

    /**
     * BaseAction constructor.
     *
     * @param string $action The API action
     * @param string $method The request method required by the action GET or POST
     * @param bool   $needsAuth True if the action requires authentication
     */
    public function __construct(string $action, string $method = 'GET', bool $needsAuth = false)
    {
        $this->params['action'] = $action;
        $this->requestMethod = $method;
        $this->auth = $needsAuth;
    }

    /**
     * @inheritDoc
     * @return string
     */
    public function requestMethod(): string
    {
        return $this->requestMethod;
    }
}