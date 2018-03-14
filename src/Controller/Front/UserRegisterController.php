<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Form\UserRegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user/register")
 */
class UserRegisterController extends Controller
{
    const USER_REGISTER_KEY = "user_register_key";

    /**
     * @Route("/", name="user_register_index")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->get('session')->get(self::USER_REGISTER_KEY);
        $user = $user? $user: new User();

        $form = $this->createForm(UserRegisterType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $this->get('session')->set(self::USER_REGISTER_KEY, $user);

            return $this->redirectToRoute('user_register_confirm');
        }

        return $this->render('Front/user_register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/confirm", name="user_register_confirm")
     */
    public function confirm(Request $request, EntityManagerInterface $entityManager)
    {
        if (null === $user = $this->get('session')->get(self::USER_REGISTER_KEY)) {
            return $this->redirectToRoute('user_register_index');
        }

        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($user);
            $entityManager->flush();

            $this->get('session')->remove(self::USER_REGISTER_KEY);

            $this->addFlash('success', '登録しました。');
            return $this->redirectToRoute('front_index');
        }

        return $this->render('Front/user_register/confirm.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
