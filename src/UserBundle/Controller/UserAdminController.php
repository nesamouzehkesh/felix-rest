<?php

namespace UserBundle\Controller;

use Symfony\Component\Form\Form;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Library\Base\BaseController;
use UserBundle\Entity\User;
use UserBundle\Form\UserType;

class UserAdminController extends BaseController
{
    /**
     * Display all users in the user main page
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     * 
     * @Route("/", name="admin_user_index")
     */
    public function indexAction()
    {
        // Get a query of listing all users from user service
        $pages = $this->get('app.user.service')->getUsers();
        
        // Get pagination
        $pagination = $this->get('app.service')->paginate($pages);
        
        // Render and return the view
        return $this->render(
            '::admin/user/users.html.twig',
            array(
                'pagination' => $pagination
                )
            );
    }
    
    /**
     * 
     * @return type
     * @Route("/myprofile", name="admin_user_myprofile")
     */
    public function myprofileAction()
    {
        $user = $this->getUser();
        
        return $this->render(
            '::admin/user/myprofile.html.twig', 
            array('user' => $user)
            );
    }
        
    /**
     * Display a user
     * 
     * @param type $userId
     * @return type
     * @Route("/view/user{userId}", name="admin_user_view")
     */
    public function viewUserAction($userId)
    {
        try {
            // Get User object
            $user = $this->getUserService()->getUser($userId);

            // Generate a view 
            $view = $this->renderView(
                '::admin/user/user.html.twig',
                array(
                    'user' => $user,
                    )
                );
            
            // Returning a Jason response
            return $this->getAppService()->getJsonResponse(true, null, $view);        
        } catch (\Exception $ex) {
            // Catch Exceptions and return Json Exception Response
            return $this->getAppService()->getExceptionResponse(
                'Can not display this user', 
                $ex
                );
        }  
    }
    
    /**
     * Display and handel add edit user action
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     * @Route("/edit/user/{userId}", name="admin_user_edit")
     * @Route("/add/user", name="admin_user_add", defaults={"id" = null})
     */
    public function addEditUserAction(Request $request, $userId = null)
    {
        try {
            // Get user object
            $user = $this->getUserService()->getUser($userId);
            $em = $this->getDoctrine()->getManager();
            
            // Generate User Form
            $userForm = $this->createForm(
                UserType::class, 
                $user,
                array(
                    'action' => $request->getUri(),
                    'method' => 'post'
                    )
                );

            $userForm->handleRequest($request);
            // If form is submited and it is valid then add or update this $user
            if ($userForm->isValid()) {
                // Best practice is to return a form with error
                $result = $this->userFormIsValid($userForm);
                if (true !== $result) {
                    return $this->getAppService()->getJsonResponse(false, $result);
                }
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                return $this->getAppService()->getJsonResponse(true);
            }
            
            // Generate a view 
            $view = $this->renderView(
                '::admin/user/addEditUser.html.twig', 
                array(
                    'form' => $userForm->createView(),
                    )
                );
            
            // Returning a Jason response
            return $this->getAppService()->getJsonResponse(true, null, $view);
        } catch (\Exception $ex) {
            // Catch Exceptions and return Json Exception Response
            return $this->getAppService()->getExceptionResponse(
                'Can not add or edit user', 
                $ex
                );
        }         
    }
    
    /**
     * Delete a user
     * 
     * @param type $userId
     * @return type
     * @Route("/delete/{userId}", name="admin_user_delete")
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
            return $this->getAppService()->getExceptionResponse(
                'alert.error.canNotDeleteItem', 
                $ex
                );
        }        
    }
    
    /**
     * 
     * @param Form $userForm
     * @return string|boolean
     */
    private function userFormIsValid(Form $userForm)
    {
        $em = $this->getAppService()->getEntityManager();
        $user = $userForm->getData();
        $userName = $user->getUsername();

        // Check the username
        if (!User::getRepository($em)->canUserUseUsername($user, $userName)) {
            return 'This username is already used';
        }

        if ($userForm->has('changePassword')) {
            if ($userForm->get('changePassword')->getData()) {
                // Check password and rePassword
                $password = $userForm->get('password')->getData();
                $rePassword = $userForm->get('rePassword')->getData();
                if ($password !== $rePassword) {
                    return 'Passwords are no matched';
                }
                // Check user current password
                if ($userForm->has('currentPassword')) {
                    $currentPassword = $userForm->get('currentPassword')->getData();
                    if ($user->getPassword() !== md5($currentPassword)) {
                        return 'Current passwords is not valid';
                    }
                }
                // Set this $password for user password
                $user->setPassword($password);
            }
        } else {
            // Check password and rePassword
            $password = $userForm->get('password')->getData();
            $rePassword = $userForm->get('rePassword')->getData();
            if ($password !== $rePassword) {
                return 'Passwords are no matched';
            }
            
            // Set this $password for user password
            $user->setPassword($password);
        }
        
        return true;
    }    
    
    /**
     * 
     * @return \UserBundle\Service\UserService
     */
    private function getUserService()
    {
        return $this->get('app.user.service');
    }    
}
