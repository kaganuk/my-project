<?php
/**
 * Created by PhpStorm.
 * User: kagan
 * Date: 28.04.2018
 * Time: 12:28
 */

namespace App\Service;

use App\Entity\ExchangeRate;
use App\Helper\Util;
use App\Model\ExchangeRateType;
use App\Repository\ExchangeRateRepository;
use App\Service\Adapter\ExchangeRateProviderInterface;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class ExchangeRateService
{
    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    /**
 * @var LoggerInterface $em
 */
    private $logger;

    /**
     * @var ExchangeRateRepository $em
     */
    private $exchangeRateRepository;

    /**
     * ExchangeRateService constructor.
     *
     * @param EntityManagerInterface                 $entityManager
     * @param \Psr\Log\LoggerInterface               $logger
     * @param \App\Repository\ExchangeRateRepository $exchangeRateRepository
     */
    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger, ExchangeRateRepository $exchangeRateRepository)
    {
        $this->em                       = $entityManager;
        $this->exchangeRateRepository   = $exchangeRateRepository;
        $this->logger                   = $logger;
    }

    /**
     * updates exchange rates
     */
    public function updateExchangeRates(): void
    {
        try {
            $cheaperExchangeRates   = $this->getCheaperExchangeRates();
            $this->addExchangeRates($cheaperExchangeRates);
        } catch (\Exception $exception){
            $this->logger->error($exception->getMessage());
        }
    }

    public function getLastExchangeRates(): array
    {
        $exchangeRateCount = \count(ExchangeRateType::getExchangeRateTypes());
        $criteria = Criteria::create();
        $criteria->orderBy(['created'=>'DESC']);
        $criteria->setMaxResults($exchangeRateCount);
        return $this->exchangeRateRepository->matching($criteria)->toArray();
    }

    /**
     * @return array
     */
    private function getCheaperExchangeRates(): array
    {
        $exchangeRateTypes      = ExchangeRateType::getExchangeRateTypes();
        $exchangeRatesStack     = $this->getExchangeRatesFromProviders();
        $cheaperExchangeRates   = [];

        try{
            foreach ($exchangeRateTypes as $type){
                $cheaperExchangeRates = $this->calculateCheaperExchangeRates($exchangeRatesStack, $type, $cheaperExchangeRates);
            }
        } catch (\Exception $exception){
            $this->logger->error($exception->getMessage());
        }

        return $cheaperExchangeRates;
    }


    private function getExchangeRatesFromProviders(): array
    {
        $providerPaths = Util::getImplementingClasses(ExchangeRateProviderInterface::class);
        $exchangeRates = [];

        try {
            /** @var ExchangeRateProviderInterface $provider */
            foreach ($providerPaths as $item){
                $provider = new $item();
                $exchangeRates[] = $provider->parseExchangeRates();
            }
        } catch (\Exception $exception){
            $this->logger->error($exception->getMessage());
        }

        return $exchangeRates;
    }

    /**
     * @param $exchangeRates
     */
    private function addExchangeRates($exchangeRates): void
    {
        try{
            foreach ($exchangeRates as $item){
                $this->em->persist($item);
                $this->em->flush($item);
            }
        } catch (\Exception $exception){
            $this->logger->error($exception->getMessage());
        }
    }

    /**
     * @param $exchangeRates
     * @param $type
     * @param $cheaperExchangeRates
     *
     * @return array
     */
    private function calculateCheaperExchangeRates($exchangeRates, $type, $cheaperExchangeRates): array
    {
        $lastExchangeRate = null;
        $cheaperExchangeRate = null;

        try{
            /** @var ExchangeRate $exchangeRate */
            foreach ($exchangeRates as $exchangeRate) {
                $exchangeRate = $exchangeRate[$type];
                if ($lastExchangeRate !== null) {
                    $cheaperExchangeRate = ($lastExchangeRate->getRate() < $exchangeRate->getRate()) ?
                        $lastExchangeRate : $exchangeRate;
                } else {
                    $cheaperExchangeRate = $exchangeRate;
                }
                $lastExchangeRate = $exchangeRate;
            }
        } catch (\Exception $exception){
            $this->logger->error($exception->getMessage());
        }

        if ($cheaperExchangeRate instanceof ExchangeRate) {
            $cheaperExchangeRates[] = $cheaperExchangeRate;
        }

        return $cheaperExchangeRates;
    }
}
