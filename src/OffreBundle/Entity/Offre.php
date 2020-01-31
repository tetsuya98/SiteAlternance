<?php

namespace OffreBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Offre
 *
 * @ORM\Table(name="offre")
 * @ORM\Entity(repositoryClass="OffreBundle\Repository\OffreRepository")
 * @Vich\Uploadable()
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
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Competence")
     */

    private $competences;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var TypeContrat
     *
     * @ORM\ManyToOne(targetEntity="OffreBundle\Entity\TypeContrat")
     */
    private $typeContrat;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var DateTime
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
     * @param DateTime $crdate
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
     * @return DateTime
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

        $this->addCompetence($competences);
        return $this;
    }


    public function getCompetences()
    {
        return $this->competences;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->competences = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function addCompetence(\AppBundle\Entity\Competence $competence)
    {
        if ($this->competences->contains($competence)) {
            return;
        }
        $this->competences[] = $competence;
    }

    public function removeCompetence(\AppBundle\Entity\Competence $competence)
    {
        if (!$this->competences->contains($competence)) {
            return;
        }
        $this->competences->removeElement($competence);
    }

    /**
     * @return TypeContrat
     */
    public function getTypeContrat()
    {
        return $this->typeContrat;
    }

    /**
     * @param TypeContrat $typeContrat
     */
    public function setTypeContrat(TypeContrat $typeContrat): void
    {
        $this->typeContrat = $typeContrat;
    }


    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="offre_image", fileNameProperty="imageName", size="imageSize")
     *
     * @var File
     */
    protected $imageFile;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     *
     * @var string
     */
    protected  $imageName;

    /**
     * @ORM\Column(type="integer",nullable=true)
     *
     * @var integer
     */
    protected  $imageSize;

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|UploadedFile $imageFile
     * @throws Exception
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }
}
