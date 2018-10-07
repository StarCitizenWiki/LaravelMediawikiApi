<?php declare(strict_types = 1);

namespace StarCitizenWiki\MediawikiApi\Api\Request;

use StarCitizenWiki\MediawikiApi\Contracts\ApiRequestContract;

/**
 * User: Hannes
 * Date: 05.10.2018
 * Time: 18:31
 */
class Query extends AbstractBaseRequest implements ApiRequestContract
{
    const MEDIAWIKI_CSRF_TOKEN = 'mediawiki.csrf_token';

    public function __construct()
    {
        $this->params['action'] = 'query';
    }

    public function meta(string $meta)
    {
        $this->setParam('meta', $meta);

        return $this;
    }

    public function prop(string $prop)
    {
        $this->setParam('prop', $prop);

        return $this;
    }

    public function titles(string $titles)
    {
        $this->setParam('titles', $titles);

        return $this;
    }


    public function requestMethod(): string
    {
        return 'GET';
    }

    public function needsAuthentication(): bool
    {
        if (isset($this->params['meta']) && str_contains($this->params['meta'], 'tokens')) {
            return true;
        }

        return false;
    }
}
