<?php

namespace App\Command;

use App\Entity\Notify;
use App\Entity\SSLCheck;
use App\Event\NotifyEvent;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SslNotifyCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'ssl:notify';

    protected function configure()
    {
        $this
            ->setDescription('Notify about ssl s')

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $event=null;
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $io = new SymfonyStyle($input, $output);

        // Check  email
        $email=$em->getRepository('App:Notify')->findOneBy(
            ['what'=>Notify::NOTIFY_WHAT_SSL,
            'channel'=>Notify::NOTIFY_CHANNEL_EMAIL],['id'=>'DESC']
        );
        if($email instanceof Notify){
            $data=$email->getDate();
            $now=new \DateTime();
            $now->modify('-'.$this->getContainer()->getParameter('sslcheck')['notification']['email'].' day');
            if($data<$now)
                $event=new NotifyEvent(Notify::NOTIFY_WHAT_SSL,Notify::NOTIFY_CHANNEL_EMAIL);
        }else{
            $event=new NotifyEvent(Notify::NOTIFY_WHAT_SSL,Notify::NOTIFY_CHANNEL_EMAIL);
        }

        if($event instanceof NotifyEvent)
            $this->getContainer()->get('event_dispatcher')->dispatch(NotifyEvent::NAME,$event);

        //check slack
        $slack=$em->getRepository('App:Notify')->findOneBy(
            ['what'=>Notify::NOTIFY_WHAT_SSL,
                'channel'=>Notify::NOTIFY_CHANNEL_SLACK],['id'=>'DESC']
        );
        if($slack instanceof Notify){
            $data=$slack->getDate();
            $now=new \DateTime();
            $now->modify('-'.$this->getContainer()->getParameter('sslcheck')['notification']['slack'].' day');
            if($data<$now)
                $event=new NotifyEvent(Notify::NOTIFY_WHAT_SSL,Notify::NOTIFY_CHANNEL_SLACK);
        }else{
            $event=new NotifyEvent(Notify::NOTIFY_WHAT_SSL,Notify::NOTIFY_CHANNEL_SLACK);
        }

        if($event instanceof NotifyEvent)
            $this->getContainer()->get('event_dispatcher')->dispatch(NotifyEvent::NAME,$event);
    }
}
