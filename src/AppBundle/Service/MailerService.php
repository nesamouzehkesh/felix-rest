<?php

namespace AppBundle\Service;

/**
 * Description of Mailer service
 *
 * @author nesa
 */
class MailerService
{
    /**
     * 
     * @param type $gmailMailerService
     * @param type $outlookMailerService
     * @param type $mailerType
     */
    public function __construct($gmailMailerService, $outlookMailerService, $mailerType)
    {
        $this->gmailMailerService = $gmailMailerService;
        $this->outlookMailerService = $outlookMailerService;
        $this->mailerType = $mailerType;
    }

    /**
     * 
     * @param type $id
     * @return type
     */
    public function getMail($id)
    {
        return $this->getMailerService()->getMail($id);
    }
            
    /**
     * 
     * @param type $to
     * @param type $subject
     */
    public function sendMail($to, $subject)
    {
        $this->getMailerService()->sendMail($to, $subject);
    }
    
    /**
     * 
     * @return type
     */
    public function getMailerService()
    {
        switch ($this->mailerType) {
            case "Gmail":
                return $this->gmailMailerService;
            case "Outlook":
                return $this->outlookMailerService;
        }
    }    
}