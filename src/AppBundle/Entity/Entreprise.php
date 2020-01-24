<?php
/**
 * Created by PhpStorm.
 * User: zitouni
 * Date: 20/01/2020
 * Time: 20:35
 */

namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;



class Entreprise extends User
{


    public function __construct()
    {
        parent::__construct();
        $this->dateInscrip = date("Y-m-d H:i:s");
        $this->role = "ROLE_USER";
        $this->imageName =  null;
        $this->offres = new ArrayCollection();
        $this->userForum= new ForumUser($this);
        $this->status = "Entreprise";
    }
}