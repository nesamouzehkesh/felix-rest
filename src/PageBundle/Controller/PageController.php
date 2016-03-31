<?php

namespace PageBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Route;
use AppBundle\Library\Base\BaseController;
use PageBundle\Form\Type\PageType;
use AppBundle\Library\Serializer\AngularSchemaFormSerializer\FormSerializer;

class PageController extends BaseController
{
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Get all products",
     *   requirements={
     *     {
     *       "name"="page",
     *       "dataType"="integer",
     *       "requirement"="\d+",
     *       "description"="The page"
     *     }
     *   },
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     403 = "Returned when the product is not authorized to say hello",
     *     404 = {
     *       "Returned when the product is not found",
     *       "Returned when something else is not found"
     *     }
     *   }
     * )
     * 
     * @return array
     * @View()
     */
    public function indexAction()
    {
        // Get a query of listing all pages from page service
        $pages = $this->get('app.page.service')->getPages(false);
        
        return $pages;
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Get all products",
     *   requirements={
     *     {
     *       "name"="page",
     *       "dataType"="integer",
     *       "requirement"="\d+",
     *       "description"="The page"
     *     }
     *   },
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     403 = "Returned when the product is not authorized to say hello",
     *     404 = {
     *       "Returned when the product is not found",
     *       "Returned when something else is not found"
     *     }
     *   }
     * )
     * 
     * @return array
     * @View()
     */ 
    public function getAction($id)
    {
        // Get a page from page service. 
        $page = $this->get('app.page.service')->getPage($id);
        
        return $page;
    }    
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Get all products",
     *   requirements={
     *     {
     *       "name"="page",
     *       "dataType"="integer",
     *       "requirement"="\d+",
     *       "description"="The page"
     *     }
     *   },
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     403 = "Returned when the product is not authorized to say hello",
     *     404 = {
     *       "Returned when the product is not found",
     *       "Returned when something else is not found"
     *     }
     *   }
     * )
     * 
     * @return string
     * @View()
     */
    public function getFormAction($id)
    {
        $page = $this->get('app.page.service')->getPage($id);
        $form = $this->createForm(PageType::class, $page);
        
        return FormSerializer::serialize($form);
    }
    
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Get all products",
     *   requirements={
     *     {
     *       "name"="page",
     *       "dataType"="integer",
     *       "requirement"="\d+",
     *       "description"="The page"
     *     }
     *   },
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     403 = "Returned when the product is not authorized to say hello",
     *     404 = {
     *       "Returned when the product is not found",
     *       "Returned when something else is not found"
     *     }
     *   }
     * )
     * 
     * @return array
     * @View()
     */ 
    public function deleteAction($id)
    {
        // Get a page from page service. 
        $page = $this->get('app.page.service')->getPage($id);
        
        // Use deleteEntity function in app.service to delete this entity        
        $this->get('app.service')->deleteEntity($page);
        
        return array('Page is deleted');
    }
}