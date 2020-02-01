<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Etudiant;
use AppBundle\Entity\User;
use AppBundle\Form\EntrepriseType;
use AppBundle\Form\EtudiantType;
use AppBundle\Form\UserType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DefaultController extends Controller
{

    /**
     * @var object $authorizationChecker Symfony\Component\Security\Core\Authorization\AuthorizationChecker
     * //     */
    protected $authorizationChecker;

    /**
     * @return object Symfony\Component\Security\Core\Authorization\AuthorizationChecker
     */
    protected function getAuthorization()
    {
        if (null === $this->authorizationChecker) {
            $this->authorizationChecker = $this->get('security.authorization_checker');
        }

        return $this->authorizationChecker;
    }

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
        $form->add('Créer un compte', SubmitType::class, [
            'attr' => ['class' => 'save'],
        ]);

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
        $form->add('Créer un compte', SubmitType::class, [
            'attr' => ['class' => 'save'],
        ]);


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

    /**
     * @Route("/edit/profile",name="editProfile")
     *
     */

    public function EditProfile(Request $request){

        $user = $this->getUser();
        $res = false;
        if (null === $this->getUser()) {
            // Ici, l'utilisateur est anonyme ou l'URL n'est pas derrière un pare-feu
            return $this->redirectToRoute('login');
        }
        $form2 = $this->createForm(UserType::class, $user);
        $form2->remove("plainPassword");
        if ($this->getAuthorization()->isGranted('ROLE_ENTREPRISE') &&  $user->getUserEntreprise()!= null) {
            $user->getUserEntreprise()->setUserManager($user);
            $user->setUserEtudiant(null);
            dump($user->getUserEntreprise());
          /*  die;*/


            $form = $this->createForm(EntrepriseType::class, $user->getUserEntreprise());

           // $form->add($form=$this->createForm(UserType::class, $user));
            /*var_dump($user->getUserEntreprise());
            die;*/




            $form->add('submit', SubmitType::class, array('label' => 'Modifier'));

            $form->handleRequest($request);
            //  $form->isValid()

            if ($form->isSubmitted() and $form2->isSubmitted()) {
                $entityManager = $this->getDoctrine()->getManager();



                $entityManager->persist($user);
                $entityManager->flush();
                /* $this->get('session')->getFlashBag()
                     ->add('infoAjout', 'nouvelle salle ajoutée :'.$canidat->__toString());*/
                if ($res) return $this->redirectToRoute('profile');
                return $this->redirectToRoute('homepage');
            }

        }elseif ($this->getAuthorization()->isGranted('ROLE_ETUDIANT')and  $user->getUserEtudiant() != null){
            $form = $this->createForm(EtudiantType::class, $user->getUserEtudiant());
            $form->add('submit', SubmitType::class, array('label' => 'Modifier'));

            $form->handleRequest($request);
            //  $form->isValid()

            if ($form->isSubmitted() && $form2->isSubmitted()) {
                $entityManager = $this->getDoctrine()->getManager();



                $entityManager->persist($user);
                $entityManager->flush();
                /* $this->get('session')->getFlashBag()
                     ->add('infoAjout', 'nouvelle salle ajoutée :'.$canidat->__toString());*/
                if ($res) return $this->redirectToRoute('profile');
                return $this->redirectToRoute('homepage');
            }


        }
        else{
                return $this->redirectToRoute('fos_user_profile_edit');



        }
        return $this->render('User/editProfile.html.twig', [
            'form' => $form->createView(),'form2'=>$form2->createView()
        ]);
    }

}
