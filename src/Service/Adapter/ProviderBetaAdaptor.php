<?php
/**
 * Created by PhpStorm.
 * User: kagan
 * Date: 28.04.2018
 * Time: 12:04
 */

use App\Helper\Util;

class ProviderBetaAdaptor implements ExchangeRateProviderInterface
{
    private const URL = 'http://www.mocky.io/v2/5a74519d2d0000430bfe0fa0';

    public function saveExchangeRates(): string
    {
        // TODO: Implement saveExchangeRates() method.
    }

    public function parseExchangeRates(): array
    {
        dump($this->getExchangeRates());die;
    }

    /**
     * @return mixed|string
     */
    private function getExchangeRates()
    {
        return Util::makeRequest(self::URL);
    }
}