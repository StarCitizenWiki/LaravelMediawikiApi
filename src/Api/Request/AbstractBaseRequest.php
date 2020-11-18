<?php

declare(strict_types=1);

namespace StarCitizenWiki\MediaWikiApi\Api\Request;

use GuzzleHttp\Exception\GuzzleException;
use StarCitizenWiki\MediaWikiApi\Api\MediaWikiRequestFactory;
use StarCitizenWiki\MediaWikiApi\Api\Response\MediaWikiResponse;

/**
 * Base API Request
 */
abstract class AbstractBaseRequest
{
    /**
     * @var bool
     */
    protected bool $auth = false;

    protected array $params = [
        'format' => 'json',
    ];

    /**
     * The query params for this request
     *
     * @return array
     */
    public function queryParams(): array
    {
        return $this->params;
    }

    /**
     * Set format to json
     *
     * @return $this
     */
    public function json(): self
    {
        $this->params['format'] = 'json';

        return $this;
    }

    /**
     * Include current Timestamp
     *
     * @return $this
     */
    public function withTimestamp(): self
    {
        $this->params['curtimestamp'] = 1;

        return $this;
    }

    /**
     * Force Authentication
     *
     * @return $this
     */
    public function withAuthentication(): self
    {
        $this->auth = true;

        return $this;
    }

    /**
     * True if the action requires authentication
     *
     * @return bool
     */
    public function needsAuthentication(): bool
    {
        return $this->auth;
    }

    /**
     * @param array|null $requestConfig
     *
     * @return MediaWikiResponse
     *
     * @throws GuzzleException
     */
    public function request(array $requestConfig = []): MediaWikiResponse
    {
        /** @var MediaWikiRequestFactory $factory */
        $factory = app()->makeWith(MediaWikiRequestFactory::class, ['apiRequest' => $this]);

        return $factory->getResponse($requestConfig);
    }

    /**
     * Set or append a Api Query Parameter
     *
     * @param string $key
     * @param string $value
     */
    protected function setParam(string $key, string $value): void
    {
        if (isset($this->params[$key])) {
            $this->params[$key] .= "|{$value}";
        } else {
            $this->params[$key] = $value;
        }
    }
}
