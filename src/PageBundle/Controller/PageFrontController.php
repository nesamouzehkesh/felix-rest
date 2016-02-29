<?php

namespace PageBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Library\Base\BaseController;

class PageFrontController extends BaseController
{
    /**
     * @Route("/", name="page_front_index")
     */
    public function indexAction(Request $request)
    {
        return $this->render('::front/page/index.html.twig');
    }
}