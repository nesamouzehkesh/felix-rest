<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Command to generate some predefined data in DB
 * 
 * php bin/console fixture:generateData
 */
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
        $router = $this->getContainer()->get('router');
        $dashboardUrl = $router->generate(
            'nesa_app_display_dashboard',
            array(),
            UrlGeneratorInterface::ABSOLUTE_URL
            );
        
        $output->writeln('<comment>Importing predefined data ...<comment>');
        $dataFixture = $this->getContainer()->get('app.dataFixture.service');
        $dataFixture
            ->truncateEntities()
            ->loadUserRoles()
            ->loadUsers();
        
        $output->writeln(sprintf(
            '<info>Data loaded successfully. Visit this page %s and login with "admin@admin.com" with password "admin"<info>', 
            $dashboardUrl
            ));
    }
}