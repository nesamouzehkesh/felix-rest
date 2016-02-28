<?php


namespace AppBundle\Service\Logger;

/**
 * 
 *
 * @author nesa
 */
class FileLoggerService 
{  
    public function logEvent($type, $title)
    {
        return 'File Log Type: '. $type . ' is titled: '. $title;
    }

    public function showAllEvents($type)
    {
        echo 'Events for this file are of this type: '. $type;
    }
    
    public function getEvent($id)
    {
        echo 'The File ID of this event is: '. $id;
    }
}
