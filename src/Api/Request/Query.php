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
    private $auth = false;

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
     * Set the Category Limit
     *
     * @param int $limit
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function cllimit(int $limit)
    {
        if (-1 === $limit || $limit > 5000) {
            $limit = 'max';
        } elseif ($limit < 0) {
            throw new \InvalidArgumentException('Limit has to be greater than 0');
        }

        if (isset($this->params['prop']) && str_contains($this->params['prop'], 'categories')) {
            $this->params['cllimit'] = $limit;
        }

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
        if ($this->auth || isset($this->params['meta']) && str_contains($this->params['meta'], 'tokens')) {
            return true;
        }

        return false;
    }

    /**
     * Force Authentication
     *
     * @return $this
     */
    public function withAuthentication()
    {
        $this->auth = true;

        return $this;
    }
}
