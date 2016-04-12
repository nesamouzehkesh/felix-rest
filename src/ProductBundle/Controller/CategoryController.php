<?php

namespace ProductBundle\Controller;

use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Library\Base\BaseController;

class CategoryController extends BaseController
{
    /**
     * Get all categories
     * Route name "get_categories" [GET] /categories
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
   
    
    public function getCategoriesAction()
    {
        $categories = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('ProductBundle:Category')
            ->getCategories();
        
        return $categories;
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
    public function getCategoryAction($id)
    {
        try {
            $category = $this
                ->getDoctrine()
                ->getEntityManager()
                ->getRepository('ProductBundle:Category')
                ->getProduct($id);

            return $category;
        }   catch (\Exception $ex) {
            throw new HttpException(
                Response::HTTP_BAD_REQUEST, 
                'No Product is found'
                );
        }
    }
    
    /**
     * "delete_category"
     * [DELETE] /categories/{id}
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
    public function deleteCategoryAction($id)
    {
        // Get a page from page service. 
        $category = $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository('ProductBundle:Category')
            ->find($id);

        // Use deleteEntity function in app.service to delete this entity        
        $this->get('app.service')->deleteEntity($category);

        // There is a debate if this should be a 404 or a 204
        // see http://leedavis81.github.io/is-a-http-delete-requests-idempotent/
        return $this->routeRedirectView(
            'get_categories', 
            array(), 
            Response::HTTP_NO_CONTENT
            );
    }    
}