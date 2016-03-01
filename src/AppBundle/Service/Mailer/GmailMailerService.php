<?php

namespace AppBundle\Service\Mailer;

/**
 *
 * @author nesa
 */
class GmailMailerService 
{  
    public function getMail($id)
    {
        return 'Sender ID: '. $id . ' is received by a: Gmail Server';
    }

    public function sendMail($to, $subject)
    {
        echo 'email has been sent to: '. $to . ' with subject: '. $subject . ' #Gmail';
    }
}
