<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use StarCitizenWiki\MediaWikiApi\Api\Request\Edit;

class EditTest extends TestCase
{
    /**
     * Test that the action param equals query
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::__construct
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::queryParams
     */
    public function testActionMatches(): void
    {
        $edit = new Edit();

        self::assertArrayHasKey('action', $edit->queryParams());
        self::assertEquals('edit', $edit->queryParams()['action']);
    }

    /**
     * Test that the query method equals GET
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::requestMethod
     */
    public function testMethodMatches(): void
    {
        $edit = new Edit();

        self::assertEquals('POST', $edit->requestMethod());
    }

    /**
     * Test default needsAuthentication true
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::needsAuthentication()
     */
    public function testDefaultNoAuth(): void
    {
        $edit = new Edit();

        self::assertEquals(true, $edit->needsAuthentication());
    }

    /**
     * Test that a singular title is set
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::title
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::queryParams
     */
    public function testSingleTitle(): void
    {
        $title = 'Example';

        $edit = new Edit();
        $edit->title($title);

        self::assertArrayHasKey('title', $edit->queryParams());
        self::assertArrayNotHasKey('pageid', $edit->queryParams());
        self::assertEquals($title, $edit->queryParams()['title']);
    }

    /**
     * Test that a singular pageid is set
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::pageId
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::queryParams
     */
    public function testSinglePageId(): void
    {
        $pageId = 1;

        $edit = new Edit();
        $edit->pageId($pageId);

        self::assertArrayHasKey('pageid', $edit->queryParams());
        self::assertArrayNotHasKey('title', $edit->queryParams());
        self::assertEquals($pageId, $edit->queryParams()['pageid']);
    }

    /**
     * Test that setting a title after a page id unsets the page id
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::pageId
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::title
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::queryParams
     */
    public function testTitleUnsetPageId(): void
    {
        $pageId = 10;

        $edit = new Edit();
        $edit->pageId($pageId)->title('Example');

        self::assertArrayHasKey('title', $edit->queryParams());
        self::assertArrayNotHasKey('pageid', $edit->queryParams());
        self::assertEquals('Example', $edit->queryParams()['title']);
    }

    /**
     * Test that setting a pageId after a title unsets the title
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::pageId
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::title
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::queryParams
     */
    public function testPageIdUnsetTitle(): void
    {
        $pageId = 10;

        $edit = new Edit();
        $edit->title('Example')->pageId($pageId);

        self::assertArrayHasKey('pageid', $edit->queryParams());
        self::assertArrayNotHasKey('title', $edit->queryParams());
        self::assertEquals($pageId, $edit->queryParams()['pageid']);
    }

    /**
     * Test setting text and auto md5
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::text
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::queryParams
     */
    public function testText(): void
    {
        $text = 'Lorem Ipsum';
        $textMd5 = md5($text);

        $edit = new Edit();
        $edit->text($text);

        self::assertArrayHasKey('text', $edit->queryParams());
        self::assertArrayHasKey('md5', $edit->queryParams());
        self::assertEquals($text, $edit->queryParams()['text']);
        self::assertEquals($textMd5, $edit->queryParams()['md5']);
    }

    /**
     * Test setting a section id
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::section
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::queryParams
     */
    public function testSection(): void
    {
        $section = 1;

        $edit = new Edit();
        $edit->section($section);

        self::assertArrayHasKey('section', $edit->queryParams());
        self::assertEquals($section, $edit->queryParams()['section']);
    }

    /**
     * Test setting a section without an id
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::section
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::queryParams
     */
    public function testSectionNullId(): void
    {
        $edit = new Edit();
        $edit->section();

        self::assertArrayHasKey('section', $edit->queryParams());
        self::assertEquals('new', $edit->queryParams()['section']);
    }

    /**
     * Test setting a section title
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::sectionTitle
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::queryParams
     */
    public function testSectionTitle(): void
    {
        $title = 'New Sec Title';

        $edit = new Edit();
        $edit->sectionTitle($title);

        self::assertArrayHasKey('sectiontitle', $edit->queryParams());
        self::assertEquals($title, $edit->queryParams()['sectiontitle']);
    }

    /**
     * Test setting a summary
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::summary
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::queryParams
     */
    public function testSummary(): void
    {
        $summary = 'Example Summary';

        $edit = new Edit();
        $edit->summary($summary);

        self::assertArrayHasKey('summary', $edit->queryParams());
        self::assertEquals($summary, $edit->queryParams()['summary']);
    }

    /**
     * Test setting create only
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::createOnly
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::queryParams
     */
    public function testCreateOnly(): void
    {
        $edit = new Edit();
        $edit->createOnly();

        self::assertArrayHasKey('createonly', $edit->queryParams());
        self::assertEquals(true, $edit->queryParams()['createonly']);
    }

    /**
     * Test setting minor
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::minor
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::queryParams
     */
    public function testMinor(): void
    {
        $edit = new Edit();
        $edit->minor();

        self::assertArrayHasKey('minor', $edit->queryParams());
        self::assertArrayNotHasKey('notminor', $edit->queryParams());
        self::assertEquals(true, $edit->queryParams()['minor']);
    }

    /**
     * Test setting notminor
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::notMinor
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::queryParams
     */
    public function testNotMinor(): void
    {
        $edit = new Edit();
        $edit->notMinor();

        self::assertArrayHasKey('notminor', $edit->queryParams());
        self::assertArrayNotHasKey('minor', $edit->queryParams());
        self::assertEquals(true, $edit->queryParams()['notminor']);
    }

    /**
     * Test setting notminor then minor
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::notMinor
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::queryParams
     */
    public function testNotMinorMinor(): void
    {
        $edit = new Edit();
        $edit->notMinor()->minor();

        self::assertArrayHasKey('minor', $edit->queryParams());
        self::assertArrayNotHasKey('notminor', $edit->queryParams());
        self::assertEquals(true, $edit->queryParams()['minor']);
    }

    /**
     * Test setting minor then notminor
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::notMinor
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::queryParams
     */
    public function testMinorNotMinor(): void
    {
        $edit = new Edit();
        $edit->minor()->notMinor();

        self::assertArrayHasKey('notminor', $edit->queryParams());
        self::assertArrayNotHasKey('minor', $edit->queryParams());
        self::assertEquals(true, $edit->queryParams()['notminor']);
    }

    /**
     * Test setting bot edit
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::markBotEdit
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::queryParams
     */
    public function testMarkBotEdit(): void
    {
        $edit = new Edit();
        $edit->markBotEdit();

        self::assertArrayHasKey('bot', $edit->queryParams());
        self::assertEquals(true, $edit->queryParams()['bot']);
    }

    /**
     * Test request without csrf token
     *
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::request
     * @covers \StarCitizenWiki\MediaWikiApi\Api\Request\Edit::queryParams
     */
    public function testRequestWithoutCsrfToken(): void
    {
        $edit = new Edit();

        $this->expectException('RuntimeException');

        $edit->request();
    }
}
