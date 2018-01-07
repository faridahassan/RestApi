<?php

namespace BackendBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InactivityEmailCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('inactivity:email')
            ->setDescription('Sends Daily Inactivity Emails to subscribed users')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $message = $this->getContainer()->get('reciprocasrest.backendbundle.mailingManager')->sendInactivityEmails();
        $output->writeln($message);
    }
}