<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DataFixtureCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fixture:generateData')
            ->setDescription('Generate some sample data')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dataFixture = $this->getContainer()->get('nesa.dataFixture');
        $dataFixture
            ->truncateEntities()
            ->loadUserRoles()
            ->loadUsers();
        
        $output->writeln('Data loaded successfully');
    }
}