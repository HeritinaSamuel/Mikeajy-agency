<?php

namespace App\Controller;

use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * Permet de recuperer tous les annonces
     * 
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $adRepo): Response
    {
        return $this->render('ad/index.html.twig',[
            'ads' => $adRepo->findAll()
        ]);
    }

    /**
     * Permet de recuperer une annonce
     *
     * @Route("/ads/{slug}", name="ads_show")
     * @param String $slug
     * @return Response
     */
    public function show($slug, AdRepository $adRepo)
    {
        $ad = $adRepo->findOneBySlug($slug);

        return $this->render('ad/show.html.twig',[
            'ad'=> $ad
        ]);
    }
}
