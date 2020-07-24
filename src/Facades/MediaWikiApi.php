<?php declare(strict_types=1);

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
