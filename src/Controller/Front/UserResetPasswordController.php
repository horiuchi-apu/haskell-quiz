<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Form\UserResetPasswordRequestType;
use App\Form\UserResetPasswordType;
use App\Repository\UserRepository;
use App\Service\Mailer;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user/reset_password")
 */
class UserResetPasswordController extends Controller
{

    const RESET_PASSWORD_KEY          = 'reset_password_key';

    /**
     * @Route("/", name="user_reset_password_index")
     */
    public function index(Request $request, UserRepository $repository)
    {
        $form = $this->createForm(UserResetPasswordRequestType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $email = $form->get('email')->getData();

            if (null === $user = $repository->findOneBy(['email' => $email])) {
                $this->addFlash('danger', "メールアドレスに謝りがあります");
            } else {
                $this->get('session')->set(self::RESET_PASSWORD_KEY, $user);
                return $this->redirectToRoute('user_reset_password_confirm');
            }
        }

        return $this->render('Front/user_reset_password/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/confirm", name="user_reset_password_confirm")
     */
    public function confirm(Request $request, EntityManagerInterface $entityManager, Mailer $mailer)
    {
        $user = $this->get('session')->get(self::RESET_PASSWORD_KEY);
        if (!$user || null === $user = $entityManager->find(User::class, $user)) {
            return $this->redirectToRoute('front_index');
        }

        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $token = $this->genToken();
            $limit = Carbon::now()->addHour(2);
            $user->setConfirmToken($token);
            $user->setConfirmTokenLimit($limit);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->get('session')->remove(self::RESET_PASSWORD_KEY);

            $mailer->send('Front/user_reset_password/mail/confirm.txt.twig', [
                'user' => $user
            ]);

            $this->addFlash('success', "{$user->getEmail()}にメールを送信しました。確認してください。");
            return $this->redirectToRoute('front_index');
        }

        return $this->render('Front/user_reset_password/confirm.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/reset/{token}", name="user_reset_password_reset")
     */
    public function reset(
        $token,
        Request $request,
        Mailer $mailer,
        UserPasswordEncoderInterface $passwordEncoder,
        UserRepository $repository,
        EntityManagerInterface $entityManager
    )
    {
        if (null === $user = $repository->findByToken($token)) {
            return $this->redirect($this->generateUrl('front_index'));
        }

        $form = $this->createForm(UserResetPasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setIsEnabled(true);
            $user->setConfirmToken('');
            $user->setConfirmTokenLimit(null);

            $entityManager->persist($user);
            $entityManager->flush();

            $mailer->send('Front/user_reset_password/mail/complete.txt.twig', [
                'user' => $user
            ]);


            $this->addFlash('success', "パスワードの再設定が完了しました。");
            return $this->redirectToRoute('front_index');
        }


        return $this->render('Front/user_reset_password/reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    private function genToken()
    {
        try {
            $token = bin2hex(random_bytes(16));
        } catch (\Exception $e) {
            $token = md5(uniqid(rand(), true));
        }

        return $token;
    }
}
