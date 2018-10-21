<?php

namespace App\Command;

use App\Event\SSLCheckEvent;
use Punkstar\Ssl\Reader;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SslCheckCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'ssl:check';

    protected function configure()
    {
        $this
            ->setDescription('Checks ssls ')

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em=$this->getContainer()->get('doctrine.orm.entity_manager');
        $ed=$this->getContainer()->get('event_dispatcher');
        $io = new SymfonyStyle($input, $output);
        $env = $this->getContainer()->get('kernel')->getEnvironment();
        $interval=$this->getContainer()->getParameter('sslcheck'); //interval in days for check from parameters

        $websites=$em->getRepository("App:SSLCheck")->findByLastCheckOlderThan($interval['olderthan']);

        foreach ($websites as $website){
            if($env=='dev')
            $io->writeln($website->getUrl());
            $event=new SSLCheckEvent($website);
            $ed->dispatch('ssl.check',$event);
        }


//        $url=$io->ask('Enter  url od domanin (without https:// ');
//        $reader=new Reader();
//        $cert=$reader->readFromUrl('https://'.$url);
//        $io->writeln('SSL Name : '.$cert->certName());
//        $io->writeln('SSL Valid to : '.$cert->validTo()->format('d-m-Y'));
//        $io->writeln('SSL Issuer : '.$cert->issuer()["O"]);
////        $io->writeln('SSL Sans : '.$cert->sans());
////        $io->writeln('SSL Subject : '.$cert->subject());

    }
}
