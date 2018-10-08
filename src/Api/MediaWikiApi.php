<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 05.10.2018
 * Time: 18:29
 */

namespace StarCitizenWiki\MediaWikiApi\Api;

use InvalidArgumentException;
use StarCitizenWiki\MediaWikiApi\Api\Request\Edit;
use StarCitizenWiki\MediaWikiApi\Api\Request\Query;

/**
 * Main Entry point for MediaWiki Requests
 */
class MediaWikiApi
{
    /**
     * @param string $type
     *
     * @return \Illuminate\Foundation\Application|mixed
     */
    public function make(string $type)
    {
        switch ($type) {
            case 'edit':
            case Edit::class:
                $action = app(Edit::class);
                break;

            case 'query':
            case Query::class:
                $action = app(Query::class);
                break;

            default:
                throw new InvalidArgumentException('Invalid Action type passed');
        }

        return $action;
    }

    /**
     * Make an Edit Request
     *
     * @param null|string $title
     *
     * @return \StarCitizenWiki\MediaWikiApi\Api\Request\Edit
     */
    public function edit(?string $title = null): Edit
    {
        /** @var \StarCitizenWiki\MediaWikiApi\Api\Request\Edit $editObject */
        $editObject = $this->make(Edit::class);

        if (null !== $title) {
            $editObject->title($title);
        }

        return $editObject;
    }

    /**
     * Make a Query Request
     *
     * @return \StarCitizenWiki\MediaWikiApi\Api\Request\Query
     */
    public function query(): Query
    {
        return $this->make(Query::class);
    }
}
