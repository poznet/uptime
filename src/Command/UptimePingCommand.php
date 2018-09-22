<?php

namespace App\Command;

use App\Service\PingService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UptimePingCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'uptime:ping';

    protected function configure()
    {
        $this
            ->setDescription('Tries to ping all urls in db')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $www = $em->getRepository("App:Website")->findAll();

        $output->writeln('Starting ... ');
        foreach ($www as $w){
            $output->write($w->getUrl().' - ');
            $result=$this->getContainer()->get(PingService::class)->ping($w);
            $output->writeln($result);
        }
        $output->writeln('DONE');
    }
}
