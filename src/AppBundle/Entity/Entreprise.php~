<?php

namespace AppBundle\Entity;
use AppBundle\Entity\User as monUtilisateur;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entreprise
 *
 * @ORM\Table(name="entreprise")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EntrepriseRepository")
 */
class Entreprise
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var User
     * @ORM\OneToOne(targetEntity=monUtilisateur::class, mappedBy="userEtudiant" ,cascade={"persist", "remove"})
     */
    private $userManager;


    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     *
     * @var string
     */
    private $sirt;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     *
     * @var string
     */
    private $adresse;
    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     *
     * @var string
     */
    private $NomUtilisateur;

    public function __construct(User $user)
    {
        $this->userManager= $user;

    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUserManager(): User
    {
        return $this->userManager;
    }

    /**
     * @param User $userManager
     */
    public function setUserManager(User $userManager): void
    {
        $this->userManager = $userManager;
    }

    /**
     * @return string
     */
    public function getSirt(): string
    {
        return $this->sirt;
    }

    /**
     * @param string $sirt
     */
    public function setSirt(string $sirt): void
    {
        $this->sirt = $sirt;
    }

    /**
     * @return string
     */
    public function getAdresse(): string
    {
        return $this->adresse;
    }

    /**
     * @param string $adresse
     */
    public function setAdresse(string $adresse): void
    {
        $this->adresse = $adresse;
    }

    /**
     * @return string
     */
    public function getNomUtilisateur(): string
    {
        return $this->NomUtilisateur;
    }

    /**
     * @param string $NomUtilisateur
     */
    public function setNomUtilisateur(string $NomUtilisateur): void
    {
        $this->NomUtilisateur = $NomUtilisateur;
    }



}
