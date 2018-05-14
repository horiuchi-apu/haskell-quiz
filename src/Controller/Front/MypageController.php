<?php

namespace App\Controller\Front;

use App\Entity\Section;
use App\Entity\User;
use App\Form\Front\EditUserType;
use App\Repository\SectionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class MypageController
 * @package App\Controller\Front
 * @Route("/mypage")
 */
class MypageController extends Controller
{
    /**
     * @Route("/", name="front_mypage")
     */
    public function index(SectionRepository $repository)
    {
        $user = $this->getUser();
        $sections = $repository->findAll();

        return $this->render('Front/mypage/index.html.twig', [
            'user' => $user,
            'sections' => $sections
        ]);
    }

    /**
     * @Route("/edit_profile", name="front_edit_profile")
     */
    public function editProfile(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em)
    {
        $user = $this->getUser();

        $form = $this->createForm(EditUserType::class, $user);

        if ($request->isMethod("POST")) {
            /** @var UserRepository $repo */
            $repo = $em->getRepository(User::class);
            $username = $request->request->get('edit_user')['username'];
            $loadedUser = $repo->findOneBy(['username' => $username]);
            if ($loadedUser && $loadedUser != $user) {
                $form->get('username')->addError(new FormError('既に使用されているIDです'));
                return $this->render('Front/mypage/modify_profile.html.twig', [
                    'user' => $user,
                    'form' => $form->createView(),
                ]);
            }
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $em->flush();
            $this->addFlash('success', 'プロフィールを編集しました');
        }

        return $this->render('Front/mypage/modify_profile.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
