<?php

declare(strict_types=1);

namespace StarCitizenWiki\MediaWikiApi\Api;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use InvalidArgumentException;
use MediaWiki\OAuthClient\Consumer;
use MediaWiki\OAuthClient\Token;

/**
 * Class Api Manager
 */
class ApiManager
{
    /**
     * @var Consumer
     */
    private Consumer $consumer;

    /**
     * @var Token|null
     */
    private ?Token $token = null;

    /**
     * Creates the Usertoken to use
     *
     * @param Authenticatable|null $user Optional User Object to use
     *
     * @return Token
     *
     * @throws InvalidArgumentException
     */
    public function getToken(?Authenticatable $user = null): Token
    {
        if (null !== $this->token) {
            return $this->token;
        }

        $driver = config('mediawiki.token_driver', 'session');

        if ('session' === $driver) {
            $tokenData = $this->getTokenFromSession();
        } elseif ('database' === $driver) {
            if (null === $user) {
                $user = Auth::user();
            }

            $tokenData = $this->getTokenFromDatabase($user);
        } else {
            throw new InvalidArgumentException("Invalid driver '{$driver}'");
        }

        return $this->token = new Token($tokenData['token'], $tokenData['secret']);
    }

    /**
     * Creates a Token with given Credentials
     *
     * @param string $accessToken
     * @param string $accessSecret
     */
    public function setTokenFromCredentials(string $accessToken, string $accessSecret): void
    {
        $this->token = new Token($accessToken, $accessSecret);
    }

    /**
     * Creates a Consumer with given Credentials
     *
     * @param string $consumerToken
     * @param string $consumerSecret
     */
    public function setConsumerFromCredentials(string $consumerToken, string $consumerSecret): void
    {
        $this->consumer = new Consumer($consumerToken, $consumerSecret);
    }

    /**
     * Creates the Consumer Object
     *
     * @return Consumer
     *
     * @throws InvalidArgumentException
     */
    public function getConsumer(): Consumer
    {
        if (null !== $this->consumer) {
            return $this->consumer;
        }

        $clientID = config('services.mediawiki.client_id', null);
        $clientSecret = config('services.mediawiki.client_secret', null);

        if (null === $clientID || null === $clientSecret) {
            throw new InvalidArgumentException('Missing Client ID or Client Secret');
        }

        return $this->consumer = new Consumer($clientID, $clientSecret);
    }

    /**
     * Retrieve Token Data from Session
     *
     * @return array
     *
     * @throws InvalidArgumentException
     */
    private function getTokenFromSession(): array
    {
        $tokenKey = config('mediawiki.driver.session.token', null);
        $secretKey = config('mediawiki.driver.session.secret', null);

        if (null === $tokenKey || null === $secretKey) {
            throw new InvalidArgumentException('Invalid Session Token Key or Session Secret Key');
        }

        return [
            'token' => Session::get((string)$tokenKey),
            'secret' => Session::get((string)$secretKey),
        ];
    }

    /**
     * Retrieve Token Data from User Object
     *
     * @param Authenticatable|null $user
     *
     * @return array
     *
     * @throws InvalidArgumentException
     */
    private function getTokenFromDatabase(?Authenticatable $user): array
    {
        if (null === $user) {
            throw new InvalidArgumentException('User not logged In');
        }

        $tokenKey = config('mediawiki.driver.database.token', null);
        $secretKey = config('mediawiki.driver.database.secret', null);

        if (null === $tokenKey || null === $secretKey) {
            throw new InvalidArgumentException('Invalid Database Token Field or Database Secret Key');
        }

        return [
            'token' => $user->{$tokenKey},
            'secret' => $user->{$secretKey},
        ];
    }
}
