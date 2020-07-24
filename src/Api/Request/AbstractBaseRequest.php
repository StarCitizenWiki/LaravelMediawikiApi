<?php declare(strict_types=1);

namespace StarCitizenWiki\MediaWikiApi\Api\Request;

use StarCitizenWiki\MediaWikiApi\Api\MediaWikiRequestFactory;
use StarCitizenWiki\MediaWikiApi\Api\Response\MediaWikiResponse;

/**
 * Base API Request
 */
abstract class AbstractBaseRequest
{
    protected $params = [
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
     * Set format zo json
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
     * @param array|null $requestConfig
     *
     * @return MediaWikiResponse
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
