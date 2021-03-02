<?php

declare(strict_types=1);

namespace StarCitizenWiki\MediaWikiApi\Api\Request;

use GuzzleHttp\Exception\GuzzleException;
use RuntimeException;
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

    /**
     * @var string|null The csrf token for the edit request
     */
    protected ?string $csrfToken = null;

    /**
     * The request params
     *
     * @var array|string[]
     */
    protected array $params = [
        'format' => 'json',
    ];

    /**
     * @param $name
     * @param $arguments
     *
     * @return $this
     */
    public function __call(string $name, $arguments): self
    {
        $this->setParam($name, (string)($arguments[0] ?? ''));

        return $this;
    }

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
     * Set formatversion
     * Valid versions are '1', '2', 'latest'
     *
     * @param string|int $version
     *
     * @return $this
     */
    public function formatVersion($version): self
    {
        $validFormats = ['1', '2', 'latest'];

        if (in_array((string)$version, $validFormats)) {
            $this->params['formatversion'] = (string)$version;
        }

        return $this;
    }

    /**
     * Set the CSRF Token
     *
     * @param string $token
     *
     * @return $this
     */
    public function csrfToken(string $token): self
    {
        $this->csrfToken = $token;

        return $this;
    }

    /**
     * If the Request needs an csrf token
     *
     * @return bool
     */
    public function needsCsrfToken(): bool
    {
        return $this->csrfToken !== null;
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
     *
     * @throws RuntimeException If the csrf token is null
     */
    public function request(array $requestConfig = []): MediaWikiResponse
    {
        if (null === $this->csrfToken && $this->needsCsrfToken()) {
            throw new RuntimeException('Missing CSRF Token');
        }

        if (null !== $this->csrfToken) {
            $this->params['token'] = $this->csrfToken;
        }

        /** @var MediaWikiRequestFactory $factory */
        $factory = app()->makeWith(MediaWikiRequestFactory::class, ['apiRequest' => $this]);

        return $factory->getResponse($requestConfig);
    }

    /**
     * Add a parameter to the request
     *
     * @param string $name Param name
     * @param mixed $value Param value
     *
     * @return $this
     */
    public function addParam(string $name, $value): self
    {
        $this->setParam($name, (string)$value);

        return $this;
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
