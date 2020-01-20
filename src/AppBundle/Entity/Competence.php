<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Competence
 *
 * @ORM\Table(name="competence")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CompetenceRepository")
 */
class Competence
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
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @var string
     *
     **@ORM\Column(name="description_Competence", type="string", length=255,nullable=true)
     *
     */

    private $competences;


    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", mappedBy ="competences")
     */
    private $userComp;

    /**
     * @ORM\ManyToMany(targetEntity="OffreBundle\Entity\Offre", mappedBy="competences")
     */
    private $offre;


    public function  getCompetences(){

        return $this->competences ;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userComp = new \Doctrine\Common\Collections\ArrayCollection();
        $this->offre = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set competences.
     *
     * @param string|null $competences
     *
     * @return Competence
     */
    public function setCompetences($competences = null)
    {
        $this->competences = $competences;

        return $this;
    }

    /**
     * Add userComp.
     *
     * @param \AppBundle\Entity\User $userComp
     *
     * @return Competence
     */
    public function addUserComp(\AppBundle\Entity\User $userComp)
    {
        $this->userComp[] = $userComp;

        return $this;
    }

    /**
     * Remove userComp.
     *
     * @param \AppBundle\Entity\User $userComp
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeUserComp(\AppBundle\Entity\User $userComp)
    {
        return $this->userComp->removeElement($userComp);
    }

    /**
     * Get userComp.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserComp()
    {
        return $this->userComp;
    }

    /**
     * Add offre.
     *
     * @param \OffreBundle\Entity\Offre $offre
     *
     * @return Competence
     */
    public function addOffre(\OffreBundle\Entity\Offre $offre)
    {
        $this->offre[] = $offre;

        return $this;
    }

    /**
     * Remove offre.
     *
     * @param \OffreBundle\Entity\Offre $offre
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeOffre(\OffreBundle\Entity\Offre $offre)
    {
        return $this->offre->removeElement($offre);
    }

    /**
     * Get offre.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOffre()
    {
        return $this->offre;
    }
}
