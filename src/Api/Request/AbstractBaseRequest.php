<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 06.10.2018
 * Time: 20:18
 */

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
     * {@inheritdoc}
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
    public function json()
    {
        $this->params['format'] = 'json';

        return $this;
    }

    /**
     * Include current Timestamp
     *
     * @return $this
     */
    public function withTimestamp()
    {
        $this->params['curtimestamp'] = 1;

        return $this;
    }

    /**
     * @return \StarCitizenWiki\MediaWikiApi\Api\Response\MediaWikiResponse
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request(): MediaWikiResponse
    {
        /** @var \StarCitizenWiki\MediaWikiApi\Api\MediaWikiRequestFactory $factory */
        $factory = app()->makeWith(MediaWikiRequestFactory::class, ['apiRequest' => $this]);

        return $factory->getResponse();
    }

    /**
     * Set or append a Api Query Parameter
     *
     * @param string $key
     * @param string $value
     */
    protected function setParam(string $key, string $value)
    {
        if (isset($this->params[$key])) {
            $this->params[$key] .= "|{$value}";
        } else {
            $this->params[$key] = $value;
        }
    }
}
