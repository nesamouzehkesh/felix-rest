<?php

namespace AppBundle\Library\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    /**
     * Generate Csrf Token and put it in a session parameter with this name $tokenName
     * 
     * @param String $intention The intention used when generating the CSRF token
     * @param String $tokenName The name of session parameter 
     * @return String
     */
    public function generateAccessToken($intention = 'form', $tokenName = 'access_token') 
    {
        // Generate a CSRF token
        $token = $this->getService('form.csrf_provider')->generateCsrfToken($intention);

        $this->getSession()->set($tokenName, $token);
        
        return $token;
    }
    
    /**
     * Set flash bag
     *
     * @param $messageType
     * @param $message
     * @param null $ex
     */
    public function setFlashBag($messageType, $message)
    {
        $flashBag = $this->getSession()->getFlashBag();
        
        $flashBag->clear();
        $this->addFlashBag($messageType, $message);
    }
    
    /**
     * 
     * @param type $messageType
     * @param type $message
     */
    public function addFlashBag($messageType, $message)
    {
        $flashBag = $this->getSession()->getFlashBag();
        $transedMessage = $this->transMessage($message);
        
        $flashBag->add($messageType, $transedMessage);
    }

    /**
     * 
     * @return \AppBundle\Service\AppService
     */
    public function getAppService()
    {
        return $this->getService('app.service');
    }

    /**
     * 
     * @return type
     */
    public function getDispatcher()
    {
        return $this->getService('event_dispatcher');
    }
    
    /**
     * 
     * @return \AppBundle\Service\Session
     */
    public function getSession()
    {
        return $this->getService('app.session.service');
    }    
}