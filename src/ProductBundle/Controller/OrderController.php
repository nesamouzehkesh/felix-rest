<?php

namespace ProductBundle\Controller;

use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Library\Base\BaseController;

/**
 * Getting Started With FOSRestBundle 
 * http://symfony.com/doc/master/bundles/FOSRestBundle/index.html
 * https://github.com/gimler/symfony-rest-edition/blob/2.7/src/AppBundle/Controller/NoteController.php
 * 
 */
class OrderController extends BaseController
{
    /**
     * Post orders
     * Route name "post_order" [POST] /orders
     * 
     * @ApiDoc(
     *   resource = true,
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
     * @Annotations\View()
     * 
     * @return array
     */
    public function postOrderAction(Request $request)
    {
        $order = $request->request->get('order');
        
        return array();
    }
}
