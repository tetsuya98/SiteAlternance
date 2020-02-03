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

        if (!is_null($user->getUserEntreprise())) {
            return $this->render('OffreBundle:Default:index.html.twig', [
                'offres' => $user->getOffres()
            ]);
        } else {
            return $this->render('OffreBundle:Default:index.html.twig', [
                'offres' => $this->getDoctrine()->getRepository(Offre::class)
                ->findBy([],['crdate' => 'DESC'])
            ]);
        }

    }

    /**
     * @Route("/new", name="offres_new")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function newAction(Request $request){

        // Vérification user
        /** @var User $user */
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        if (is_null($user->getUserEntreprise())) {
            throw new \Exception("Vous n'êtes pas une entreprise.");
        }

        // Création de l'offre
        $offre = new Offre();
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
     * @Route("/show/{offre}",name="offres_show")
     * @param Offre $offre
     * @return Response Vue
     * @throws \Exception
     */
    public function showAction(Offre $offre){

        // Vérification si étudiant
        /** @var User $user */
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        if (is_null($user->getUserEtudiant())) {
            throw new \Exception("Vous n'êtes pas un étudiant.");
        }

        // Ajout compteur vue
        $offre->setNbVue($offre->getNbVue() + 1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($offre);
        $em->flush();

        // Vérification si postulé
        $candidature = $em->getRepository(Candidature::class)->findOneBy([
            'etudiant' => $user->getUserEtudiant(),
            'offre' => $offre
        ]);

        // Retour de la vue
        return $this->render('OffreBundle:Default:show.html.twig',[
            'offre' => $offre,
            'candidature' => $candidature
        ]);

    }

    /**
     * @Route("/edit/{offre}", name="offres_edit")
     * @param Offre $offre
     * @param Request $request
     * @return RedirectResponse|Response Affiche de la vue | Redirect vers l'index
     * @throws \Exception
     */
    public function editAction(Offre $offre, Request $request){

        // Vérification user
        /** @var User $user */
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        if ($user !== $offre->getUser()) {
            throw new \Exception("Vous n'êtes pas l'auteur de l'offre.");
        }

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
            return $this->redirectToRoute('candidatures_index');
        }

        // Rendu de la vue
        return $this->render('OffreBundle:Candidatures:new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
