<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use AppBundle\Library\Component\UserAgentDetector;

class SecurityController extends BaseController
{
    public function loginAction(Request $request, $isWeb)
    {
        $userAgentDetector = new UserAgentDetector;
        if ($userAgentDetector->isCrawler()) {
            throw $this->createAccessDeniedException('Crawlers and Robots cannot access to this page');
        }
        
        $session = $request->getSession();        
        // get the login error if there is one
        if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                Security::AUTHENTICATION_ERROR
            );
        } elseif (null !== $session && $session->has(Security::AUTHENTICATION_ERROR)) {
            $error = $session->get(Security::AUTHENTICATION_ERROR);
            $session->remove(Security::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(Security::LAST_USERNAME);
        
        if ($isWeb) {
            return $this->render(
                '::web/login.html.twig',
                array(
                    'title' => 'word.logIn',
                    'last_username' => $lastUsername,
                    'action'        => 'saman_login_check_web',
                    'error'         => $error,
                    )
                );            
        } else {
            return $this->render(
                'AppBundle:Security:login.html.twig',
                array(
                    // last username entered by the user
                    'last_username' => $lastUsername,
                    'error'         => $error,
                )
            );
        }
    }
}
