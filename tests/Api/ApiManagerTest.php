<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use StarCitizenWiki\MediaWikiApi\Api\ApiManager;

class ApiManagerTest extends TestCase
{
    /**
     * @covers \StarCitizenWiki\MediaWikiApi\Api\ApiManager::setTokenFromCredentials
     * @covers \StarCitizenWiki\MediaWikiApi\Api\ApiManager::getToken
     */
    public function testSetToken(): void
    {
        $manager = new ApiManager();
        $manager->setTokenFromCredentials('Token', 'Secret');

        $token = $manager->getToken();

        self::assertEquals('Token', $token->key);
        self::assertEquals('Secret', $token->secret);
    }

    /**
     * @covers \StarCitizenWiki\MediaWikiApi\Api\ApiManager::setConsumerFromCredentials
     * @covers \StarCitizenWiki\MediaWikiApi\Api\ApiManager::getConsumer
     */
    public function testSetConsumer(): void
    {
        $manager = new ApiManager();
        $manager->setConsumerFromCredentials('Token', 'Secret');

        $consumer = $manager->getConsumer();

        self::assertEquals('Token', $consumer->key);
        self::assertEquals('Secret', $consumer->secret);
    }
}
