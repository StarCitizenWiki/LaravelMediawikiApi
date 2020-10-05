<?php

declare(strict_types=1);

namespace StarCitizenWiki\MediaWikiApi\Api;

use Illuminate\Foundation\Application;
use InvalidArgumentException;
use StarCitizenWiki\MediaWikiApi\Api\Request\Edit;
use StarCitizenWiki\MediaWikiApi\Api\Request\Parse;
use StarCitizenWiki\MediaWikiApi\Api\Request\Query;

/**
 * Main Entry point for MediaWiki Requests
 */
class MediaWikiApi
{
    /**
     * Make an Edit Request
     *
     * @param null|string $title
     *
     * @return Edit
     */
    public function edit(?string $title = null): Edit
    {
        /** @var Edit $editObject */
        $editObject = $this->make(Edit::class);

        if (null !== $title) {
            $editObject->title($title);
        }

        return $editObject;
    }

    /**
     * @param string $type
     *
     * @return Application|mixed
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

            case 'parse':
            case Parse::class:
                $action = app(Parse::class);
                break;

            default:
                throw new InvalidArgumentException('Invalid Action type passed');
        }

        return $action;
    }

    /**
     * Make a Query Request
     *
     * @return Query
     */
    public function query(): Query
    {
        return $this->make(Query::class);
    }

    /**
     * Make a Parse Request
     *
     * @return Parse
     */
    public function parse(): Parse
    {
        return $this->make(Parse::class);
    }
}
