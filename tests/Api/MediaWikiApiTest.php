<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class MediaWikiApiTest extends TestCase
{
    /**
     * @covers \StarCitizenWiki\MediaWikiApi\Api\MediaWikiApi::edit
     */
    public function testEdit(): void
    {
        $page = 'Example';

        $api = new StarCitizenWiki\MediaWikiApi\Api\MediaWikiApi();
        $editObj = $api->edit($page);

        self::assertArrayHasKey('title', $editObj->queryParams());
        self::assertEquals($page, $editObj->queryParams()['title']);
    }
}
