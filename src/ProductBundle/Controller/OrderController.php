<?php

namespace ProductBundle\Controller;

use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Library\Base\BaseController;
use ProductBundle\Entity\Order;
use ProductBundle\Entity\OrderDetail;

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
        $em = $this->getDoctrine()->getManager();
        // Get front end data
        $data = $request->request->get('order');
        
        // Create a new Order object
        $order = new Order();
        $order->setName($data['name']);
        $order->setStreet($data['street']);
        $order->setCity($data['city']);
        $order->setState($data['state']);
        $order->setCountry($data['country']);
        $order->setZip($data['zip']);
        $order->setCountry($data['country']);
        $order->setGiftWrap($data['giftwrap']);
        // Persist $order
        $em->persist($order);
        
        foreach ($data['products'] as $productData) {
            // Get a refrence to product entity. Note: that $product is not a 
            // Product object
            $product = $this->getDoctrine()
                ->getManager()
                ->getReference('ProductBundle:Product', $productData['id']);
            
            // Create a new OrderDetail object
            $orderDetail = new OrderDetail();
            $orderDetail->setCount($productData['count']);
            $orderDetail->setOrder($order);
            $orderDetail->setProduct($product);
            
            // Add this $orderDetail to $order
            $order->addOrderDetail($orderDetail);
            
            // Persist $orderDetail
            $em->persist($orderDetail);
        }
        $em->flush();
        
        return array();
    }
}
