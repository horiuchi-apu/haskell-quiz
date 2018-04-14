<?php

namespace App\Controller\Admin;

use App\Form\AdminLoginType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 * @package App\Controller\Admin
 * @Route("/admin")
 */
class SecurityController extends Controller
{
    /**
     * @param AuthenticationUtils $helper
     * @return Response
     * @Route("/login", name="admin_login")
     */
    public function login(AuthenticationUtils $helper)
    {
        $error = $helper->getLastAuthenticationError();
        $lastUsername = $helper->getLastUsername();

        $form = $this->createForm(AdminLoginType::class, [
            'username' => $lastUsername]
        );

        if ($error) {
            $this->addFlash('danger', $error->getMessage());
        }

        return $this->render("Admin/security/login.html.twig", [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/login_check", name="admin_login_check")
     * @Method("POST")
     */
    public function check()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    /**
     * @Route("/logout", name="admin_logout")
     */
    public function logoutAction()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }
}
