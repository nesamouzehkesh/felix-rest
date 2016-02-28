<?php

namespace CmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use CmsBundle\Entity\Page;
use CmsBundle\Form\Type\PageType;

class DefaultController extends Controller
{
    /**
     * @Route("/pages", name="nesa_admin_pages_home")
     */
    public function listPagesAction(Request $request)
    {
        $message = "Here is the list of some random pages of the book";
       
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('CmsBundle:Page')->getPages();
       
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1),
            5
        );
        
        return $this->render(
            'CmsBundle:Page:pages.html.twig', 
            array(
                'message' => $message,
                'pagination' => $pagination,
                )
            );
    }
    
    /**
     * @Route("/page/add", name="nesa_admin_pages_add")
     */  
    public function addPageAction(Request $request)
    {
        $page = new Page(); 

        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();
            
            if ($form->get('saveAndAdd')->isClicked()) {
                return $this->redirectToRoute('nesa_admin_pages_add');
            } else {
                return $this->redirectToRoute('nesa_admin_pages_home');
            }
        }
        
        return $this->render('CmsBundle:Page:pageAdd.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    /**
     * @Route("/page/edit/{pageId}", name="nesa_admin_pages_edit")
     */  
    public function editPageAction(Request $request, $pageId)
    {
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('CmsBundle:Page')->find($pageId);
        if (!$page) {
            throw $this->createNotFoundException('No page found for id ' . $pageId);
        }
        
        $form = $this->createForm(PageType::class, $page)
            ->add('title', 'Symfony\Component\Form\Extension\Core\Type\TextType')
            ->add('content', 'Symfony\Component\Form\Extension\Core\Type\TextareaType')
            ->add('url', 'Symfony\Component\Form\Extension\Core\Type\TextType')
            ->add('save', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array('label' => 'Edit'));
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();
            
            return $this->redirectToRoute('nesa_admin_pages_home');        
            
        }
        
        return $this->render(
            'CmsBundle:Page:editPage.html.twig', 
            array(
                'form' => $form->createView(),
                )
            );
    }
    
    /**
     * @Route("/page/delete/{pageId}", defaults={"pageId" = 1}, name="nesa_admin_pages_delete")
     */  
    public function deletePageAction($pageId)
    {
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('CmsBundle:Page')->find($pageId);
        
        $em->remove($page);
        $em->flush();
        
        return $this->redirectToRoute('nesa_admin_pages_home');        

    }
}