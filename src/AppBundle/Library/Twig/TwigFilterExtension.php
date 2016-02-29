<?php

namespace AppBundle\Library\Twig;

use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use \Twig_Extension;

/**
 *
 * @author nesa mouzehkesh
 */
class TwigFilterExtension extends Twig_Extension
{
    const ICON_TEMPLATE = '<span class="%s %s"></span>';
    const ALERT_TEMPLATE = '<div class="alert alert-%s alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>%s</div>';
        
    /** 
     * 
     * @var Translator  
     */
    protected $translator;
    
    /**
     * Resolve dependencies
     * 
     * @param Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }  
    
    /**
     * 
     * @return type
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter(
                'icon', 
                array($this, 'icon'), 
                array('is_safe' => array('html'))
                ),
            new \Twig_SimpleFilter(
                'mapTo', 
                array($this, 'mapTo')
                ),
            new \Twig_SimpleFilter(
                'showAlert', 
                array($this, 'showAlert'), 
                array('is_safe' => array('html'))
                ),            
            );
    }
    
    /**
     * Create Bootstrap icon
     * 
     * @param type $icon
     * @return type
     */
    public function icon($icon, $extraClass = '')
    {
        return sprintf(
            self::ICON_TEMPLATE, 
            $this->translator->trans($icon), 
            $extraClass
            );
    }
    
    /**
     * 
     * @param type $uri
     * @return type
     */
    public function mapTo($index, array $from)
    {
        $total = count($from);
        if ($total > 0) {
            $key = ($index % $total) - 1;
            if (array_key_exists($key, $from)) {
                return $from[$key];
            }
        }
        
        return null;
    }
    
    /**
     * Show an Alert
     * 
     * @param type $alert
     * @param type $alertType
     * @return type
     */
    public function showAlert($alert, $alertType)
    {
        return sprintf(
            self::ALERT_TEMPLATE, 
            $alertType, 
            $this->translator->trans($alert)
            );
    }
    
    /**
     * 
     * @return string
     */
    public function getName()
    {
        return 'app.twig.filter.service';
    }     
}