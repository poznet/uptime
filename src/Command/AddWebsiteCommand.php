<?php
/**
 * Created by PhpStorm.
 * User: pozyc
 * Date: 22.09.2018
 * Time: 00:34
 */

namespace App\Command;


use App\Entity\Website;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class AddWebsiteCommand extends ContainerAwareCommand
{
    private $url;
    private $email;
    private $slack;

    protected function configure()
    {
        $this
            ->setName('uptime:url:add')
            ->setDescription('Adds website to uptime checks');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $helper = $this->getHelper('question');
        $q = new Question('Enter website url address : ');
        $this->url = $helper->ask($input, $output, $q);

        $q = new ConfirmationQuestion('Do you want to be notified by email ? ', false);
        $this->email = $helper->ask($input, $output, $q);

        $q = new ConfirmationQuestion('Do you want to be notified by slack ? ', false);
        $this->slack = $helper->ask($input, $output, $q);

        $www = $em->getRepository("App:Website")->findOneByUrl($this->url);
        if (!$www instanceof Website)
            $www = new Website();
        $www->setUrl($this->url);
        $www->setEmail($this->email);
        $www->setSlack($this->slack);
        $em->persist($www);
        $em->flush();
        $output->writeln('Done.');


    }
}