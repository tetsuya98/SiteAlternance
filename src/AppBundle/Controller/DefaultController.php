<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Etudiant;
use AppBundle\Entity\User;
use AppBundle\Form\EntrepriseType;
use AppBundle\Form\EtudiantType;
use AppBundle\Form\UserType;
use Doctrine\ORM\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Exception\BaseException;
use OffreBundle\Entity\Candidature;
use OffreBundle\Entity\Offre;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Date;
use Yosimitso\WorkingForumBundle\Entity\Post;
use Yosimitso\WorkingForumBundle\Entity\PostReport;
use Yosimitso\WorkingForumBundle\Entity\PostVote;
use Yosimitso\WorkingForumBundle\Entity\Subscription;
use Yosimitso\WorkingForumBundle\Entity\Thread;

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
        /** @var User $user */
        $user = $this->getUser();

        if (is_null($user)) {
            return $this->indexWithoutLogin();
        } elseif (!is_null($user->getUserEntreprise())) {
            return $this->indexEntrepriseAction();
        } elseif (!is_null($user->getUserEtudiant())) {
            return $this->indexEtudiantAction();
        } else {
            return $this->indexWithoutLogin();
        }
    }

    private function indexEtudiantAction() {

        $em = $this->getDoctrine();

        // Récupération entretiens
        $entretiens = $em->getRepository(Candidature::class)->createQueryBuilder('c')
            ->select('c')->where('c.dateMeeting IS NOT NULL')->andWhere('c.etudiant = :etudiant')
            ->andWhere('c.dateMeeting > :date')->orderBy('c.dateMeeting', 'ASC')
            ->setParameter('etudiant', $this->getUser()->getUserEtudiant())
            ->setParameter('date', new \DateTime())->getQuery()->getResult();
        $countEntretiens = count($entretiens);
        $agenda = null;
        if ($countEntretiens > 0) {
            $agenda = $entretiens[0];
        }

        // récupération des offres
        $offres = $em->getRepository(Offre::class)->createQueryBuilder('o')
            ->select('o')->orderBy('o.crdate', 'DESC')->setMaxResults(2)
            ->getQuery()->getResult();

        // récupération des candidatures
        $candidatures = $em->getRepository(Candidature::class)->createQueryBuilder('c')
            ->select('c')->where('c.etudiant = :etudiant')->orderBy('c.datePostule', 'DESC')
            ->setParameter('etudiant', $this->getUser()->getUserEtudiant())->setMaxResults(2)
            ->getQuery()->getResult();

        return $this->render('@App/Home/index_etudiant.html.twig', [
            'countEntretiens' => $countEntretiens,
            'offres' => $offres,
            'agenda' => $agenda,
            'candidatures' => $candidatures
        ]);
    }

    private function indexEntrepriseAction(){
        $em = $this->getDoctrine();
        return $this->render('@App/Home/index_entreprise.html.twig', [
            'countCandidatures' => $em->getRepository(Candidature::class)->getCountCandidatures($this->getUser()),
            'vues' => $em->getRepository(Offre::class)->getCountVues($this->getUser()),
            'offres' => $em->getRepository(Offre::class)->getCountOffres($this->getUser()),
            'best' => $em->getRepository(Offre::class)->getBestOffres($this->getUser()),
            'candidatures' => $em->getRepository(Candidature::class)->createQueryBuilder('c')
                ->select('c')->leftJoin('c.offre', 'o')->where('o.user = :user')
                ->orderBy('c.datePostule', 'DESC')->setParameter('user', $this->getUser())
                ->setMaxResults(2)->getQuery()->getResult(),
            'agenda' => $em->getRepository(Candidature::class)->getAgendaEntreprise($this->getUser(), 2)
        ]);
    }

    private function indexWithoutLogin() {
        return $this->render('@App/Security/select_home.html.twig');
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
          //  dump($user->getUserEntreprise());
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




    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/profile/deleteUser/{id}", name="delete_user", requirements={"id"="\d+"})
     */
    public function deleteAction($id, Request $request)
    {
        $res = false;

        if (!$this->getAuthorization()->isGranted('ROLE_ADMIN')) {
            if ($this->getUser()->getId() != $id) throw $this->createNotFoundException('Vous n\'avez pas l\'autorisation d\'accéder à ce contenu.');
            $res = true;
        }

        $repUser = $this->getDoctrine()->getManager()->getRepository('AppBundle:User');


        if (!$user = $repUser->find($id))
            throw $this->createNotFoundException('Utilisateur[id=' . $id . '] inexistante');

        $name = $user->getUsername();
        $entityManager = $this->getDoctrine()->getManager();

        $userManager = $this->get('fos_user.user_manager');
        if (!$trach = $repUser->findOneByUsername('root'))
            throw $this->createNotFoundException('il faudra cree un simple utilisateur du nom root poure le trash');

        $repositoryPoste = $entityManager->getRepository(Post::class);
        $repositoryVote = $entityManager->getRepository(PostVote::class);
        $repositoryThread = $entityManager->getRepository(Thread::class);
        $repositoryworkingforum_subscription = $entityManager->getRepository(Subscription::class);
        $repositoryworkingforum_post_report = $entityManager->getRepository(PostReport::class);


        $listePoste = $repositoryPoste->findByUser($user);
        $listeVote = $repositoryVote->findByUser($user);
        $listeThread = $repositoryThread->findByAuthor($user->getId());
        $listeSubscription = $repositoryworkingforum_subscription->findByUser($user);
        $listePostReport = $repositoryworkingforum_post_report->findByUser($user);

        //workingforum_subscription
        //workingforum_post_report
        if (!empty($listeSubscription)) {

            foreach ($listeSubscription as $list) {
                $list->setUser($trach);

                $entityManager->persist($list);

            }
        }
        if (!empty($listePostReport)) {

            foreach ($listePostReport as $list) {
                $list->setUser($trach);

                $entityManager->persist($list);

            }
        }


        if (!empty($listeThread)) {

            foreach ($listeThread as $list) {
                $list->setAuthor($trach);
                $list->setLocked(1);
                $entityManager->persist($list);

            }
        }

        if (!empty($listePoste)) {

            foreach ($listePoste as $list) {
                $list->setUser($trach);
                $list->setUserId($trach);
                $entityManager->persist($list);

            }
        }


        if (!empty($listeVote)) {
            foreach ($listeVote as $list) {

                $list->setUser($trach);
                $entityManager->persist($list);
            }
        }


        $entityManager->flush();
        $userManager->deleteUser($user);


        if ($res or $this->getUser()->getId() == $id)
            // Si c'est un utilisateur lambda
            //return $this->render('profile/suppUser.html.twig', array('name' => $name));
            // VIDER LES VARIABLES DE SESSION
            return $this->redirectToRoute('homepage');
        else {
            // Si c'est un administrateur
            $this->addFlash('success', $name . ' est bien supprimé.');
            return $this->redirectToRoute('All_User');
        }
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/profile/show_prof/{id}", name="show_prof", requirements={"id"="\d+"})
     */

    public function show_profAction(int $id){
        $repUser = $this->getDoctrine()->getManager()->getRepository('AppBundle:User');
        if (!$user = $repUser->find($id)){
            $this->addFlash("ERROR","ERREUR : L'utilisateur n'existe pas.");
            return $this->redirectToRoute('homepage');
        }
       $user->newVisite($this->getUser());


        return $this->render('User/show_profile_user.html.twig', array('user'=>$user));
    }
    /**
     * @Route("/profile/allUser",name="All_User")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allUserAction()
    {

//        $repUser = $this->getDoctrine()->getManager()->getRepository('AppBundle:User');
        $userManager = $this->get('fos_user.user_manager');
        $repUser = $userManager->findUsers();

        $user_Entreprise=array();
        $users_etu=array();

        foreach ($repUser as $r){
            dump($r);
            try{
                if($r->getStatus() == "ENTREPRISE")
                    array_push($user_Entreprise,$r);
                elseif($r->getStatus() == "ETUDIANT")
                    array_push($users_etu,$r);
            }catch ( BaseException $e){

            }

        }

        return $this->render('User/allUser.html.twig', array('listeEtu' => $users_etu, 'listeEntr' => $user_Entreprise));

    }
}
