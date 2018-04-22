<?php

namespace App\Controller\Admin;

use App\Entity\FunctionInfo;
use App\Form\Admin\CreateFunctionDescriptionType;
use App\Form\Admin\EditFunctionDescriptionType;
use App\Repository\FunctionInfoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class FunctionDescriptionController
 * @package App\Controller\Admin
 * @Security("has_role('ROLE_ADMIN')")
 * @Route("/admin/function_info")
 */
class FunctionInfoController extends Controller
{
    /**
     * @Route("/", name="admin_function_info_index")
     */
    public function index(Request $request, FunctionInfoRepository $repository)
    {
        $query = $repository->createQueryBuilder('function_info')->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        $deleteForm = $this->createDeleteForm();
        return $this->render('Admin/function_info/index.html.twig', [
            'pagination' => $pagination,
            'deleteForm' => $deleteForm->createView(),
        ]);
    }

    /**
     * @Route("/create", name="admin_function_info_create")
     */
    public function create(Request $request, EntityManagerInterface $em)
    {
        $function_info = new FunctionInfo();
        $form = $this->createForm(CreateFunctionDescriptionType::class, $function_info);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($function_info);
            $em->flush();

            $this->addFlash('success', "関数情報を登録しました。");
            return $this->redirect($this->generateUrl('admin_function_info_index'));
        }

        return $this->render('Admin/function_info/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="admin_function_info_edit")
     */
    public function edit(FunctionInfo $function_info, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(EditFunctionDescriptionType::class, $function_info);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', "関数情報を編集しました。");
            return $this->redirect($this->generateUrl('admin_function_info_index'));
        }

        return $this->render('Admin/function_info/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_function_info_delete")
     */
    public function delete(FunctionInfo $function_info, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createDeleteForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($function_info);
            $em->flush();

            $this->addFlash('success', "関数情報を削除しました。");
        }
        return $this->redirect($this->generateUrl('admin_function_info_index'));
    }


    public function createDeleteForm()
    {
        return $this->createFormBuilder()->getForm();
    }
}
