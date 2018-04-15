<?php

namespace App\Controller\Admin;

use App\Repository\SectionRepository;
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

        return $this->render('Admin/section/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
