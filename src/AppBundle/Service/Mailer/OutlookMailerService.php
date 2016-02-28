<?php

namespace AppBundle\Service\Mailer;

/**
 *
 * @author nesa
 */
class OutlookMailerService 
{  
    /**
     * 
     * @param type $id
     * @return type
     */
    public function getMail($id)
    {
        return 'Sender ID: ' . $id . ' is received by a:  Outlook Server';
    }
    
    /**
     * 
     * @param string $to
     * @param string $subject
     */
    public function sendMail($to, $subject)
    {
        echo 'email has been sent to: '. $to . ' with subject: '. $subject . ' #Outlook';
    }
}