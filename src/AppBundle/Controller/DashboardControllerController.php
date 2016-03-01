<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Library\Base\BaseController;

class DashboardControllerController extends BaseController
{
    /**
     * @Route("/", name="admin_app_dashboard")
     */    
    public function displayDashboardAction()
    {
        return $this->render(
            '::admin/app/dashboard.html.twig',
            array()
        );
    }
    
    /**
     * @Route("/app/mailer", name="admin_app_dashboard_mailer")
     */    
    public function MailerAction()
    {
        echo $this->get('app.mailer.service')->getMail("D620");
        $this->get('nesa.MailerService')->sendMail("nesa M", "Hello");
        
        exit;
    }
    
    /**
     * @Route("/app/logger", name="admin_app_dashboard_logger")
     */
    public function LoggerAction()
    {
        echo $this->get('app.logger.service')->logEvent("Critical", "Pending");
        $this->get('app.logger.service')->getEvent("11278");
        $this->get('app.logger.service')->showAllEvents("Critical");
        
        exit;
    }
}