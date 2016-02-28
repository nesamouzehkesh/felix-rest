<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DashboardControllerController extends BaseController
{
    /**
     * @Route("/", name="nesa_app_display_dashboard")
     */    
    public function displayDashboardAction()
    {
        /* echo $this->get('nesa.PrintService')->printText("This text is "); */
               
        return $this->render(
            'AppBundle:DashboardController:displayDashboard.html.twig',
            array()
        );
    }
    
    /**
     * @Route("/mailer", name="nesa_app_display_dashboard_mailer")
     */    
    public function MailerAction()
    {
        echo $this->get('nesa.MailerService')->getMail("D620");
        $this->get('nesa.MailerService')->sendMail("nesa M", "Hello");
            
        return $this->render(
            'AppBundle:DashboardController:displayDashboard.html.twig', 
            array()
            );
    }
    
    /**
     * @Route("/logger", name="nesa_app_display_dashboard_logger")
     */
    public function LoggerAction()
    {
        echo $this->get('nesa.LoggerService')->logEvent("Critical", "Pending");
        $this->get('nesa.LoggerService')->getEvent("11278");
        $this->get('nesa.LoggerService')->showAllEvents("Critical");
            
        return $this->render(
            'AppBundle:DashboardController:displayDashboard.html.twig', 
            array()
            );
    }
}