<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Library\Base\BaseController;

class AppApiController extends BaseController
{
    /**
     * Get flash bag message view
     * 
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/app/api/flashbag", name="api_app_flashbag")
     */
    public function displayFlashBagAction()
    {
        // Get flash Bag
        $flashBag = $this->getService('session')->getFlashBag();
        
        // Get message groups in this $flashBag
        $messageGroups = $flashBag->all();
        
        // Generate the view for these $messageGroups
        $view = $this->renderView(
            '::admin/app/flashBag.html.twig', 
            array(
                'messageGroups' => $messageGroups
            ));
        
        // Return a jason response
        return $this->getJsonResponse(true, null, $view);
    }
}