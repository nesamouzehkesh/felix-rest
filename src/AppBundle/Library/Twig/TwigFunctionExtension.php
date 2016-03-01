<?php

namespace AppBundle\Library\Twig;

use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use \Twig_Extension;

/**
 *
 * @author Saman Shafigh - samanshafigh@gmail.com
 */
class TwigFunctionExtension extends Twig_Extension
{
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
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction(
                'modal', 
                array($this, 'modal'),
                array('is_safe' => array('html'))
                ),            
            new \Twig_SimpleFunction(
                'breadcrumb', 
                array($this, 'breadcrumb'),
                array('is_safe' => array('html'))
                ),            
            new \Twig_SimpleFunction(
                'link', 
                array($this, 'link'),
                array('is_safe' => array('html'))
                ),
            new \Twig_SimpleFunction(
                'button', 
                array($this, 'button'),
                array('is_safe' => array('html'))
                ),
            );
    }
    
    /**
     * 
     * @param type $id
     * @param type $externalParam
     * @return type
     */
    public function modal($id, $externalParam = array())
    {
        $param = array_merge(
            array(
                'description' => null,
                'title' => null,
                'icon' => null
                ), 
            $externalParam
            );        
        
        $title = '';
        if (null !== $param['title']) {
            $title = sprintf(
                '<h4 class="modal-title" id="myModalLabel">%s%s</h4>',
                $this->getIcon($param['icon']),
                $param['title']
                );
        }
        
        $modalHeader = sprintf(''
            . '<div class="modal-header">'
                . '<button type="button" class="close" data-dismiss="modal">'
                    . '<span aria-hidden="true">&times;</span><span class="sr-only">%s</span>'
                . '</button>%s'
            . '</div>',
            $this->translator->trans('action.close'),
            $title
            );
        
        return sprintf(''
            . '<div class="modal fade" id="%s" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'
                . '<div class="modal-dialog">'
                    . '<div class="modal-content">'
                        . '%s'
                        . '<div class="modal-body-content">'
                            . '<div class="modal-body"></div>'
                            . '<div class="modal-footer"></div>'
                        . '</div>'
                    . '</div>'
                . '</div>'
            . '</div>',
            $id,
            $modalHeader
            );
    }

    /**
     * 
     * @param type $navigations
     * @param type $externalParam
     * @return type
     * @throws \Exception
     */
    public function breadcrumb(array $navigations, $externalParam = array())
    {
        $param = array_merge(
            array(
                'class' => '',
                'id' => null,
                'attr' => null
                ), 
            $externalParam
            );
        
        $breadcrumbItemsContent = '';
        foreach ($navigations as $navigationParam) {
            $navigationParam = array_merge(
                array(
                    'class' => '',
                    'url' => null,
                    'text' => 'NULL',
                    'attr' => null,
                    'action' => null,
                    'icon' => null,
                    'active' => false
                    ), 
                $navigationParam
                );
            
            if (null !== $navigationParam['action']) {
                $breadcrumbItemsContent = $breadcrumbItemsContent . sprintf(
                    '<li><a href="#" %s class="%s %s" %s>%s%s</a></li>', 
                    $this->getUrl($navigationParam['url']),
                    $navigationParam['class'],
                    $navigationParam['action'],
                    $this->getAttr($navigationParam['attr']),
                    $this->getIcon($navigationParam['icon']),
                    $this->translator->trans($navigationParam['text'])
                    );
            } elseif ($navigationParam['active'] or null === $navigationParam['url']) {
                $breadcrumbItemsContent = $breadcrumbItemsContent . sprintf(
                    '<li class="active">%s%s</li>', 
                    $this->getIcon($navigationParam['icon']),
                    $this->translator->trans($navigationParam['text'])
                    );
            } else {
                $breadcrumbItemsContent = $breadcrumbItemsContent . sprintf(
                    '<li><a href="%s" class="%s" %s>%s%s</a></li>', 
                    $navigationParam['url'],
                    $navigationParam['class'],
                    $this->getAttr($navigationParam['attr']),
                    $this->getIcon($navigationParam['icon']),
                    $this->translator->trans($navigationParam['text'])
                    );
            }
        }
        
        return sprintf(
            '<ol class="breadcrumb" %s>%s</ol>', 
            $this->getAttr($param['attr']),
            $breadcrumbItemsContent
            );
    }
    
    /**
     * externalParam:
     *   {
     *      url: string,
     *      icon: string,
     *      size: null|xs|sm|lg
     *      class: string,
     *      id: string, 
     *      attr: {}
     *   }
     * 
     * @param type $url
     * @param type $text
     * @param type $clientParameters
     * @return type
     */
    public function link($text, $externalParam = array())
    {
        $param = array_merge(
            array(
                'url' => null,
                'icon' => null,
                'size' => 'xs',
                'class' => '',
                'id' => null,
                'attr' => null
                ), 
            $externalParam
            );
        
        return sprintf(
            '<a %s href="%s" class="%s %s" %s>%s%s</a>', 
            $this->getId($param['id']),
            $param['url'],
            $this->getBtnSize($param['size']),
            $param['class'],
            $this->getAttr($param['attr']),
            $this->getIcon($param['icon']),
            $this->translator->trans($text)
            );
    }
    
    /**
     * externalParam:
     *   {
     *      url: string,
     *      icon: string,
     *      size: null|xs|sm|lg
     *      toggle: string,
     *      action: string,
     *      class: string,
     *      type: default|primary|success|info|warning|danger|link
     *      id: string,
     *      attr: {}
     *   }
     * 
     * @param type $url
     * @param type $text
     * @param type $clientParameters
     * @return type
     */
    public function button($text, $externalParam = array())
    {
        $param = array_merge(
            array(
                'url' => null,
                'icon' => null,
                'size' => null,
                'target' => null,
                'action' => '',
                'class' => '',
                'type' => 'link',
                'id' => null,
                'attr' => array()
                ), 
            $externalParam
            );

        return sprintf(''
            . '<button %s %s type="button" class="btn btn-%s %s %s %s" %s %s>'
                . '%s<span class="hidden-sm hidden-xs">%s</span>'
            . '</button>', 
            $this->getUrl($param['url']),
            $this->getId($param['id']),
            $param['type'],
            $this->getBtnSize($param['size']),
            $param['class'],
            $param['action'],
            $this->getTarget($param['target']),
            $this->getAttr($param['attr']),
            $this->getIcon($param['icon']),
            $this->translator->trans($text)
            );
    }

    /**
     * 
     * @return string
     */
    public function getName()
    {
        return 'app.twig.function.service';
    }
    
    /**
     * 
     * @param type $url
     * @return type
     */
    private function getUrl($url)
    {
        $content = '';
        if (null !== $url) {
            $content = sprintf(
                'data-url="%s"', 
                $url
                );
        }
        
        return $content;
    }
    
    /**
     * 
     * @param type $id
     * @return type
     */
    private function getId($id)
    {
        $content = '';
        if (null !== $id) {
            $content = sprintf('id="%s"', $id);
        }
        
        return $content;
    }
    
    /**
     * 
     * @param type $icon
     * @return type
     */
    private function getIcon($icon)
    {
        $content = '';
        if (null !== $icon) {
            $content = sprintf(
                '<span class="%s"></span> ', 
                $this->translator->trans($icon)
                );
        }
        
        return $content;
    }
    
    /**
     * 
     * @param type $attr
     * @return string
     */
    private function getAttr($attr)
    {
        $content = '';
        if (is_array($attr)) {
            foreach ($attr as $key => $value) {
                $content = $content . sprintf(' %s="%s"', $key, $value);
            }
        }
        
        return $content;
    }
    
    /**
     * 
     * @param type $size
     * @return type
     */
    private function getBtnSize($size)
    {
        $content = '';
        if (null !== $size) {
            $content = sprintf('btn-%s', $size);
        }
        
        return $content;
    }
    
    /**
     * 
     * @param type $target
     * @return type
     */
    private function getTarget($target)
    {
        $content = '';
        if (null !== $target) {
            $content = sprintf(
                'data-target="#%s"', 
                $target
                );
        }
        
        return $content;
    }
}