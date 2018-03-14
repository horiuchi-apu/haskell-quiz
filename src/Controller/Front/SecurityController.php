<?php

namespace App\Controller\Front;

use App\Form\UserLoginType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @param AuthenticationUtils $helper
     * @return Response
     * @Route("/login", name="front_login")
     */
    public function login(AuthenticationUtils $helper)
    {
        $error = $helper->getLastAuthenticationError();
        $lastUsername = $helper->getLastUsername();

        $form = $this->createForm(UserLoginType::class, [
            'username' => $lastUsername]
        );

        return $this->render("Front/security/login.html.twig", [
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }

    /**
     * @Route("/login_check", name="front_login_check")
     * @Method("POST")
     */
    public function check()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    /**
     * @Route("/logout", name="front_logout")
     */
    public function logoutAction()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }
}
