<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to generate some predefined data in DB
 * 
 * php bin/console fixture:generateData
 */
class DataFixtureCommand extends ContainerAwareCommand
{
    /**
     * Command configurations
     */
    protected function configure()
    {
        $this
            ->setName('fixture:generateData')
            ->setDescription('Generate some sample data')
        ;
    }
    
    /**
     * 
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Importing predefined data ...<comment>');
        $dataFixture = $this->getContainer()->get('app.dataFixture.service');
        $dataFixture
            ->truncateEntities()
            ->loadUserRoles()
            ->loadUsers()
            ->loadPages()
            ->loadProducts()
            ;
        
        $output->writeln('<info>Data loaded successfully. Visit this page http://<domain>/api<info>');
    }
}