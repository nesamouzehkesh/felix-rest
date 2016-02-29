<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Library\Base\BaseController;

class DashboardControllerController extends BaseController
{
    /**
     * @Route("/", name="nesa_app_display_dashboard")
     */    
    public function displayDashboardAction()
    {
        /* echo $this->get('nesa.PrintService')->printText("This text is "); */
               
        return $this->render(
            '::admin/app/dashboard.html.twig',
            array()
        );
    }
    
    /**
     * @Route("/mailer", name="nesa_app_display_dashboard_mailer")
     */    
    public function MailerAction()
    {
        echo $this->get('app.mailer.service')->getMail("D620");
        $this->get('nesa.MailerService')->sendMail("nesa M", "Hello");
            
        return $this->render(
            '::admin/app/dashboard.html.twig',
            array()
            );
    }
    
    /**
     * @Route("/logger", name="nesa_app_display_dashboard_logger")
     */
    public function LoggerAction()
    {
        echo $this->get('app.logger.service')->logEvent("Critical", "Pending");
        $this->get('app.logger.service')->getEvent("11278");
        $this->get('app.logger.service')->showAllEvents("Critical");
            
        return $this->render(
            '::admin/app/dashboard.html.twig',
            array()
            );
    }
}