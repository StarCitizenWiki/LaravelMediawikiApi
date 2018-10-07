<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 06.10.2018
 * Time: 20:18
 */

namespace StarCitizenWiki\MediawikiApi\Api\Request;

use GuzzleHttp\Psr7\Response;
use StarCitizenWiki\MediawikiApi\Api\MediawikiRequestFactory;
use StarCitizenWiki\MediawikiApi\Contracts\ApiRequestContract;

abstract class AbstractBaseRequest implements ApiRequestContract
{
    protected $params = [
        'format' => 'json',
    ];

    public function queryParams(): array
    {
        return $this->params;
    }

    public function json()
    {
        $this->params['format'] = 'json';

        return $this;
    }

    public function withTimestamp()
    {
        $this->params['curtimestamp'] = 1;

        return $this;
    }

    /**
     * @return \GuzzleHttp\Psr7\Response
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \MediaWiki\OAuthClient\Exception
     * @throws \StarCitizenWiki\MediawikiApi\Exceptions\ApiErrorException
     */
    public function request(): Response
    {
        /** @var \StarCitizenWiki\MediawikiApi\Api\MediawikiRequestFactory $factory */
        $factory = app()->makeWith(MediawikiRequestFactory::class, ['apiRequest' => $this]);

        return $factory->getResponse();
    }

    protected function setParam(string $key, string $value)
    {
        if (isset($this->params[$key])) {
            $this->params[$key] .= "|{$value}";
        } else {
            $this->params[$key] = $value;
        }
    }
}
