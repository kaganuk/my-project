<?php
/**
 * Created by PhpStorm.
 * User: kagan
 * Date: 28.04.2018
 * Time: 12:28
 */

namespace App\Service;

use App\Entity\ExchangeRate;
use App\Model\ExchangeRateType;
use App\Repository\ExchangeRateRepository;
use App\Service\Adapter\ProviderAlphaAdaptor;
use App\Service\Adapter\ProviderBetaAdaptor;
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
        $providerAlpha          = new ProviderAlphaAdaptor();
        $providerBeta           = new ProviderBetaAdaptor();
        try {
            $responseAlpha          = $providerAlpha->parseExchangeRates();
            $responseBeta           = $providerBeta->parseExchangeRates();
            $cheaperExchangeRates   = $this->getCheaperExchangeRates($responseAlpha, $responseBeta);
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
     * @param $firstItem
     * @param $secondItem
     *
     * @return array
     */
    private function getCheaperExchangeRates($firstItem, $secondItem): array
    {

        $exchangeRateTypes = ExchangeRateType::getExchangeRateTypes();
        $cheaperExchangeRates = [];

        try{
            foreach ($exchangeRateTypes as $type){
                /** @var ExchangeRate $firstEntity */
                /** @var ExchangeRate $secondEntity */
                $firstEntity    = $firstItem[$type];
                $secondEntity   = $secondItem[$type];
                $cheaperExchangeRate = ($firstEntity->getRate() < $secondEntity->getRate()) ?
                    $firstEntity : $secondEntity;
                array_push($cheaperExchangeRates, $cheaperExchangeRate);
            }
        } catch (\Exception $exception){
            $this->logger->error($exception->getMessage());
        }

        return $cheaperExchangeRates;
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
}
