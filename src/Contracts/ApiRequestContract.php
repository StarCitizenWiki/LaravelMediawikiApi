<?php
/**
 * User: Hannes
 * Date: 06.10.2018
 * Time: 17:02
 */

namespace StarCitizenWiki\MediawikiApi\Contracts;


use GuzzleHttp\Psr7\Response;

interface ApiRequestContract
{
    public function queryParams(): array;

    public function requestMethod(): string;

    public function needsAuthentication(): bool;

    public function request(): Response;
}