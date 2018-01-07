<?php
namespace NotificationsBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;

use NotificationsBundle\Entity\Notification as Notification;


/**
 * @ORM\Entity
 */
class ReplyNotification extends Notification {
     /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     protected $id;
    
    /**
     * 
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\Post" )
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id" , onDelete="SET NULL"  )
     */
     protected $post;
   
     /**
     * 
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\User", inversedBy="notifyby" )
     * @ORM\JoinColumn(name="fromuser_id", referencedColumnName="id" , onDelete="SET NULL"   )
     */
     protected $FromUser;

     
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
     * @return ReplyNotification
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
     * Set type
     *
     * @param string $type
     *
     * @return ReplyNotification
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
     * Set seen
     *
     * @param string $seen
     *
     * @return ReplyNotification
     */
    public function setSeen($seen)
    {
        $this->seen = $seen;

        return $this;
    }

    /**
     * Get seen
     *
     * @return string
     */
    public function getSeen()
    {
        return $this->seen;
    }

    /**
     * Set post
     *
     * @param \BackendBundle\Entity\Post $post
     *
     * @return ReplyNotification
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
     * Set fromUser
     *
     * @param \BackendBundle\Entity\User $fromUser
     *
     * @return ReplyNotification
     */
    public function setFromUser(\BackendBundle\Entity\User $fromUser = null)
    {
        $this->FromUser = $fromUser;

        return $this;
    }

    /**
     * Get fromUser
     *
     * @return \BackendBundle\Entity\User
     */
    public function getFromUser()
    {
        return $this->FromUser;
    }

    /**
     * Set toUser
     *
     * @param \BackendBundle\Entity\User $toUser
     *
     * @return ReplyNotification
     */
    public function setToUser(\BackendBundle\Entity\User $toUser = null)
    {
        $this->toUser = $toUser;

        return $this;
    }

    /**
     * Get toUser
     *
     * @return \BackendBundle\Entity\User
     */
    public function getToUser()
    {
        return $this->toUser;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return ReplyNotification
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
}
