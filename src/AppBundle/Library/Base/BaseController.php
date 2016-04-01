<?php

namespace AppBundle\Library\Base;

use FOS\RestBundle\Controller\FOSRestController;

class BaseController extends FOSRestController
{
    /**
     * 
     * @return \AppBundle\Service\AppService
     */
    public function getAppService()
    {
        return $this->get('app.service');
    }

    /**
     * 
     * @return type
     */
    public function getDispatcher()
    {
        return $this->get('event_dispatcher');
    }
    
    /**
     * 
     * @return \AppBundle\Service\Session
     */
    public function getSession()
    {
        return $this->get('app.session.service');
    }    
}