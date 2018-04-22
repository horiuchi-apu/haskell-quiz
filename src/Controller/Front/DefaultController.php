<?php

namespace App\Controller\Front;

use App\Entity\FunctionInfo;
use App\Repository\FunctionInfoRepository;
use App\Repository\SectionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DefaultController
 * @package App\Controller\Front
 * @Security("has_role('ROLE_USER')")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="front_index")
     */
    public function index(SectionRepository $repository)
    {
        $sections = $repository->findAll();

        return $this->render('Front/default/index.html.twig', [
            'sections' => $sections,
        ]);
    }


    /**
     * @Route("/functions", name="front_functions")
     */
    public function functionReference(FunctionInfoRepository $repository)
    {
        /** @var FunctionInfo[] $functions */
        $functions = $repository->findAll();
        return $this->render('Front/default/functions.html.twig', [
            'functions' => $functions,
        ]);
    }
}
