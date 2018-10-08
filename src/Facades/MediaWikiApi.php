<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: Hanne
 * Date: 08.10.2018
 * Time: 12:43
 */

namespace StarCitizenWiki\MediaWikiApi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Facade Access
 */
class MediaWikiApi extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'mediawikiapi';
    }
}
