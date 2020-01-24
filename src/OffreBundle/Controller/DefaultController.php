<?php

namespace OffreBundle\Controller;

use AppBundle\Entity\User;
use OffreBundle\Entity\Offre;
use OffreBundle\Type\OffreType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Exception
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
        $form = $this->createForm(OffreType::class, $offre);

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

    /**
     * @Route("/edit/{offre}", name="offres_edit")
     * @param Offre $offre
     * @param Request $request
     * @return RedirectResponse|Response Afficahe de la vue | Redirect vers l'index
     */
    public function editAction(Offre $offre, Request $request){

        // Génération du formulaire
        $form = $this->createForm(OffreType::class, $offre);

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

    /**
     * @Route("/remove/{offre}", name="offres_remove")
     * @param Offre $offre Offre à supprimer
     * @return RedirectResponse Retour vers l'index
     */
    public function removeAction(Offre $offre) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($offre);
        $em->flush();
        $flashbag = $this->get('session')->getFlashBag();
        $flashbag->add('success', "L'offre a été supprimée avec succès.");
        return $this->redirectToRoute('offres_index');
    }
}
