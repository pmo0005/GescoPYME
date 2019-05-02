<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\Exception\LockedException;
use Symfony\Component\Security\Core\Security;

class AdminLoginController extends Controller
{
    public function indexAction(Request $request)
    {
        $session = $request->getSession();
        $error = $this->getLoginErrorFromSession($session);
        $lastUsername = $session->get(Security::LAST_USERNAME);

        return $this->render('@App/AdminLogin/index.html.twig', array(
            "companies" => $this->getDoctrine()->getRepository("AppBundle:Company")->findAll(),
            "error" => $error,
            "last_username" => $lastUsername,
        ));
    }

    public function securityCheckAction () {

    }

    public function logoutAction () {

    }

    private function getLoginErrorFromSession (Session $session) {
        if (!$session->has(Security::AUTHENTICATION_ERROR)) {
            return null;
        }

        $exception = $session->get(Security::AUTHENTICATION_ERROR);
        if ($exception instanceof DisabledException) { return "d"; }
        elseif ($exception instanceof LockedException) { return "l"; }
        elseif ($exception instanceof AccountExpiredException) { return "e"; }
        elseif ($exception instanceof BadCredentialsException) { return "bc"; }
        else { return "g"; }
    }
}
