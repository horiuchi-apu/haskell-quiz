<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Admin\CreateUserType;
use App\Form\Admin\EditUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController
 * @package App\Controller\Admin
 * @Route("/admin/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/", name="admin_user_index")
     */
    public function index(Request $request, UserRepository $repository)
    {
        $query = $repository->createQueryBuilder('user')->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('Admin/user/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }


    /**
     * @Route("/create", name="admin_user_create")
     */
    public function create(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User(true);
        $form = $this->createForm(CreateUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setIsEnabled(true);

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "ユーザーを登録しました。");
            return $this->redirect($this->generateUrl('admin_user_index'));
        }

        return $this->render('Admin/user/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="admin_user_edit")
     */
    public function edit(User $user, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', "ユーザーを編集しました。");
            return $this->redirect($this->generateUrl('admin_user_index'));
        }

        return $this->render('Admin/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
