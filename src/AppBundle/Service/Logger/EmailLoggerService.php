<?php


namespace AppBundle\Service\Logger;

/**
 * 
 *
 * @author nesa
 */
class EmailLoggerService 
{  
    public function logEvent($type, $title)
    {
        return 'Email Log Type: '. $type . ' is titled: '. $title;
    }

    public function showAllEvents($type)
    {
        echo 'Events for this Email are of this type: '. $type;
    }
    
    public function getEvent($id)
    {
        echo 'The Email ID of this event is: '. $id;
    }
}
