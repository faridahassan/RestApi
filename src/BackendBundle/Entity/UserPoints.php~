<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserPoints
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
 * @ORM\Entity(repositoryClass="BackendBundle\DAO\UserPointsRepository")
 * @ORM\Table("user_points")
 * @ORM\HasLifecycleCallbacks
 */
class UserPoints {
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
      * 
      * 
     */
     protected $points;
      /**
     * @ORM\Column(type="datetime")
     * 
     */
     public $created;
    /**
     * 
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\User", inversedBy="pointshistory" ,  cascade={"persist"} )
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id" , onDelete="SET NULL" )
     */
     protected $user;
 
     /**
     * Constructor
     */
    public function __construct( $user  )
    {
        $this->setCreated(new \DateTime('now'));
        $this->setUser($user);
       
     
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
     * Set points
     *
     * @param string $points
     *
     * @return UserPoints
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return string
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return UserPoints
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set user
     *
     * @param \BackendBundle\Entity\User $user
     *
     * @return UserPoints
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
