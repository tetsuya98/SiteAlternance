<?php

namespace OffreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Candidature
 *
 * @ORM\Table(name="candidature")
 * @ORM\Entity(repositoryClass="OffreBundle\Repository\CandidatureRepository")
 */
class Candidature
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
     * @var \DateTime
     *
     * @ORM\Column(name="datePostule", type="datetime")
     */
    private $datePostule;

    /**
     * @var int|null
     *
     * @ORM\Column(name="response", type="smallint", nullable=true)
     */
    private $response;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateResponse", type="datetime", nullable=true)
     */
    private $dateResponse;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateMeeting", type="datetime", nullable=true)
     */
    private $dateMeeting;

    /**
     * @var \OffreBundle\Entity\Offre
     *
     * @ORM\ManyToOne(targetEntity="OffreBundle\Entity\Offre", inversedBy="candidatures")
     * @ORM\JoinColumn(name="offre_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $offre;

    /**
     * @var \AppBundle\Entity\Etudiant
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Etudiant", inversedBy="candidatures")
     * @ORM\JoinColumn(name="etudiant_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $etudiant;


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
     * Set datePostule.
     *
     * @param \DateTime $datePostule
     *
     * @return Candidature
     */
    public function setDatePostule($datePostule)
    {
        $this->datePostule = $datePostule;

        return $this;
    }

    /**
     * Get datePostule.
     *
     * @return \DateTime
     */
    public function getDatePostule()
    {
        return $this->datePostule;
    }

    /**
     * Set response.
     *
     * @param int|null $response
     *
     * @return Candidature
     */
    public function setResponse($response = null)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Get response.
     *
     * @return int|null
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set dateResponse.
     *
     * @param \DateTime|null $dateResponse
     *
     * @return Candidature
     */
    public function setDateResponse($dateResponse = null)
    {
        $this->dateResponse = $dateResponse;

        return $this;
    }

    /**
     * Get dateResponse.
     *
     * @return \DateTime|null
     */
    public function getDateResponse()
    {
        return $this->dateResponse;
    }

    /**
     * Set dateMeeting.
     *
     * @param \DateTime|null $dateMeeting
     *
     * @return Candidature
     */
    public function setDateMeeting($dateMeeting = null)
    {
        $this->dateMeeting = $dateMeeting;

        return $this;
    }

    /**
     * Get dateMeeting.
     *
     * @return \DateTime|null
     */
    public function getDateMeeting()
    {
        return $this->dateMeeting;
    }

    /**
     * @return Offre
     */
    public function getOffre(): Offre
    {
        return $this->offre;
    }

    /**
     * @param Offre $offre
     */
    public function setOffre(Offre $offre): void
    {
        $this->offre = $offre;
    }

    /**
     * @return \AppBundle\Entity\Etudiant
     */
    public function getEtudiant(): \AppBundle\Entity\Etudiant
    {
        return $this->etudiant;
    }

    /**
     * @param \AppBundle\Entity\Etudiant $etudiant
     */
    public function setEtudiant(\AppBundle\Entity\Etudiant $etudiant): void
    {
        $this->etudiant = $etudiant;
    }
}
