<?php
/**
 * Created by PhpStorm.
 * User: zitouni
 * Date: 09/02/2019
 * Time: 20:47
 */

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Competence;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Yosimitso\WorkingForumBundle\Entity\Forum;
use Yosimitso\WorkingForumBundle\Entity\Rules;
use Yosimitso\WorkingForumBundle\Entity\Subforum;


class AppFixtures extends Fixture
{
    private $encoder;
    private $listeSlug;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

// ...

    /**
     * @return UserPasswordEncoderInterface
     */
    public function getEncoder()
    {
        return $this->encoder;
    }

    private function random($var)
    {
        $string = "";
        $chaine = "a0b1c2d3e4f5g6h7i8j9klmnpqrstuvwxy123456789";
        srand((double)microtime() * 1000000);
        for ($i = 0; $i < $var; $i++) {
            $string .= $chaine[rand() % strlen($chaine)];
        }

        if (!empty($this->listeSlug)) {
            if (in_array($string, $this->listeSlug)) $string = $this->random($var);
        }
        array_push($this->listeSlug, $string);
        return $string;
    }

    /**
     * Load data fixtures with the passed EntityManager
     */
    public function load(\Doctrine\Persistence\ObjectManager $manager)
    {

        $this->listeSlug = array();

        //Utilisateur Admit et root

        $user = new User();
        $user->setUsername('admin');
        $password = $this->encoder->encodePassword($user, 'root');
        $user->setEmail("admin@adm.fr");
        $user->setRole("ROLE_ADMIN");
        $user->setEnabled(true);
        $user->setPassword($password);
        $manager->persist($user);

        $user = new User();
        $user->setUsername('root');
        $password = $this->encoder->encodePassword($user, 'root');
        $user->setEmail("root@adm.fr");
        $user->getUserForum()->setBanned(1);
        $user->setPassword($password);
        $user->setRole("ROLE_USER");
        $manager->persist($user);

        $competence = new Competence();
        $competence->setCompetences("Informatique");
        $manager->persist($competence);
        $competence = new Competence();
        $competence->setCompetences("Mathématiques");
        $manager->persist($competence);

        $regle = new Rules();
        $regle->setLang("Français");
        $regle->setContent("<strong>Respect</strong><br/>
Les utilisateurs doivent traiter les autres utilisateurs, la modération et l'administration du Forum avec respect. Les commentaires irritants et irrespectueux ne sont pas tolérés. De même, les commentaires potentiellement faux ou pouvant porter préjudice à un individu ou une entité ne sont pas tolérés.

Il est important de faire la différence entre un jugement/attaque personnelle et une opinion. Une utilisatrice est dans son droit de réfuter votre argumentaire en faisant preuve de respect et en exposant ses propres arguments pour appuyer son opinion, et ceci ne constitue pas nécessairement à un jugement ou une attaque personnelle. Veuillez vous référer à l'équipe de modération en cas de doute. Une règle de base à respecter dans ce cas est qu'il est permis d'exprimer votre désaccord avec une idée sans attaquer la personne.

<strong>Pondération, civisme et courtoisie</strong><br/>
Parce que nous souhaitons que les échanges sur le Forum se fassent dans la bonne humeur et l'harmonie, nous demandons à nos utilisateurs de toujours faire preuve de pondération, de civisme et de courtoisie dans leurs publications. Bien qu'un Forum soit un milieu virtuel, il convient de toujours garder à l'esprit qu'il est aussi un espace public. Ne vous cachez pas derrière votre entité virtuelle, soyez comme vous seriez en personne.

L'équipe de modération ne tolère pas l'acharnement envers un autre utilisateur, le cynisme/dérision, la condescendance, les jugements et préjugés.

<strong>Consulter l’existant et respecter les sections</strong><br/>
Avant de publier un message, naviguez dans les différentes sections du Forum afin de repérer la section appropriée. Merci de publier votre message dans une seule section.

Avant de poser une question sur le Forum, veillez à rechercher dans les sujets déjà existants pour savoir si une question identique à la vôtre a déjà été posée et répondue, et ainsi éviter la redite.

Il est à noter que votre message pourrait être déplacé par l'équipe de modération sans préavis si il se trouve dans la mauvaise section.

<strong>Pas de messages ou de fils identiques</strong><br/>
La publication de multiples messages identiques ajoute une surcharge inutile au Forum et fait perdre du temps aux utilisateurs.

<strong>Pas de publication de liens sans introduction</strong><br/>
Si vous souhaitez publier un lien vers une information qui vous semble pertinente, veuillez ajouter un commentaire décrivant l’intérêt de ce lien. Les messages ne contenant qu’un/des liens ne seront pas tolérés.

Il est également apprécié d'utiliser les paragraphes, la grammaire et la conjugaison appropriés qui rendront la lecture de vos messages plus agréable.

<strong>Pas d’usage abusif de majuscules</strong><br/>
Merci de ne pas faire un usage abusif des majuscules dans les titres ou les messages car cela diminue de beaucoup la lisibilité de l’ensemble du Forum.

Si vous avez besoin d’aide à l’utilisation de notre Forum ou en cas de plainte à propos d’une décision de l’administration, veuillez nous contacter par messagerie à l’adresse forumadmin@alco.com.");
        $manager->persist($regle);
        $manager->persist($regle);

        $manager->flush();  //  Flush


    }
}