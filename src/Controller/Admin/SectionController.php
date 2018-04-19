<?php

namespace App\Controller\Admin;

use App\Entity\Section;
use App\Form\Admin\CreateSectionType;
use App\Form\Admin\EditSectionType;
use App\Repository\SectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class SectionController
 * @package App\Controller\Admin
 * @Route("/admin/section")
 */
class SectionController extends Controller
{
    /**
     * @Route("/", name="admin_section_index")
     */
    public function index(Request $request, SectionRepository $repository)
    {
        $query = $repository->createQueryBuilder('section')->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        $deleteForm = $this->createDeleteForm();
        return $this->render('Admin/section/index.html.twig', [
            'pagination' => $pagination,
            'deleteForm' => $deleteForm->createView(),
        ]);
    }

    /**
     * @Route("/create", name="admin_section_create")
     */
    public function create(Request $request, EntityManagerInterface $em)
    {
        $section = new Section();
        $form = $this->createForm(CreateSectionType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($section);
            $em->flush();

            $this->addFlash('success', "セクションを登録しました。");
            return $this->redirect($this->generateUrl('admin_section_index'));
        }

        return $this->render('Admin/section/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="admin_section_edit")
     */
    public function edit(Section $section, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(EditSectionType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', "セクションを編集しました。");
            return $this->redirect($this->generateUrl('admin_section_index'));
        }

        return $this->render('Admin/section/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_section_delete")
     */
    public function delete(Section $section, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createDeleteForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($section);
            $em->flush();

            $this->addFlash('success', "セクションを削除しました。");
        }
        return $this->redirect($this->generateUrl('admin_section_index'));
    }


    public function createDeleteForm()
    {
        return $this->createFormBuilder()->getForm();
    }
}
