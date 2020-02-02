<?php

namespace OffreBundle\Controller;

use AppBundle\Entity\User;
use OffreBundle\Entity\Candidature;
use OffreBundle\Entity\Offre;
use OffreBundle\Type\CandidatureType;
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
 * Class CandidatureController
 * @package OffreBundle\Controller
 */
class CandidatureController extends Controller
{
    /**
     * @Route("/", name="candidatures_index")
     */
    public function indexAction()
    {
        // Récupération de l'user
        /** @var User $user */
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        if (!is_null($user->getUserEtudiant())){
            $etudiant = $user->getUserEtudiant();
            return $this->render('OffreBundle:Candidatures:etudiant_index.html.twig', [
                'candidatures' => $etudiant->getCandidatures()
            ]);
        } else {

        }
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
        return $this->render('OffreBundle:Default:edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/remove/{candidature}", name="candidature_remove")
     * @param Candidature $candidature Candidature à supprimer
     * @return RedirectResponse Retour vers l'index
     * @throws \Exception
     */
    public function removeAction(Candidature $candidature) {

        /** @var User $user */
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        if ($user->getUserEtudiant() !== $candidature->getEtudiant()) {
            throw new \Exception("Vous n'êtes pas l'auteur de la candidature.");
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($candidature);
        $em->flush();
        $flashbag = $this->get('session')->getFlashBag();
        $flashbag->add('success', "La candidature a été supprimée avec succès.");
        return $this->redirectToRoute('candidatures_index');
    }

    /**
     * @Route("/postule/{offre}", name="offres_postule")
     * @param Offre $offre Offre à candidater
     * @throws \Exception
     */
    public function postuleAction(Offre $offre, Request $request){

        // Récupération de l'étudiant
        /** @var User $user */
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        dump($user);
        $etudiant = $user->getUserEtudiant();
        if (is_null($etudiant)) {
            throw new \Exception("L'utilisateur doit être un étudiant pour postuler.");
        }

        // Création du form et entité
        $candidature = new Candidature();
        $candidature->setEtudiant($etudiant);
        $candidature->setOffre($offre);
        $candidature->setDatePostule(new \DateTime());
        $form = $this->createForm(CandidatureType::class, $candidature);

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
        return $this->render('OffreBundle:Candidatures:new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
