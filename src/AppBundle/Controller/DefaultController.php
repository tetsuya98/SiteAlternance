<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Etudiant;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    /**
     * @Route("/register/Etudiant",name="new_Student")
     *
     */
    public function newUserStudant( Request $request, UserPasswordEncoderInterface $encoder){
        $Etu = new User("ROLE_ETUDIANT");



        // Génération du formulaire
        $form = $this->createForm(UserType::class, $Etu);

        // Validation et enregistrement de l'offre
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $Etu = $form->getData();

            $this->my_encodePassword($Etu, $encoder);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($Etu);
            $entityManager->flush();
            return $this->redirectToRoute('homepage');
        }

        return $this->render('User/RegisterUserEtudiant.html.twig', [
            'form' => $form->createView(),
        ]);

    }
    /**
     * @Route("/register/Entreprise",name="new_Entreprise")
     *
     */
    public function newUserEntrep( Request $request, UserPasswordEncoderInterface $encoder){
        $Etu = new User("ROLE_ENTREPRISE");



        // Génération du formulaire
        $form = $this->createForm(UserType::class, $Etu);

        // Validation et enregistrement de l'offre
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $Etu = $form->getData();

            $this->my_encodePassword($Etu, $encoder);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($Etu);
            $entityManager->flush();
            return $this->redirectToRoute('homepage');
        }

        return $this->render('User/RegisterUserEntreprise.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    private function my_encodePassword(User $user, UserPasswordEncoderInterface $encoder)
    {
//        $factory = $this->get('security.encoder_factory');
//        $encoder = $factory->getEncoder($user);
        $mdp = $user->getPassword();
        $password = $encoder->encodePassword($user, $mdp);
        $user->setPassword($password);
        return $user;
    }
}
