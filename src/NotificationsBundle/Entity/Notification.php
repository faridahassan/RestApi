<?php
namespace NotificationsBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\MappedSuperclass
 */
class Notification {
     /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     protected $id;
    

    /**
     * 
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\User", inversedBy="notifyto" )
     * @ORM\JoinColumn(name="touser_id", referencedColumnName="id" , onDelete="SET NULL"   )
     */
     protected $toUser;
     
    
    /**
     * @ORM\Column(type="datetime",  nullable = true )
     */
     protected $created;
      /**
     * @ORM\Column(type="string" , nullable = true  )
     */
     protected $type;

     /**
     * @ORM\Column(type="string" , nullable = true  )
     */
     protected $text;
    /**
     * @ORM\Column(type="boolean" , nullable = true  )
     */
     protected $seen = false;
   
     
      /**
     * Constructor
     */
    public function __construct()
    {
        
        $this->setCreated(new \DateTime());
    }


}
