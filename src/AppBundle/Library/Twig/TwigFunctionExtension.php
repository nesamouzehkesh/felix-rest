<?php

namespace AppBundle\Library\Twig;

use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use \Twig_Extension;

/**
 * TwigFunctionExtension
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
                $this->render('icon', $param['icon']),
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
                    $this->render('url', $navigationParam['url']),
                    $navigationParam['class'],
                    $navigationParam['action'],
                    $this->render('attr', $navigationParam['attr']),
                    $this->render('icon', $navigationParam['icon']),
                    $this->translator->trans($navigationParam['text'])
                    );
            } elseif ($navigationParam['active'] or null === $navigationParam['url']) {
                $breadcrumbItemsContent = $breadcrumbItemsContent . sprintf(
                    '<li class="active">%s%s</li>', 
                    $this->render('icon', $navigationParam['icon']),
                    $this->translator->trans($navigationParam['text'])
                    );
            } else {
                $breadcrumbItemsContent = $breadcrumbItemsContent . sprintf(
                    '<li><a href="%s" class="%s" %s>%s%s</a></li>', 
                    $navigationParam['url'],
                    $navigationParam['class'],
                    $this->render('attr', $navigationParam['attr']),
                    $this->render('icon', $navigationParam['icon']),
                    $this->translator->trans($navigationParam['text'])
                    );
            }
        }
        
        return sprintf(
            '<ol class="breadcrumb" %s>%s</ol>', 
            $this->render('attr', $param['attr']),
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
                'size' => null,
                'class' => '',
                'type' => 'link',
                'id' => null,
                'attr' => null
                ), 
            $externalParam
            );
        
        return sprintf(
            '<a %s href="%s" class="btn %s%s %s" %s>%s%s</a>', 
            $this->render('id', $param['id']),
            $param['url'],
            $this->render('btnType', $param['type']),
            $this->render('btnSize', $param['size']),
            $param['class'],
            $this->render('attr', $param['attr']),
            $this->render('icon', $param['icon']),
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
            . '<button %s %s type="button" class="btn %s %s %s %s" %s %s>'
                . '%s<span class="hidden-sm hidden-xs">%s</span>'
            . '</button>', 
            $this->render('url', $param['url']),
            $this->render('id', $param['id']),
            $this->render('btnType', $param['type']),
            $this->render('btnSize', $param['size']),
            $param['class'],
            $param['action'],
            $this->render('target', $param['target']),
            $this->render('attr', $param['attr']),
            $this->render('icon', $param['icon']),
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
     * @param type $type
     * @param type $value
     * @return string
     */
    private function render($type, $value)
    {
        if (null === $value) {
            return '';
        }
        
        switch ($type) {
            case 'target':
                return sprintf(' data-target="#%s"', $value);
            case 'btnType':
            case 'btnSize':
                return sprintf(' btn-%s', $value);
            case 'attr':
                $content = '';
                foreach ($value as $key => $value) {
                    $content = $content . sprintf(' %s="%s"', $key, $value);
                }
                return $content;
            case 'icon':
                return sprintf('<span class="%s"></span> ', $this->translator->trans($value));
            case 'id': 
                return sprintf(' id="%s"', $value);
            case 'url':
                return sprintf(' data-url="%s"', $value);
        }
        
        return '';
    }
}