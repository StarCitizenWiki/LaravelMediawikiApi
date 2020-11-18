<?php

declare(strict_types=1);

namespace StarCitizenWiki\MediaWikiApi\Tests\Api\Request;

use PHPUnit\Framework\TestCase;
use StarCitizenWiki\MediaWikiApi\Api\Request\Query;

class QueryTest extends TestCase
{
    /**
     * Test that the action param equals query
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::__construct
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::queryParams
     */
    public function testActionMatches(): void
    {
        $query = new Query();

        self::assertArrayHasKey('action', $query->queryParams());
        self::assertEquals('query', $query->queryParams()['action']);
    }

    /**
     * Test that the query method equals GET
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::requestMethod()
     */
    public function testMethodMatches(): void
    {
        $query = new Query();

        self::assertEquals('GET', $query->requestMethod());
    }

    /**
     * Test default needsAuthentication false
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::needsAuthentication()
     */
    public function testDefaultNoAuth(): void
    {
        $query = new Query();

        self::assertEquals(false, $query->needsAuthentication());
    }

    /**
     * Test needsAuthentication true
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::needsAuthentication()
     */
    public function testManualAuth(): void
    {
        $query = new Query();
        $query->withAuthentication();

        self::assertEquals(true, $query->needsAuthentication());
    }

    /**
     * Test needsAuthentication true through token meta
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::meta()
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::needsAuthentication()
     */
    public function testMetaAuth(): void
    {
        $query = new Query();
        $query->meta('tokens');

        self::assertEquals(true, $query->needsAuthentication());
    }

    /**
     * Test setting a single meta value
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::meta
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::setParam
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::queryParams
     */
    public function testSingleMeta(): void
    {
        $meta = 'Example';

        $query = new Query();
        $query->meta($meta);

        self::assertArrayHasKey('meta', $query->queryParams());
        self::assertEquals($meta, $query->queryParams()['meta']);
    }

    /**
     * Test setting a single prop value
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::prop
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::setParam
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::queryParams
     */
    public function testPropMeta(): void
    {
        $prop = 'Example';

        $query = new Query();
        $query->prop($prop);

        self::assertArrayHasKey('prop', $query->queryParams());
        self::assertEquals($prop, $query->queryParams()['prop']);
    }

    /**
     * Test that a singular title is set
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::titles
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::setParam
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::queryParams
     */
    public function testSingleTitle(): void
    {
        $title = 'Example';

        $query = new Query();
        $query->titles($title);

        self::assertArrayHasKey('titles', $query->queryParams());
        self::assertEquals($title, $query->queryParams()['titles']);
    }

    /**
     * Test setting multiple title inline
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::titles
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::setParam
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::queryParams
     */
    public function testMultipleTitlesInline(): void
    {
        $title = 'Example|Example2';

        $query = new Query();
        $query->titles($title);

        self::assertArrayHasKey('titles', $query->queryParams());
        self::assertEquals($title, $query->queryParams()['titles']);
    }

    /**
     * Test setting multiple title
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::titles
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::setParam
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::queryParams
     */
    public function testMultipleTitles(): void
    {
        $titles = [
            'Example',
            'Example2',
            'Example3',
        ];

        $query = new Query();

        foreach ($titles as $title) {
            $query->titles($title);
        }

        self::assertArrayHasKey('titles', $query->queryParams());
        self::assertEquals(implode('|', $titles), $query->queryParams()['titles']);
    }

    /**
     * Test that a singular category limit is set
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::cllimit
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::setParam
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::queryParams
     */
    public function testClimit100(): void
    {
        $limit = 100;

        $query = new Query();
        $query->prop('categories');
        $query->cllimit($limit);

        self::assertArrayHasKey('cllimit', $query->queryParams());
        self::assertEquals($limit, $query->queryParams()['cllimit']);
    }

    /**
     * Test that a singular category limit is set
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::cllimit
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::setParam
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::queryParams
     */
    public function testClimitNoCategoryProp(): void
    {
        $limit = 100;

        $query = new Query();
        $query->prop('categories')->cllimit($limit);

        self::assertArrayHasKey('cllimit', $query->queryParams());
    }

    /**
     * Test that a singular category limit is set
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::cllimit
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::setParam
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::queryParams
     */
    public function testClimitException(): void
    {
        $limit = -10;

        $query = new Query();

        $this->expectException('InvalidArgumentException');

        $query->cllimit($limit);
    }

    /**
     * Test that a singular category limit is set
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::cllimit
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::setParam
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::queryParams
     */
    public function testClimitMaxNegOne(): void
    {
        $limit = -1;

        $query = new Query();
        $query->prop('categories')->cllimit($limit);

        self::assertArrayHasKey('cllimit', $query->queryParams());
        self::assertEquals('max', $query->queryParams()['cllimit']);
    }

    /**
     * Test that a singular category limit is set
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::cllimit
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::setParam
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Query::queryParams
     */
    public function testClimitMaxGt5000(): void
    {
        $limit = 5001;

        $query = new Query();
        $query->prop('categories')->cllimit($limit);

        self::assertArrayHasKey('cllimit', $query->queryParams());
        self::assertEquals('max', $query->queryParams()['cllimit']);
    }
}
