<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;




use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\File\File;

use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Yosimitso\WorkingForumBundle\Entity\UserInterface as YomiInter;


use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser implements YomiInter
{
    /**
     * @var ForumUser
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\ForumUser", inversedBy="userManager" ,cascade={"persist", "remove"})
     */
    private $userForum;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @var int
     *
     * @ORM\Column(name="dateInscrip", type="string", length=255,nullable=true)
     * */
    private $dateInscrip;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=255)
     *
     */

    private $role;


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
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255,nullable=true)
     *
     */

    private $description;


    /**
     * @var string
     *
     * @ORM\Column(name="competences", type="array", length=255,nullable=true)
     *
     */

    private $competences;
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     *
     */


    /**
     * User constructor.
     */


    public function __construct()
    {
        parent::__construct();
        $this->dateInscrip = date("Y-m-d H:i:s");
        //$this->role = "ROLE_USER";
        $this->imageName =  null;

        $this->userForum= new ForumUser($this);

    }




    public function getRoles()
    {
        //['ROLE_ADMIN','ROLE_MODERATOR']
//        echo $this->getRole();
//        die;
        return [$this->getRole()];


        //  return ['ROLE_ADMIN'];
    }

    public function getSalt()
    {

    }


    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }


    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return "id :" . $this->getId() . " Name" . $this->getUsername();
    }

    /**
     * Set dateInscrip.
     *
     * @param \DateTime $dateInscrip
     *
     * @return User
     */
    public function setDateInscrip($dateInscrip)
    {
        $this->dateInscrip = $dateInscrip;

        return $this;
    }

    /**
     * Get dateInscrip.
     *
     * @return \DateTime
     */
    public function getDateInscrip()
    {
        return $this->dateInscrip;
    }

    /**
     * Get role.
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @return string
     */
    public function getCompetences()
    {
        return $this->competences;
    }

    /**
     * @param string $competences
     */
    public function setCompetences($competences)
    {
        $this->competences = $competences;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }









    /**
     * ########################################################################""
     * ##################################################################""""""""""
     */


    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="product_image", fileNameProperty="imageName", size="imageSize")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     *
     * @var string
     */
    private $imageName;

    /**
     * @ORM\Column(type="integer",nullable=true)
     *
     * @var integer
     */
    private $imageSize;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $imageFile
     * @throws \Exception
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



    /*************************************
     * ###########################################################################################
     */





    /**
     * Set updatedAt.
     *
     * @param \DateTime|null $updatedAt
     *
     * @return User
     */
    public function setUpdatedAt($updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set userForum.
     *
     * @param \AppBundle\Entity\ForumUser|null $userForum
     *
     * @return User
     */
    public function setUserForum(\AppBundle\Entity\ForumUser $userForum = null)
    {
        $this->userForum = $userForum;
        $userForum->setUserManager($this);
        return $this;
    }

    /**
     * Get userForum.
     *
     * @return \AppBundle\Entity\ForumUser|null
     */
    public function getUserForum()
    {
        return $this->userForum;
    }


    /**
     * Get banned.
     *
     * @return bool|null
     */
    public function isBanned()
    {
        return $this->userForum->getBanned();
      //  return !$this->isEnabled();
    }

    public function getAvatarUrl()
    {
        // TODO: Implement getAvatarUrl() method.
        return $this->userForum->getAvatarUrl();
    }

    public function getNbPost()
    {
        // TODO: Implement getNbPost() method.
        return $this->userForum->getNbPost();
    }

    public function setAvatarUrl($avatar_url)
    {
        // TODO: Implement setAvatarUrl() method.
        return $this->userForum->setAvatarUrl($avatar_url);
    }

    public function setNbPost($nbPost)
    {
        // TODO: Implement setNbPost() method.
        return $this->userForum->setNbPost($nbPost);
    }

    public function addNbPost($nb)
    {
        // TODO: Implement addNbPost() method.
        return $this->userForum->addNbPost($nb);
    }

    public function getEmailAddress()
    {
        // TODO: Implement getEmailAddress() method.
        return $this->getEmail();
    }

    public function getLastReplyDate(){
        return $this->userForum->getLastReplyDate();
    }

    /**
     * @param bool $banned
     *
     *
     */
    public function setBanned($banned)
    {
        $this->userForum->setBanned($banned);
    }

    /**
     * @param \DateTime $lastReplyDate
     *
     *
     */
    public function setLastReplyDate($lastReplyDate)
    {
        $this->userForum->setLastReplyDate($lastReplyDate);
    }
}
