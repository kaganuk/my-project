<?php
/**
 * Created by PhpStorm.
 * User: kagan
 * Date: 28.04.2018
 * Time: 12:04
 */

class ProviderBetaAdaptor implements ExchangeRateProviderInterface
{
    private const URL = 'http://www.mocky.io/v2/5a74519d2d0000430bfe0fa0';

    public function saveExchangeRates(): string
    {
        // TODO: Implement saveExchangeRates() method.
    }

    public function returnRequestUrl(): string
    {
        return self::URL;
    }

    public function parseExchangeRates($response): array
    {
        // TODO: Implement parseExchangeRates() method.
    }
}