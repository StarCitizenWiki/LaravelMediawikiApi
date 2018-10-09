<?php declare(strict_types = 1);

namespace StarCitizenWiki\MediaWikiApi\Api\Request;

use StarCitizenWiki\MediaWikiApi\Api\MediaWikiApi;
use StarCitizenWiki\MediaWikiApi\Api\Response\MediaWikiResponse;
use StarCitizenWiki\MediaWikiApi\Contracts\ApiRequestContract;
use StarCitizenWiki\MediaWikiApi\Exceptions\ApiErrorException;

/**
 * User: Hannes
 * Date: 05.10.2018
 * Time: 18:30
 */
class Edit extends AbstractBaseRequest implements ApiRequestContract
{
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
    public function title(string $title)
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
    public function pageId(int $id)
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
    public function text(string $text)
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
    public function section(?int $id = null)
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
    public function sectionTitle(string $title)
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
    public function summary(string $summary)
    {
        $this->params['summary'] = $summary;

        return $this;
    }

    /**
     * Only create Pages, don't edit them
     *
     * @return $this
     */
    public function createOnly()
    {
        $this->params['createonly'] = true;

        return $this;
    }

    /**
     * Mark Edit as Minor
     *
     * @return $this
     */
    public function minor()
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
    public function notMinor()
    {
        unset($this->params['minor']);
        $this->params['notminor'] = true;

        return $this;
    }

    /**
     * @return \StarCitizenWiki\MediaWikiApi\Api\Response\MediaWikiResponse
     *
     * @throws \StarCitizenWiki\MediaWikiApi\Exceptions\ApiErrorException
     */
    public function request(): MediaWikiResponse
    {
        $response = app(MediaWikiApi::class)->query()->withTimestamp()->meta('tokens')->request();

        if (!$response->successful()) {
            throw new ApiErrorException($response);
        }

        $body = $response->getBody();

        $this->params['starttimestamp'] = $body['curtimestamp'];
        $this->params['token'] = $body['query']['tokens']['csrftoken'];

        return parent::request();
    }
}
