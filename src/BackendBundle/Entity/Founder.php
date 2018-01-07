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
 * @ORM\Entity(repositoryClass="BackendBundle\DAO\FounderRepository")
 * @ORM\Table("founder")
 * @ORM\HasLifecycleCallbacks
 */
class Founder {
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
     * @ORM\Column(type="string" , nullable = true )
     */
     protected $name ;
    
    /**
     * @ORM\Column(type="string" , nullable = true )
     */
     protected $country;
    
    /**
     * @ORM\Column(type="string" , nullable = true )
     */
     protected $email ;
     
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
     * @return Founder
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
     * Set name
     *
     * @param string $name
     *
     * @return Founder
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Founder
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Founder
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
}
