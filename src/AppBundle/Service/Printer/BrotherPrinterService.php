<?php

namespace AppBundle\Service\Printer;

/**
 *
 * @author nesa
 */
class BrotherPrinterService 
{  
    public function printText($text)
    {
        return  $text . ' printed by: Brother printer';
    }
}
