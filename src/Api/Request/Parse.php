<?php

declare(strict_types=1);

namespace StarCitizenWiki\MediaWikiApi\Api\Request;

use StarCitizenWiki\MediaWikiApi\Contracts\ApiRequestContract;

/**
 * Make action=parse requests
 */
class Parse extends AbstractBaseRequest implements ApiRequestContract
{
    /**
     * Query constructor.
     */
    public function __construct()
    {
        $this->params['action'] = 'parse';
    }

    /**
     * The Page to parse
     *
     * @param string $page
     *
     * @return $this
     */
    public function page(string $page): self
    {
        $this->params['page'] = $page;

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
        return false;
    }
}
