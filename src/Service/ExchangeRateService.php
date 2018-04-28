<?php
/**
 * Created by PhpStorm.
 * User: kagan
 * Date: 28.04.2018
 * Time: 12:28
 */

namespace App\Service;

use ExchangeRateProviderInterface;

class ExchangeRateService
{
    public function updateExchangeRates(ExchangeRateProviderInterface $exchangeRateProvider)
    {
        $exchangeRateProvider->parseExchangeRates();
    }

    private function getExchangeRatesFromApi($providerType): ?array
    {
        $responseEntity = null;
        switch ($providerType){
            case ProviderTypes::ALPHA:
                $providerAlpha = new ProviderAlphaAdaptor();
                $response = $this->CallAPI($providerAlpha->returnRequestUrl(),true);
                $responseEntity = $providerAlpha->parseExchangeRates($response);
                break;
            case ProviderTypes::BETA:
                $providerBeta = new \ProviderBetaAdaptor();
                $response = $this->CallAPI($providerBeta->returnRequestUrl(),true);
                $responseEntity = $providerBeta->parseExchangeRates($response);
                break;
        }

        return $responseEntity;
    }


}
