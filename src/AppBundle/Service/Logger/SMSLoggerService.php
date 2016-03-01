<?php

namespace AppBundle\Service\Logger;

/**
 *
 * @author nesa
 */
class SMSLoggerService 
{  
    public function logEvent($type, $title)
    {
        return 'SMS Log Type: '. $type . ' is titled: '. $title;
    }

    public function showAllEvents($type)
    {
        echo 'Events for this SMS are of this type: '. $type;
    }
    
    public function getEvent($id)
    {
        echo 'The SMS ID of this event is: '. $id;
    }
}
