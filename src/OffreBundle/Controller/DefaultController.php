<?php

namespace OffreBundle\Controller;

use AppBundle\Entity\User;
use OffreBundle\Entity\Offre;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 * @package OffreBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="offres_index")
     */
    public function indexAction()
    {
        // Récupération de l'user
        /** @var User $user */
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        return $this->render('OffreBundle:Default:index.html.twig', [
            'offres' => $user->getOffres()
        ]);
    }

    /**
     * @Route("/new", name="offres_new")
     */
    public function newAction(Request $request){

        // Création de l'offre
        $offre = new Offre();
        /** @var User $user */
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $offre->setUser($user);
        $offre->setCrdate(new \DateTime());
        $offre->setNbVue(0);

        // Génération du formulaire
        $form = $this->createFormBuilder($offre)
            ->add('titre', TextType::class)
            ->add('description', TextareaType::class)
            ->add('nbSemaine', NumberType::class, ['label' => "Durée du stage (en semaine)"])
            ->add('submit', SubmitType::class, ['label' => "Publier l'offre"])
            ->getForm();

        // Validation et enregistrement de l'offre
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $offre = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offre);
            $entityManager->flush();
            return $this->redirectToRoute('offres_index');
        }

        // Rendu de la vue
        return $this->render('OffreBundle:Default:new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
