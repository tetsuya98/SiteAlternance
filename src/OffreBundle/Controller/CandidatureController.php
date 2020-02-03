<?php

namespace OffreBundle\Controller;

use AppBundle\Entity\User;
use Exception;
use OffreBundle\Entity\Candidature;
use OffreBundle\Entity\Offre;
use OffreBundle\Type\CandidatureAcceptType;
use OffreBundle\Type\CandidatureRefuseType;
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
            return $this->render('OffreBundle:Candidatures:entreprise_index.html.twig', [
                'candidatures' => $this->getDoctrine()->getRepository(Candidature::class)
                    ->getEntrepriseCandidatures($user)
            ]);
        }
    }

    /**
     * @Route("/show/{candidature}", name="candidature_show")
     * @param Candidature $candidature
     * @param Request $request
     * @return RedirectResponse|Response Afficahe de la vue | Redirect vers l'index
     */
    public function showAction(Candidature $candidature, Request $request){

        // Génération du formulaire
        $formAccept = $this->createForm(CandidatureAcceptType::class, $candidature);
        $candidature->setDateMeeting(new \DateTime());
        $formAccept->handleRequest($request);
        if ($formAccept->isSubmitted() && $formAccept->isValid()) {
            $candidature = $formAccept->getData();
            $candidature->setResponse(1);
            $candidature->setDateResponse(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($candidature);
            $entityManager->flush();
            return $this->redirectToRoute('candidatures_index');
        }

        // Génération du formulaire
        $formRefuse = $this->createForm(CandidatureRefuseType::class, $candidature);
        $formRefuse->handleRequest($request);
        if ($formRefuse->isSubmitted() && $formRefuse->isValid()) {
            $candidature = $formRefuse->getData();
            $candidature->setResponse(2);
            $candidature->setDateResponse(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($candidature);
            $entityManager->flush();
            return $this->redirectToRoute('candidatures_index');
        }

        // Rendu de la vue
        return $this->render('OffreBundle:Candidatures:show.html.twig', [
            'formAccept' => $formAccept->createView(),
            'formRefuse' => $formRefuse->createView(),
            'candidature' => $candidature
        ]);
    }

    /**
     * @Route("/remove/{candidature}", name="candidature_remove")
     * @param Candidature $candidature Candidature à supprimer
     * @return RedirectResponse Retour vers l'index
     * @throws Exception
     */
    public function removeAction(Candidature $candidature) {

        /** @var User $user */
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        if ($user->getUserEtudiant() !== $candidature->getEtudiant()) {
            throw new Exception("Vous n'êtes pas l'auteur de la candidature.");
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
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function postuleAction(Offre $offre, Request $request){

        // Récupération de l'étudiant
        /** @var User $user */
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        dump($user);
        $etudiant = $user->getUserEtudiant();
        if (is_null($etudiant)) {
            throw new Exception("L'utilisateur doit être un étudiant pour postuler.");
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
