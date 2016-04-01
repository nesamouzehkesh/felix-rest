<?php

namespace ProductBundle\Controller;

use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Library\Base\BaseController;

class ProductController extends BaseController
{
    /**
     * Get all products
     * Route name "get_products" [GET] /products
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
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing entities.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="10", description="How many entities to return.")
     * 
     * @return array
     */
    public function getProductsAction()
    {
        $products = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('ProductBundle:Product')
            ->getProducts();
        
        return array(
            'products' => $products,
            'total' => count($products),
            );
    }
    
    /**
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
     * @Annotations\View()
     * 
     * @param int $id
     * @return array
     */
    public function getProductAction($id)
    {
        try {
            $product = $this
                ->getDoctrine()
                ->getEntityManager()
                ->getRepository('ProductBundle:Product')
                ->getProduct($id);

            return $product;
        } catch (\Exception $ex) {
            throw new HttpException(
                Response::HTTP_BAD_REQUEST, 
                'No Product is found'
                );
        }
    }
    
    /**
     * "delete_product"
     * [DELETE] /products/{id}
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
     * @Annotations\View()
     * 
     * @param int $id
     * @return array
     */ 
    public function deleteProductAction($id)
    {
        // Get a page from page service. 
        $product = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('ProductBundle:Product')
            ->find($id);

        // Use deleteEntity function in app.service to delete this entity        
        $this->get('app.service')->deleteEntity($product);

        // There is a debate if this should be a 404 or a 204
        // see http://leedavis81.github.io/is-a-http-delete-requests-idempotent/
        return $this->routeRedirectView(
            'get_products', 
            array(), 
            Response::HTTP_NO_CONTENT
            );
    }    
}
