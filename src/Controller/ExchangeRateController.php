<?php
/**
 * Created by PhpStorm.
 * User: kagan
 * Date: 27.04.2018
 * Time: 20:47
 */

namespace App\Controller;

use App\Entity\ExchangeRate;
use App\Model\ExchangeRateType;
use App\Service\ExchangeRateService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExchangeRateController
{
    /**
     * @Route("/", name="Exchange_rate_list")
     * @param \App\Service\ExchangeRateService $exchangeRateService
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function list(ExchangeRateService $exchangeRateService):Response
    {
        $exRatesAsText = '';
        $lastExchangeRates = $exchangeRateService->getLastExchangeRates();
        /** @var ExchangeRate $item */
        foreach ($lastExchangeRates as $item){
            $exRatesAsText .= ExchangeRateType::getTitle($item->getType()) . ' : ' . $item->getRate() . '<br>';
        }
        return new Response(
            '<html><body>
                            <h2>Exchange Rates</h2>
                            '.$exRatesAsText.'
                    </body></html>'
        );
    }
}