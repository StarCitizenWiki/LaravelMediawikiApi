<?php declare(strict_types=1);

namespace StarCitizenWiki\MediaWikiApi\Api\Request;

use InvalidArgumentException;
use StarCitizenWiki\MediaWikiApi\Contracts\ApiRequestContract;

/**
 * Make action=query requests
 */
class Query extends AbstractBaseRequest implements ApiRequestContract
{
    private $auth = false;

    /**
     * Query constructor.
     *
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
    public function meta(string $meta): self
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
    public function prop(string $prop): self
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
    public function titles(string $titles): self
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
     * @throws InvalidArgumentException
     */
    public function cllimit(int $limit): self
    {
        $queryLimit = $limit;

        if (-1 === $limit || $limit > 5000) {
            $queryLimit = 'max';
        } elseif ($limit < 0) {
            throw new InvalidArgumentException('Limit has to be greater than 0');
        }

        if (isset($this->params['prop']) && strpos($this->params['prop'], 'categories') !== false) {
            $this->params['cllimit'] = $queryLimit;
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
        return $this->auth || (isset($this->params['meta']) && strpos($this->params['meta'], 'tokens') !== false);
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
}
