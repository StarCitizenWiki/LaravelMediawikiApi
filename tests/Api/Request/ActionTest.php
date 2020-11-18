<?php

declare(strict_types=1);

namespace StarCitizenWiki\MediaWikiApi\Tests\Api\Request;

use PHPUnit\Framework\TestCase;
use StarCitizenWiki\MediaWikiApi\Api\Request\Action;

class ActionTest extends TestCase
{
    /**
     * Test that the action param equals query
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Action::__construct
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Action::queryParams
     */
    public function testActionMatches(): void
    {
        $action = new Action('foo');

        self::assertArrayHasKey('action', $action->queryParams());
        self::assertEquals('foo', $action->queryParams()['action']);
    }

    /**
     * Test that the query method equals GET as default
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Action::requestMethod()
     */
    public function testMethodMatches(): void
    {
        $action = new Action('foo');

        self::assertEquals('GET', $action->requestMethod());
    }

    /**
     * Test default needsAuthentication false
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Action::needsAuthentication()
     */
    public function testDefaultNoAuth(): void
    {
        $action = new Action('foo');

        self::assertEquals(false, $action->needsAuthentication());
    }

    /**
     * Test needsAuthentication true
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Action::needsAuthentication()
     */
    public function testManualAuth(): void
    {
        $action = new Action('foo');
        $action->withAuthentication();

        self::assertEquals(true, $action->needsAuthentication());
    }

    /**
     * Test needsAuthentication true
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Action::__construct
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Action::needsAuthentication()
     */
    public function testManualAuthConstructor(): void
    {
        $action = new Action('foo', 'GET', true);

        self::assertEquals(true, $action->needsAuthentication());
    }

    /**
     * Test addParam
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Action::addParam()
     */
    public function testAddParam(): void
    {
        $action = new Action('foo');
        $action->addParam('test', 'value');

        self::assertArrayHasKey('test', $action->queryParams());
        self::assertEquals('value', $action->queryParams()['test']);
    }

    /**
     * Test addParam multiple
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Action::addParam()
     */
    public function testAddParamMultiple(): void
    {
        $action = new Action('foo');
        $action->addParam('test', 'value')->addParam('test2', 'value2');

        self::assertArrayHasKey('test', $action->queryParams());
        self::assertArrayHasKey('test2', $action->queryParams());
        self::assertEquals('value', $action->queryParams()['test']);
        self::assertEquals('value2', $action->queryParams()['test2']);
    }
}
