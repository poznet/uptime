<?php

namespace App\Command;


use App\Entity\SSLCheck;
use App\Helper\UrlHelper;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SslUrlAddCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'ssl:url:add';

    protected function configure()
    {
        $this
            ->setDescription('Adds domain to check SSL')
               ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $io = new SymfonyStyle($input, $output);
        $url=$io->ask('Enter url of  webisite that you want to be checked ');

        if(!empty($url)){
            $websitecheck=$em->getRepository("App:SSLCheck")->findOneByUrl(UrlHelper::clearUrl($url));
            if($websitecheck instanceof SSLCheck){
                $io->writeln('Site already exist');
                return false;
            }
//            $io->writeln(UrlHelper::clearUrl($url));
            $website=new SSLCheck();
            $website->setUrl($url);
            $em->persist($website);
            $em->flush();
        }

    }
}
