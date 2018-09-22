<?php

namespace App\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use WowApps\SlackBundle\DTO\SlackMessage;

class UptimeUrlShowCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'uptime:url:show';

    protected function configure()
    {
        $this
            ->setDescription('Shows all websites');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $output->writeln('ID |      URL          |  EMAIL       |     SLACK');
        $www = $em->getRepository("App:Website")->findAll();
        foreach ($www as $w) {
            $output->writeln($w->getId() . '  |  ' . $w->getUrl() . '   |    ' . $this->yesno($w->getEmail()) . '    |    ' . $this->yesno($w->getSlack()));
        }
    }

    private function yesno($var)
    {
        if ($var === true)
            return 'YES';
        return 'NO';
    }
}
