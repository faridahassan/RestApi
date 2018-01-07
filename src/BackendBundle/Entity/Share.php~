<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Share
 *
 * @author Farida
 */
namespace BackendBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * Share
 * @ORM\Entity(repositoryClass="BackendBundle\DAO\ShareRepository")
 * @ORM\Table("share")
 * @ORM\HasLifecycleCallbacks
 */
class Share {
     /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
     /**
     * @ORM\Column(type="datetime")
     */
     public $created;
     /**
     * 
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\Post", inversedBy="shares" ,  cascade={"persist"} )
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id" , onDelete="SET NULL" )
     */
     protected $post;
      /**
     * 
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\User", inversedBy="shares" ,  cascade={"persist"} )
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id" , onDelete="SET NULL" )
     */
     protected $user;
     /**
     * Constructor
     */
    public function __construct()
    {
       
        $this->setCreated(new \DateTime());
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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Share
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
     * Set post
     *
     * @param \BackendBundle\Entity\Post $post
     *
     * @return Share
     */
    public function setPost(\BackendBundle\Entity\Post $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \BackendBundle\Entity\Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set user
     *
     * @param \BackendBundle\Entity\User $user
     *
     * @return Share
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
