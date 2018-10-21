<?php

namespace App\Command;

use App\Entity\Website;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class UptimeUrlEditCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'uptime:url:edit';

    protected function configure()
    {
        $this
            ->setDescription('Edits  configuration for given action')
            ->addArgument('id', InputArgument::OPTIONAL, 'Argument description');
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
        $io->writeln("You are editing : " . $website->getUrl().' ,  enter new  values  to edit ');
        $data=$io->ask("URL : ",$website->getUrl());
        $website->setUrl($data);
        $data=$io->ask("Email  notification [0/1] : ",$website->getEmail());
        $website->setEmail($data);
        $data=$io->ask("Slack  notification [0/1] : ",$website->getSlack());
        $website->setSlack($data);
        $data=$io->ask("Description : ",$website->getDescription());
        $website->setDescription($data);

        $em->flush();
        $io->writeln("Done...");

    }
}
