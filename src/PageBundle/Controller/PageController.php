<?php

namespace PageBundle\Controller;

use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Library\Base\BaseController;
use PageBundle\Form\Type\PageType;
use AppBundle\Library\Serializer\AngularSchemaFormSerializer\FormSerializer;

/**
 * Getting Started With FOSRestBundle 
 * http://symfony.com/doc/master/bundles/FOSRestBundle/index.html
 * https://github.com/gimler/symfony-rest-edition/blob/2.7/src/AppBundle/Controller/NoteController.php
 * 
 */
class PageController extends BaseController
{
    /**
     * @ApiDoc(
     *   resource = true,
     *   requirements={},
     *   statusCodes = {}
     * )
     * 
     * @Annotations\View()
     * 
     * @return array
     */
    public function getPagesAction()
    {
        // Get a query of listing all pages from page service
        $pages = $this->get('app.page.service')->getPages(false);
        
        return $pages;
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   requirements={},
     *   statusCodes = {}
     * )
     * 
     * @Annotations\View()
     * 
     * @return array
     */ 
    public function getPageAction($id)
    {
        // Get a page from page service. 
        $page = $this->get('app.page.service')->getPage($id);
        
        return $page;
    }    
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   requirements={},
     *   statusCodes = {}
     * )
     * 
     * @Annotations\View()
     * 
     * @return string
     */
    public function getPageFormAction($id)
    {
        $page = $this->get('app.page.service')->getPage($id);
        $form = $this->createForm(PageType::class, $page);
        
        return FormSerializer::serialize($form);
    }
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   requirements={},
     *   statusCodes = {}
     * )
     * 
     * @Annotations\View()
     * 
     * @return array
     */ 
    public function deletePageAction($id)
    {
        // Get a page from page service. 
        $page = $this->get('app.page.service')->getPage($id);
        
        // Use deleteEntity function in app.service to delete this entity        
        $this->get('app.service')->deleteEntity($page);
        
        // There is a debate if this should be a 404 or a 204
        // see http://leedavis81.github.io/is-a-http-delete-requests-idempotent/
        return $this->routeRedirectView(
            'get_pages', 
            array(), 
            Response::HTTP_NO_CONTENT
            );
    }
}