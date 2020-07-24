<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use StarCitizenWiki\MediaWikiApi\Api\Request\Parse;

class ParseTest extends TestCase
{
    /**
     * Test that the action param equals query
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Parse::__construct
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Parse::queryParams
     */
    public function testActionMatches(): void
    {
        $parse = new Parse();

        self::assertArrayHasKey('action', $parse->queryParams());
        self::assertEquals('parse', $parse->queryParams()['action']);
    }

    /**
     * Test that the query method equals GET
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Parse::requestMethod
     */
    public function testMethodMatches(): void
    {
        $parse = new Parse();

        self::assertEquals('GET', $parse->requestMethod());
    }

    /**
     * Test default needsAuthentication true
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Parse::needsAuthentication()
     */
    public function testDefaultNoAuth(): void
    {
        $parse = new Parse();

        self::assertEquals(false, $parse->needsAuthentication());
    }

    /**
     * Test setting page
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Parse::page()
     */
    public function testPage(): void
    {
        $page = 'Example';

        $parse = new Parse();

        $parse->page($page);

        self::assertArrayHasKey('page', $parse->queryParams());
        self::assertEquals($page, $parse->queryParams()['page']);
    }

    /**
     * Test setting prop
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Parse::prop()
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Parse::setParam()
     */
    public function testProp(): void
    {
        $prop = 'prop';

        $parse = new Parse();

        $parse->prop($prop);

        self::assertArrayHasKey('prop', $parse->queryParams());
        self::assertEquals($prop, $parse->queryParams()['prop']);
    }
}
