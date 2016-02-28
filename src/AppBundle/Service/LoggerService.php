<?php

namespace AppBundle\Service;

/**
 * Description of Mailer service
 *
 * @author nesa
 */
class LoggerService
{
    /**
     * 
     * @param type $smsLoggerService
     * @param type $fileLoggerService
     * @param type $emailLoggerService
     * @param type $loggerType
     */
    public function __construct($smsLoggerService, $fileLoggerService, $emailLoggerService, $loggerType)
    {
        $this->smsLoggerService = $smsLoggerService;
        $this->fileLoggerService = $fileLoggerService;
        $this->emailLoggerService = $emailLoggerService;
        $this->loggerType = $loggerType;
    }

    /**
     * 
     * @param type $type
     * @param type $title
     * @return type
     */
    public function logEvent($type, $title)
    {
        return $this->whatLogger()->logEvent($type, $title);
    }
            
    /**
     * 
     * @param type $type
     */
    public function showAllEvents($type)
    {
        $this->whatLogger()->showAllEvents($type);
    }
    
    /**
     * 
     * @param type $id
     */
    public function getEvent($id)
    {
        $this->whatLogger()->getEvent($id);
    }
    
    /**
     * 
     * @return type
     */
    public function whatLogger()
    {
        switch ($this->loggerType) {
            case "SMSLogger":
                return $this->smsLoggerService;
            case "FileLogger":
                return $this->fileLoggerService;
            case "EmailLogger":
                return $this->emailLoggerService;    
        }
    }    
}