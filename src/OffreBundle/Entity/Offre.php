<?php

namespace OffreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Offre
 *
 * @ORM\Table(name="offre")
 * @ORM\Entity(repositoryClass="OffreBundle\Repository\OffreRepository")
 */
class Offre
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="offres")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $user;


    /**
     * @var string
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Competence", mappedBy ="offre" )
     */

    private $competences;


    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="crdate", type="datetime")
     */
    private $crdate;

    /**
     * @var int
     *
     * @ORM\Column(name="nbSemaine", type="integer")
     */
    private $nbSemaine;

    /**
     * @var int
     *
     * @ORM\Column(name="nbVue", type="integer")
     */
    private $nbVue;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return Offre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Offre
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set crdate
     *
     * @param \DateTime $crdate
     *
     * @return Offre
     */
    public function setCrdate($crdate)
    {
        $this->crdate = $crdate;

        return $this;
    }

    /**
     * Get crdate
     *
     * @return \DateTime
     */
    public function getCrdate()
    {
        return $this->crdate;
    }

    /**
     * Set nbSemaine
     *
     * @param integer $nbSemaine
     *
     * @return Offre
     */
    public function setNbSemaine($nbSemaine)
    {
        $this->nbSemaine = $nbSemaine;

        return $this;
    }

    /**
     * Get nbSemaine
     *
     * @return int
     */
    public function getNbSemaine()
    {
        return $this->nbSemaine;
    }

    /**
     * Set nbVue
     *
     * @param integer $nbVue
     *
     * @return Offre
     */
    public function setNbVue($nbVue)
    {
        $this->nbVue = $nbVue;

        return $this;
    }

    /**
     * Get nbVue
     *
     * @return int
     */
    public function getNbVue()
    {
        return $this->nbVue;
    }

    /**
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \AppBundle\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Set competences.
     *
     * @param \AppBundle\Entity\Competence|null $competences
     *
     * @return Offre
     */
    public function setCompetences(\AppBundle\Entity\Competence $competences = null)
    {
        $this->competences = $competences;
        $this->competences->setCompetences($this);
        return $this;
    }

    /**
     * Get competences.
     *
     * @return \AppBundle\Entity\Competence|null
     */
    public function getCompetences()
    {
        return $this->competences;
    }
}
