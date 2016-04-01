<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use AppBundle\Library\Base\BaseController;

/**
 * FOSRestBundle default annotations:
 * http://symfony.com/doc/master/bundles/FOSRestBundle/annotations-reference.html
 * 
 */
class ExceptionController extends BaseController
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
     * @Route("get/app")
     * @View()
     * 
     * @return array
     */    
    public function showAction()
    {
        throw new HttpException(400, 'Error in this API');
    }
}