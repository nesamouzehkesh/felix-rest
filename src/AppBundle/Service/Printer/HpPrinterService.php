<?php


namespace AppBundle\Service\Printer;

/**
 * Description of HpPrinter
 *
 * @author nesa
 */
class HpPrinterService 
{  

        public function printText($text)
            {
                
            return $text . ' printed by: HP printer';
            
            
            }
}

