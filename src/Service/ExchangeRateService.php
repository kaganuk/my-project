<?php
/**
 * Created by PhpStorm.
 * User: kagan
 * Date: 28.04.2018
 * Time: 12:28
 */

namespace App\Service;

class ExchangeRateService
{
    public function updateExchangeRates()
    {
        $this->getExchangeRatesFromApi();
    }

    private function getExchangeRatesFromApi(ProviderType $providerType)
    {
        $response = null;
        switch ($providerType){
            case ProviderType::ALPHA:
                $providerAlpha = new \ProviderAlphaAdaptor();
                $response = $this->CallAPI($providerAlpha->returnRequestUrl(),true);
                $providerAlpha->parseExchangeRates($response);
                break;
            case ProviderType::BETA:
                $providerBeta = new \ProviderBetaAdaptor();
                $response = $this->CallAPI($providerBeta->returnRequestUrl(),true);
                $providerBeta->parseExchangeRates($response);
                break;
        }

        return $response;
    }

    private function CallAPI($url, $jsonResponse = false)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);
        if ($jsonResponse){
            $result = json_decode($result);
        }

        return $result;
    }
}
