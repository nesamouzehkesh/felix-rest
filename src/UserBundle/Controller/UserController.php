<?php

namespace UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use UserBundle\Entity\User;
use UserBundle\Form\UserType;

class UserController extends BaseController
{
    /**
     * Display all users in the user main page
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     * @Route("/", name="nesa_admin_user_home")
     */
    public function displayUsersAction(Request $request)
    {
        // Get ObjectManager
        $em = $this->getDoctrine()->getManager();
        
        // Get all Users
        $usersQuery = User::getRepository($em)->findAllUsers();

        $paginator  = $this->get('knp_paginator');
        $usersPagination = $paginator->paginate(
            $usersQuery, /* query NOT result */
            $request->query->getInt('page', 1),
            5
        );        
        
        // Render and return the view
        return $this->render(
            'UserBundle:User:users.html.twig',
            array(
                'usersPagination' => $usersPagination
                )
            );
    }
    
    /**
     * 
     * @return type
     * @Route("/myprofile", name="nesa_admin_myprofile")
     */
    public function myprofileAction()
    {
        $user = $this->getAppService()->getUser();
        
        return $this->render(
            'UserBundle:User:myprofile.html.twig', 
            array('user' => $user)
            );
    }
        
    /**
     * Display a user
     * 
     * @param type $userId
     * @return type
     * @Route("/display/{userId}", name="nesa_admin_user_display")
     */
    public function displayUserAction($userId)
    {
        try {
            // Get ObjectManager
            $user = $this->getUserService()->getUser($userId);
            $userActivities = $this->getUserService()->getUserActivities($user);

            $view = $this->renderView(
                'UserBundle:User:user.html.twig',
                array(
                    'user' => $user,
                    'userActivities' => $userActivities
                    )
                );

            return $this->getAppService()->getJsonResponse(true, null, $view);        
        } catch (\Exception $ex) {
            return $this->getExceptionResponse('Can not display this user', $ex);
        }  
    }
    
    /**
     * Display and handel add edit user action
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     * @Route("/edit/{userId}", name="nesa_admin_user_edit")
     * @Route("/add", name="nesa_admin_user_add", defaults={"id" = null})
     */
    public function addEditUserAction(Request $request, $userId = null)
    {
        try {
            // Get user object
            $user = $this->getUserService()->getUser($userId);
            $em = $this->getDoctrine()->getManager();
            
            // Generate User Form
            $userForm = $this->createForm(
                new UserType, 
                $user,
                array(
                    'action' => $request->getUri(),
                    'method' => 'post'
                    )
                );

            $userForm->handleRequest($request);
            // If form is submited and it is valid then add or update this $user
            if ($userForm->isValid()) {
                $result = $this->getUserService()->userFormIsValid($userForm);
                if (true !== $result) {
                    return $this->getAppService()->getJsonResponse(false, $result);
                }
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                return $this->getAppService()->getJsonResponse(true);
            }

            $view = $this->renderView(
                'UserBundle:User:addEditUser.html.twig', 
                array(
                    'form' => $userForm->createView(),
                    )
                );

            return $this->getAppService()->getJsonResponse(true, null, $view);
        } catch (\Exception $ex) {
            return $this->getExceptionResponse('Can not add or edit user', $ex);
        }         
    }
    
    /**
     * Delete a user
     * 
     * @param type $userId
     * @return type
     * @Route("/delete/{userId}", name="nesa_admin_user_delete")
     */
    public function deleteUserAction($userId)
    {
        try {
            // Get user
            $user = $this->getUserService()->getUser($userId);

            // Get ObjectManager
            $em = $this->getDoctrine()->getManager();
            // Remove user and flush ObjectManager. Note: if this $user is used
            // running the following code will throw an exception. Before delete this 
            // object we need to be sure that it is not in any other places.
            $em->remove($user);
            $em->flush();

            return $this->getAppService()->getJsonResponse(true);
        } catch (\Exception $ex) {
            return $this->getExceptionResponse(
                'alert.error.canNotDeleteItem', 
                $ex
                );
        }        
    }
    
    /**
     * 
     * @return \UserBundle\Service\UserService
     */
    private function getUserService()
    {
        return $this->get('saman.user_user');
    }
}
