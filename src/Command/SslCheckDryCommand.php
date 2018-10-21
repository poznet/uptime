<?php

namespace App\Command;

use Punkstar\Ssl\Reader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SslCheckDryCommand extends Command
{
    protected static $defaultName = 'ssl:check:dry';

    protected function configure()
    {
        $this
            ->setDescription('Checks ssl of a given url')

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $url=$io->ask('Enter  url od domanin (without https:// ');
        $reader=new Reader();
        $cert=$reader->readFromUrl('https://'.$url);
        $io->writeln('SSL Name : '.$cert->certName());
        $io->writeln('SSL Valid to : '.$cert->validTo()->format('d-m-Y'));
        $io->writeln('SSL Issuer : '.$cert->issuer()["O"]);
//        $io->writeln('SSL Sans : '.$cert->sans());
//        $io->writeln('SSL Subject : '.$cert->subject());

    }
}
