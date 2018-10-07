<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 05.10.2018
 * Time: 18:29
 */

namespace StarCitizenWiki\MediawikiApi\Api;

use InvalidArgumentException;
use StarCitizenWiki\MediawikiApi\Api\Request\Edit;
use StarCitizenWiki\MediawikiApi\Api\Request\Query;

class ApiRequestFactory
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
}
