<?php

namespace App\Command;

use App\Entity\Website;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UptimeUrlDetailsCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'uptime:url:details';

    protected function configure()
    {
        $this
            ->setDescription('Show  details  about  website')
            ->addArgument('id', InputArgument::OPTIONAL, 'Id of website')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $io = new SymfonyStyle($input, $output);
        $id = $input->getArgument('id');
        $website = null;

        if ($id) {
            $website = $em->getRepository("App:Website")->findOneById($id);
            if (!$website instanceof Website) {
                $io->writeln('Id not  found');
                return false;
            }
        }
        if (!$website instanceof Website) {
            $r = $io->ask("Enter id of website that you want to edit",0);
            $website = $em->getRepository("App:Website")->findOneById($r);
            if (!$website instanceof Website) {
                $io->writeln('Id not  found');
                return false;
            }
        }
        $io->writeln("Url : ".$website->getUrl());
        $io->writeln("Email Notification : ".$website->getEmail());
        $io->writeln("SlackNotification : ".$website->getSlack());
        $io->writeln("Description : ".$website->getDescription());
        $io->writeln("");
        $io->writeln("Pings  (last 30) : ");
        $io->writeln("");
        $output->writeln("Data      | Stauts | Response | SSL ");
        $pings=$em->getRepository("App:Ping")->findBy(['website'=>$website->getId()],['id'=>'DESC'],30);
        foreach ($pings as $ping){
            $output->writeln($ping->getData()->format("d-m-Y G:i:s").' | '.$ping->getStatus().' | '.$ping->getResponseCode().' | '.$ping->getSsl());

        }

    }
}
