<?php

declare(strict_types=1);

namespace StarCitizenWiki\MediaWikiApi\Api;

use Illuminate\Foundation\Application;
use InvalidArgumentException;
use StarCitizenWiki\MediaWikiApi\Api\Request\Action;
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
     * @param array  $params Optional constructor arguments where applicable
     *
     * @return Application|mixed
     */
    public function make(string $type, array $params = [])
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

            case 'action':
            case Action::class:
                $action = app()->makeWith(Action::class, $params);
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

    /**
     * Makes a Action Request
     *
     * @param string $action    The API action
     * @param string $method    The request method required by the action GET or POST
     * @param bool   $needsAuth True if the action requires authentication
     *
     * @return Action
     */
    public function action(string $action, string $method = 'GET', bool $needsAuth = false): Action
    {
        return app()->makeWith(Action::class, [
            'action' => $action,
            'method' => $method,
            'needsAuth' => $needsAuth,
        ]);
    }
}
