<?php
/**
 * Created by PhpStorm.
 * User: kagan
 * Date: 28.04.2018
 * Time: 12:04
 */

use App\Helper\Util;

class ProviderAlphaAdaptor implements ExchangeRateProviderInterface
{
    private const URL = 'http://www.mocky.io/v2/5a74524e2d0000430bfe0fa3';

    public function saveExchangeRates(): string
    {
        // TODO: Implement saveExchangeRates() method.
    }

    public function parseExchangeRates(): array
    {
        dump($this->getExchangeRates());die;
    }

    public function getExchangeRates(): string
    {
        return Util::makeRequest(self::URL);
    }
}