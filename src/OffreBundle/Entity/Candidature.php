<?php

namespace OffreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Candidature
 *
 * @ORM\Table(name="candidature")
 * @ORM\Entity(repositoryClass="OffreBundle\Repository\CandidatureRepository")
 * @Vich\Uploadable()
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
     * @var string
     *
     * @ORM\Column(name="motivation", type="text")
     */
    private $motivation;

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
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="candidature_file", fileNameProperty="fileName", size="fileSize")
     *
     * @var File
     */
    protected $file;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     *
     * @var string
     */
    protected  $fileName;

    /**
     * @ORM\Column(type="integer",nullable=true)
     *
     * @var integer
     */
    protected  $fileSize;

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|UploadedFile $file
     * @throws Exception
     */
    public function setFile(?File $file = null): void
    {
        $this->file = $file;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFileName(?string $imageName): void
    {
        $this->fileName = $imageName;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileSize(?int $fileSize): void
    {
        $this->fileSize = $fileSize;
    }

    public function getFileSize(): ?int
    {
        return $this->fileSize;
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

    /**
     * @return string
     */
    public function getMotivation()
    {
        return $this->motivation;
    }

    /**
     * @param string $motivation
     */
    public function setMotivation($motivation)
    {
        $this->motivation = $motivation;
    }

    public function getStatus(){
        if (is_null($this->getResponse())) {
            return "En attente";
        } elseif ($this->getResponse() === 1) {
            return "Rdv " . $this->getDateMeeting()->format('d/m H:i');
        } else {
            return "Refusé le " . $this->getDateResponse()->format('d/m');
        }
    }
}
