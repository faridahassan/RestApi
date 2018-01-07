<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User_Link
 *
 * @author Farida
 */
namespace BackendBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
/**
 * User_Link
 * @ORM\Entity(repositoryClass="BackendBundle\DAO\UserLinkRepository")
 * @ORM\Table("user_link")
 * @ORM\HasLifecycleCallbacks
 */
class User_Link {
     /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"usersummery"})
     */
    protected $id;
     /**
     * @ORM\Column(type="string", nullable = true  )
      * @Groups({"usersummery" , "usersummeryedit"})
      * 
     */
     protected $link;
      /**
     * @ORM\Column(type="string", nullable = true  )
       * @Groups({"usersummery" , "usersummeryedit"})
     */
     protected $type;
    /**
     * 
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\User", inversedBy="links" ,  cascade={"persist"} )
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id" , onDelete="SET NULL" )
     */
     protected $user;
 
     /**
     * Constructor
     */
    public function __construct($type , $user , $link )
    {
        $this->type=$type;
        $this->setUser($user);
        $this->link=$link;
     
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return User_Link
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return User_Link
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set user
     *
     * @param \BackendBundle\Entity\User $user
     *
     * @return User_Link
     */
    public function setUser(\BackendBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \BackendBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
