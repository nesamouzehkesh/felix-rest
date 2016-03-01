<?php

namespace PageBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Library\Base\BaseController;
use PageBundle\Form\Type\PageType;

class PageAdminController extends BaseController
{
    /**
     * @Route("/", name="admin_page_index")
     */
    public function indexAction()
    {
        // Get a query of listing all pages from page service
        $pages = $this->get('app.page.service')->getPages();
        
        // Get pagination
        $pagination = $this->get('app.service')->paginate($pages);
        
        // Render view and then generate a Response object and return it
        return $this->render(
            '::admin/page/pages.html.twig', 
            array(
                'pagination' => $pagination,
                )
            );
    }
    
    /**
     * @Route("/add/page", defaults={"id" = null}, name="admin_page_add")
     * @Route("/edit/page/{id}", name="admin_page_edit")
     */  
    public function addEditPageAction(Request $request, $id)
    {
        // Get a page from page service. 
        // If $id is null then it will returns a new page object
        $page = $this->get('app.page.service')->getPage($id);

        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            // Use saveEntity function in app.service to save this entity
            $this->get('app.service')->saveEntity($page);
            
            return $this->redirectToRoute('admin_page_index');
        }
        
        return $this->render('::admin/page/addEdit.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    /**
     * @Route("/delete/page/{id}", name="admin_page_delete")
     */  
    public function deletePageAction($id)
    {
        // Get a page from page service. 
        $page = $this->get('app.page.service')->getPage($id);
        
        // Use deleteEntity function in app.service to delete this entity        
        $this->get('app.service')->deleteEntity($page);
        
        return $this->redirectToRoute('admin_page_index');        
    }
}