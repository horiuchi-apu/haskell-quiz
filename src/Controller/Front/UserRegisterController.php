<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Form\UserRegisterType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserRegisterController extends Controller
{
    /**
     * @Route("/user/register", name="user_register_index")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserRegisterType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', '登録しました。');
            return $this->redirectToRoute('front_index');
        }

        return $this->render('Front/user_register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
