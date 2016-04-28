<?php

namespace ProductBundle\Controller;

use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Library\Base\BaseController;
use ProductBundle\Entity\Order;

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
        $data = $request->request->get('order');
        $order = new Order();
        $order->setName($data['name']);
        $order->setStreet($data['street']);
        $order->setCity($data['city']);
        $order->setZip($data['zip']);
        $order->setCountry($data['country']);
        $order->setGiftWrap($data['giftWrap']);
        
        foreach ($data['products'] as $productData) {
            $product = $this->getDoctrine()
            ->getManager()
            ->getReference('ProductBundle:Product', $productData['id']);
            $order->addProduct($product);
}

        $em->persist($order);
        $em->flush();
        
        }
} /* ini ke backend mideee object nist etelaate order hast
ina bayad convert koni be order */
