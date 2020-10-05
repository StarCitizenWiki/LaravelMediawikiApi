<?php

declare(strict_types=1);

namespace StarCitizenWiki\MediaWikiApi\Api\Request;

use RuntimeException;
use StarCitizenWiki\MediaWikiApi\Api\Response\MediaWikiResponse;
use StarCitizenWiki\MediaWikiApi\Contracts\ApiRequestContract;

/**
 * Make action=edit requests
 */
class Edit extends AbstractBaseRequest implements ApiRequestContract
{
    /**
     * @var string The csrf token for the edit request
     */
    private string $csrfToken;

    /**
     * Edit constructor.
     */
    public function __construct()
    {
        $this->params['action'] = 'edit';
    }

    /**
     * {@inheritdoc}
     */
    public function requestMethod(): string
    {
        return 'POST';
    }

    /**
     * {@inheritdoc}
     */
    public function needsAuthentication(): bool
    {
        return true;
    }

    /**
     * The Page title to Edit
     *
     * @param string $title
     *
     * @return $this
     */
    public function title(string $title): self
    {
        unset($this->params['pageid']);
        $this->params['title'] = $title;

        return $this;
    }

    /**
     * The Page ID to Edit
     *
     * @param int $id
     *
     * @return $this
     */
    public function pageId(int $id): self
    {
        unset($this->params['title']);
        $this->params['pageid'] = $id;

        return $this;
    }

    /**
     * Set the Page Content
     *
     * @param string $text
     *
     * @return $this
     */
    public function text(string $text): self
    {
        $this->params['text'] = $text;
        $this->params['md5'] = md5($this->params['text']);

        return $this;
    }

    /**
     * Create a new Section or edit an existing one
     *
     * @param int|null $id
     *
     * @return $this
     */
    public function section(?int $id = null): self
    {
        $this->params['section'] = $id;
        if (null === $id) {
            $this->params['section'] = 'new';
        }

        return $this;
    }

    /**
     * The Section Title for a new Section
     *
     * @param string $title
     *
     * @return $this
     */
    public function sectionTitle(string $title): self
    {
        $this->params['sectiontitle'] = $title;

        return $this;
    }

    /**
     * Set an Edit Summary
     *
     * @param string $summary
     *
     * @return $this
     */
    public function summary(string $summary): self
    {
        $this->params['summary'] = $summary;

        return $this;
    }

    /**
     * Only create Pages, don't edit them
     *
     * @return $this
     */
    public function createOnly(): self
    {
        $this->params['createonly'] = true;

        return $this;
    }

    /**
     * Mark Edit as Minor
     *
     * @return $this
     */
    public function minor(): self
    {
        unset($this->params['notminor']);
        $this->params['minor'] = true;

        return $this;
    }

    /**
     * Mark Edit as not Minor
     *
     * @return $this
     */
    public function notMinor(): self
    {
        unset($this->params['minor']);
        $this->params['notminor'] = true;

        return $this;
    }

    /**
     * Mark Edit as Bot
     *
     * @param bool $flag
     *
     * @return $this
     */
    public function markBotEdit(bool $flag = true): self
    {
        $this->params['bot'] = $flag;

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
     * @param array $requestConfig
     *
     * @return MediaWikiResponse
     *
     * @throws RuntimeException If the csrf token is null
     */
    public function request(array $requestConfig = []): MediaWikiResponse
    {
        if (null === $this->csrfToken) {
            throw new RuntimeException('Missing CSRF Token');
        }

        $this->params['token'] = $this->csrfToken;

        return parent::request($requestConfig);
    }
}
