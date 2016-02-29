<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use AppBundle\Library\Component\UserAgentDetector;
use AppBundle\Library\Base\BaseController;

class SecurityController extends BaseController
{
    public function loginAction(Request $request, $isFront)
    {
        $userAgentDetector = new UserAgentDetector;
        if ($userAgentDetector->isCrawler()) {
            throw $this->createAccessDeniedException('Crawlers and Robots cannot access to this page');
        }
        
        $error = '';
        $session = $request->getSession();        
        // get the login error if there is one
        if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                Security::AUTHENTICATION_ERROR
            );
        } elseif (null !== $session && $session->has(Security::AUTHENTICATION_ERROR)) {
            $error = $session->get(Security::AUTHENTICATION_ERROR);
            $session->remove(Security::AUTHENTICATION_ERROR);
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(Security::LAST_USERNAME);
        
        if ($isFront) {
            return $this->render(
                '::front/app/login.html.twig',
                array(
                    'title' => 'word.logIn',
                    'last_username' => $lastUsername,
                    'action'        => 'saman_login_check_web',
                    'error'         => $error,
                    )
                );            
        } else {
            return $this->render(
                '::admin/app/login.html.twig',
                array(
                    'last_username' => $lastUsername,
                    'error'         => $error,
                )
            );
        }
    }
}
