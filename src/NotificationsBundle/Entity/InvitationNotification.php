<?php
namespace NotificationsBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use NotificationsBundle\Entity\Notification as Notification;

/**
 * @ORM\Entity
 */
class InvitationNotification extends Notification {
     /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     protected $id;
    
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
     * @return InvitationNotification
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
     * @return InvitationNotification
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
     * @return InvitationNotification
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
     * Set user
     *
     * @param \BackendBundle\Entity\User $user
     *
     * @return InvitationNotification
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

    /**
     * Set toUser
     *
     * @param \BackendBundle\Entity\User $toUser
     *
     * @return InvitationNotification
     */
    public function setToUser(\BackendBundle\Entity\User $toUser = null)
    {
        $this->ToUser = $toUser;

        return $this;
    }

    /**
     * Get toUser
     *
     * @return \BackendBundle\Entity\User
     */
    public function getToUser()
    {
        return $this->ToUser;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return InvitationNotification
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

    /**
     * Set fromUser
     *
     * @param \BackendBundle\Entity\User $fromUser
     *
     * @return InvitationNotification
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
}
