<?php
/**
 * Created by PhpStorm.
 * User: zitouni
 * Date: 22/12/2019
 * Time: 19:00
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="OffreBundle\Entity\Offre", mappedBy="user")
     */
    private $offres;

    public function __construct()
    {
        parent::__construct();
        $this->offres = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getOffres()
    {
        return $this->offres;
    }

    /**
     * @param mixed $offres
     */
    public function setOffres($offres)
    {
        $this->offres = $offres;
    }
}