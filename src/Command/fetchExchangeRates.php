<?php
/**
 * Created by PhpStorm.
 * User: kagan
 * Date: 28.04.2018
 * Time: 09:02
 */

// src/Command/CreateUserCommand.php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\ExchangeRateService;

class fetchExchangeRates extends Command
{
    private $exchangeRateService;

    public function __construct(ExchangeRateService $exchangeRateService)
    {
        $this->exchangeRateService = $exchangeRateService;

        // you *must* call the parent constructor
        parent::__construct();
    }
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:fetch-exchange-rates')

            // the short description shown while running "php bin/console list"
            ->setDescription('Fetches exchange rates.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to fetch exchange rates...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $a = new \ProviderAlphaAdaptor();
        $response = $this->exchangeRateService->updateExchangeRates($a);
        dump(json_decode($response));die;
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Exchange Rates',
            '============',
        ]);
    }
}