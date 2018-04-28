<?php
/**
 * Created by PhpStorm.
 * User: kagan
 * Date: 28.04.2018
 * Time: 12:04
 */

namespace App\Service\Adapter;

use App\Entity\ExchangeRate;
use App\Helper\Util;
use App\Model\ExchangeRateType;

class ProviderAlphaAdaptor implements ExchangeRateProviderInterface
{
    private const URL = 'http://www.mocky.io/v2/5a74524e2d0000430bfe0fa3';

    private $exchangeRates;

    public function saveExchangeRates(): string
    {
        $exchangeRates = $this->exchangeRates;
        if (!empty($exchangeRates)) {
            foreach ($exchangeRates as $item){
                //save db $item
            }
        }
    }

    /**
     * @return array
     */
    public function parseExchangeRates():array
    {
        $type = 0;
        $this->exchangeRates = [];
        $response = $this->getExchangeRates();
        foreach ($response as $item){
            $exchangeRate = new ExchangeRate();
            switch ($item['kod']){
                case 'DOLAR':
                    $type = ExchangeRateType::USDTRY;
                    break;
                case 'AVRO':
                    $type = ExchangeRateType::EURTRY;
                    break;
                case 'İNGİLİZ STERLİNİ':
                    $type = ExchangeRateType::GBPTRY;
                    break;
            }
            $exchangeRate->setType($type);
            $exchangeRate->setRate($item['oran']);
            $this->exchangeRates[$type] = $exchangeRate;
        }

        return $this->exchangeRates;
    }

    /**
     * @return mixed
     */
    private function getExchangeRates()
    {
        return Util::makeRequest(self::URL,true);
    }
}