<?php

declare(strict_types=1);

namespace StarCitizenWiki\MediaWikiApi\Tests\Api;

use PHPUnit\Framework\TestCase;
use StarCitizenWiki\MediaWikiApi\Api\MediaWikiApi;

class MediaWikiApiTest extends TestCase
{
    /**
     * @covers \StarCitizenWiki\MediaWikiApi\Api\MediaWikiApi::edit
     */
    public function testEdit(): void
    {
        $page = 'Example';

        $api = new MediaWikiApi();
        $editObj = $api->edit($page);

        self::assertArrayHasKey('title', $editObj->queryParams());
        self::assertEquals($page, $editObj->queryParams()['title']);
    }
}
