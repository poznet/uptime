<?php

namespace App\Command;

use App\Entity\SSLCheck;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SslUrlShowCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'ssl:url:show';

    protected function configure()
    {
        $this
            ->setDescription('Show all ssl websites')

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $io = new SymfonyStyle($input, $output);
        $websites=$em->getRepository("App:SSLCheck")->findAll();
        $io->writeln(' URL            |   STATUS  |    VALID TILL  ');
        foreach ($websites as $website){
            $valid='';
            if($website instanceof SSLCheck) {
                if($website->getValidTill() != NULL )
                    $valid= $website->getValidTill()->format("d-m-Y G:i:s");
                $io->writeln($website->getUrl() . '  |  ' . $website->getSslstatus() . '  |  ' . $valid);
            }
        }
        $io->writeln('DONE');
    }
}
