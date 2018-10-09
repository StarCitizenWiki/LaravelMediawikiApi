<?php declare(strict_types = 1);

namespace StarCitizenWiki\MediaWikiApi\Api\Request;

use StarCitizenWiki\MediaWikiApi\Contracts\ApiRequestContract;

/**
 * User: Hannes
 * Date: 05.10.2018
 * Time: 18:31
 */
class Query extends AbstractBaseRequest implements ApiRequestContract
{
    /**
     * Query constructor.
     */
    public function __construct()
    {
        $this->params['action'] = 'query';
    }

    /**
     * Set a Meta Property
     *
     * @param string $meta
     *
     * @return $this
     */
    public function meta(string $meta)
    {
        $this->setParam('meta', $meta);

        return $this;
    }

    /**
     * Set a 'Prop' Property
     *
     * @param string $prop
     *
     * @return $this
     */
    public function prop(string $prop)
    {
        $this->setParam('prop', $prop);

        return $this;
    }

    /**
     * The Pages to work on
     *
     * @param string $titles
     *
     * @return $this
     */
    public function titles(string $titles)
    {
        $this->setParam('titles', $titles);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function requestMethod(): string
    {
        return 'GET';
    }

    /**
     * {@inheritdoc}
     */
    public function needsAuthentication(): bool
    {
        if (isset($this->params['meta']) && str_contains($this->params['meta'], 'tokens')) {
            return true;
        }

        return false;
    }
}