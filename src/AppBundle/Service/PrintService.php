<?php


namespace AppBundle\Service;

/**
 * Description of Printer service
 *
 * @author nesa
 */
class PrintService
{
    public function __construct($hpPrinterService, $brotherPrinterService, $printerType)
    {
        $this->hpPrinterService = $hpPrinterService;
        $this->brotherPrinterService = $brotherPrinterService;
        $this->printerType = $printerType;
    }

    public function printText($text)
    {
        switch ($this->printerType) {
            case "HP":
                return $this->hpPrinterService->printText($text);
            case "Brother":
                return $this->brotherPrinterService->printText($text);
        }
    }
}
