<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use StarCitizenWiki\MediaWikiApi\Api\Response\MediaWikiResponse;

class MediaWikiResponseTest extends TestCase
{
    /**
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Response\MediaWikiResponse::__construct
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Response\MediaWikiResponse::checkResponse
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Response\MediaWikiResponse::setBody
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Response\MediaWikiResponse::hasErrors
     */
    public function testSuccessful(): void
    {
        $response = new MediaWikiResponse(
            '{}',
            200,
            [
                'Content-Type' => [
                    'application/json',
                ],
            ]
        );

        self::assertEquals(false, $response->hasErrors());
    }

    /**
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Response\MediaWikiResponse::__construct
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Response\MediaWikiResponse::checkResponse
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Response\MediaWikiResponse::setBody
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Response\MediaWikiResponse::hasErrors
     */
    public function testMissingHeader(): void
    {
        $response = new MediaWikiResponse(
            '{}',
            200,
            []
        );

        self::assertEquals(true, $response->hasErrors());
    }
}
