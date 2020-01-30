<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User as monUtilisateur;

use  Yosimitso\WorkingForumBundle\Entity\User as Yosimitso;


/**
 * ForumUser
 *
 * @ORM\Table(name="forum_user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ForumUserRepository")
 */
class ForumUser extends Yosimitso
{
    /**
     * @var monUtilisateur
     * @ORM\OneToOne(targetEntity=monUtilisateur::class, mappedBy="userForum" ,cascade={"persist", "remove"})
     */
    private $userManager;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;





    /**
     * ForumUser constructor.
     * @param monUtilisateur $userManager
     */
    public function __construct(monUtilisateur $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->userManager->getUsername();
    }


    /**
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->userManager->getEmail();
    }


    /**
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

     //   return $this;
    }



    public function getRoles()
    {
        return $this->userManager->getRoles();

    }





    /**
     * Get banned.
     *
     * @return bool|null
     */
    public function getBanned()
    {
        return $this->banned;
    }

    /**
     * Set userManager.
     *
     * @param \AppBundle\Entity\User|null $userManager
     *
     * @return ForumUser
     */
    public function setUserManager(\AppBundle\Entity\User $userManager = null)
    {
        $this->userManager = $userManager;

        return $this;
    }

    /**
     * Get userManager.
     *
     * @return \AppBundle\Entity\User|null
     */
    public function getUserManager()
    {
        return $this->userManager;
    }

}
