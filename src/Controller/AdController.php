<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Entity\Image;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * Ajout de nouveua annonce
     * 
     * @Route("/ads_new", name="ads_create")
     * @IsGranted("ROLE_USER")
     * 
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $ad = new Ad();
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère le image de couverture transmise
            $cover_image = $form->get('coverImage')->getData();
            // On récupère les images transmises
            $images = $form->get('images')->getData();
            // On génère un nouveau nom de fichier
            $fichier = md5(uniqid()) . '.' . $cover_image->guessExtension();
            // On copie le fichier dans le dossier uploads
            $cover_image->move(
                $this->getParameter('coverimages_directory'),
                $fichier
            );
            // On stocke le nom de l'image dans la base de données
            $ad->setCoverImage($fichier);

            $ad->setAuthor($this->getUser());

            foreach($images as $image){
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                $img = new Image();
                $img->setNom($fichier);
                $ad->addImage($img);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ad);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "L'annonce {$ad->getTitle()} a bien ete enregistre."
            );
            
            return $this->redirectToRoute('ads_show',[
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/new.html.twig',[
            'ad' => $ad,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet de recuperer une annonce
     *
     * @Route("/ads/{slug}", name="ads_show")
     * @param String $slug
     * @return Response
     */
    public function show(Ad $ad)
    {
        return $this->render('ad/show.html.twig',[
            'ad'=> $ad
        ]);
    }

    /**
     * Permet de supprimer une annonce
     * 
     * @Route("/ads/{slug}/delete", name="ads_delete")
     * @Security("is_granted('ROLE_USER') and user == ad.getAuthor()", message="Vous n'avez pas le droit d'accéder à cette ressource")
     *
     * @param Ad $ad
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Ad $ad) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($ad);
        $entityManager->flush();

        $this->addFlash(
            'success',
            "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée !"
        );

        return $this->redirectToRoute("ads_index");
    }
}
