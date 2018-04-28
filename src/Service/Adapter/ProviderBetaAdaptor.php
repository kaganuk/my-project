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

class ProviderBetaAdaptor implements ExchangeRateProviderInterface
{
    private const URL = 'http://www.mocky.io/v2/5a74519d2d0000430bfe0fa0';

    private $exchangeRates;

    public function saveExchangeRates(): string
    {
        // TODO: Implement saveExchangeRates() method.
    }

    public function parseExchangeRates():array
    {
        $type = 0;
        $this->exchangeRates = [];
        $response = $this->getExchangeRates();
        $response = $response['result'];
        foreach ($response as $item){
            $exchangeRate = new ExchangeRate();
            switch ($item['symbol']){
                case 'USDTRY':
                    $type = ExchangeRateType::USDTRY;
                    break;
                case 'EURTRY':
                    $type = ExchangeRateType::EURTRY;
                    break;
                case 'GBPTRY':
                    $type = ExchangeRateType::GBPTRY;
                    break;
            }
            $exchangeRate->setType($type);
            $exchangeRate->setRate($item['amount']);
            $this->exchangeRates[$type] = $exchangeRate;
        }

        return $this->exchangeRates;
    }

    /**
     * @return mixed|string
     */
    private function getExchangeRates()
    {
        return Util::makeRequest(self::URL,true);
    }
}