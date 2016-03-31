<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Route;
use AppBundle\Library\Base\BaseController;

/**
 * FOSRestBundle default annotations:
 * http://symfony.com/doc/master/bundles/FOSRestBundle/annotations-reference.html
 * 
 */
class AppController extends BaseController
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
     * 
     * @Route("get/app")
     * @View()
     */    
    public function getAppAction()
    {
        return array('app' => 'RestFull App');
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
     * 
     * If we don't explicitly define the method of this api action the method 
     * will resolve based on the action name for example deleteFlashBagAction will
     * be resolved to DELETE method
     * @Delete
     * @View()
     */    
    public function deleteAppAction()
    {
        return array('App is deleted');
    }
}