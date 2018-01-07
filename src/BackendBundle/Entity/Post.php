<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Post
 *
 * @author Farida
 */
namespace BackendBundle\Entity;
use JMS\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * Post
 * @ORM\Entity(repositoryClass="BackendBundle\DAO\PostRepository")
 * @ORM\Table("post")
 * @ORM\HasLifecycleCallbacks
 */
class Post {
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"myposts_sr", "myposts_a", "myposts_pi","myteam", "usersummery" })
     *  
     */
    protected $id;
   
    /**
     * @ORM\Column(type="string", nullable = true  )
     * @Groups({"myposts_sr", "myposts_a", "myposts_pi","myteam" , "usersummery"})
     */
     protected $type;
      /**
     * @ORM\Column(type="string" , nullable = true )
     * @Groups({"myposts_sr", "myposts_a", "myposts_pi","myteam" , "usersummery"})
     */
     protected $message ;
     /**
     * @ORM\Column(type="datetime")
     * @Groups({"myposts_sr", "myposts_a", "myposts_pi","myteam" , "usersummery"})
     */
     public $created;
    /**
    * @ORM\Column(type="string" , nullable = true )
     * @Groups({"myposts_sr", "myposts_a", "myposts_pi","myteam" , "usersummery"})
    */
    protected $url;
      /**
     *
     * @Assert\File(maxSize="6000000")
     * @Assert\File
     * @var UploadedFile
     */
    private $file;
   /**
    * @ORM\Column(type="string", nullable = true) 
    * @Groups({"myposts_sr", "myposts_a", "myposts_pi","myteam" , "usersummery"}) 
    */
    protected $replies_is_private ;
    /**
    * @ORM\Column(type="string", nullable = true)
    * @Groups({"myposts_sr", "myposts_a", "myposts_pi","myteam" , "usersummery"}) 
    */
     protected $replies_is_closed ;
      /**
     * 
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\User", inversedBy="posts" ,  cascade={"persist"} )
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id" , onDelete="SET NULL" )
     * @Groups({"myposts_sr", "myposts_a", "myposts_pi","myteam"})
     */
     protected $user;
      /**
     * @ORM\OneToMany(targetEntity="BackendBundle\Entity\Share", mappedBy="post" ,  cascade={"persist"}  )
     *  
     */
     private $shares = array();
    /**
     * @ORM\OneToMany(targetEntity="BackendBundle\Entity\Reply", mappedBy="post" ,  cascade={"persist" , "remove"}  )
     * @Groups({"myposts_sr", "myposts_a", "myposts_pi" ,"myteam" , "usersummery"})
     *  
     */
     private $replies = array();
     /**
     * 
     * @Groups({"myposts_sr", "myposts_a", "myposts_pi" ,"myteam" , "usersummery"})
     *  
     */
     public $uniques = array();
    
    /**
     * Constructor
     */
     public function __construct()
    {
       
        $this->setCreated(new \DateTime('now'));
//        $this->getReplyDistinctNames();
       
    }
    public function getReplyDistinctNames ()
    {
        $uniquename = array();
        foreach ($this->replies as $obj) {
            if($obj->getUser())
            {
            $uniquename[$obj->getUser()->getId()] = $obj;
            }
        }
        $i=0;
        foreach ($uniquename as $obj) {
            $this->uniques[$i] = $obj;
            $i++;
        }
    }
    /**
   * Sets file.
   *
   * @param UploadedFile $file
   */
  public function setFile(UploadedFile $file = null)
  {
    if($file === null){
      return;
    }
    $this->url=null;
    $this->file = $file;
  }
 
  /**
   * Get file.
   *
   * @return UploadedFile
   */
  public function getFile()
  {
    return $this->file;
  }
 
  public function getWebPath()
  {
     
    return null === $this->url ? null : $this->getUploadDir() . '/' . $this->url;
  }
 
  public function getAbsolutePath()
  {
    return null === $this->url ? null : $this->getUploadRootDir() . '/' . $this->url;
  }
 
  protected function getUploadRootDir()
  {
    //throw new \Exception('You must implement getUploadRootDir in inherited classes ');
    // the absolute directory path where uploaded documents should be saved
    //return '/var/www/ent/storage/uploaded_file/'.$this->getUploadDir();
     return __DIR__.'/../../../web/'.$this->getUploadDir();
  }
 
  /**
   * @ORM\PrePersist()
   * @ORM\PreUpdate()
   */
  public function preUpload()
  {
    if (null !== $this->file) {
       
      $this->alt = $this->file->getClientOriginalName();
      //Set path of old file to remove it in file system
      $this->originalFilePathToDelete = $this->getAbsolutePath();
//      $this->url = "http://localhost:8000/Symfony_Angularjs/RecopricasRestApi/web/uploads/PostImages/". md5($this->file->getClientOriginalName()) . '-' . mt_rand() . '.' . $this->file->guessExtension();
      $this->url = "http://equation-solutions.com/reciprocas/reciprocasrest/web/uploads/PostImages/". md5($this->file->getClientOriginalName()) . '-' . mt_rand() . '.' . $this->file->guessExtension();
     
    
    }
  }
 
    public function getUploadDir()
    {
      return 'uploads/PostImages/';
    }
    public function compress($src, $dest, $quality)
    {
        $info = getimagesize($src);
        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($src);
        } elseif ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($src);
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($src);
        } else {
           // die('Unknown image file format');
            return ;
        }
        //compress and save file to jpg
        imagejpeg($image, $dest, $quality);
        //return destination file
        return $dest;
    }
  /**
   * @ORM\PostPersist()
   * @ORM\PostUpdate()
   */
  public function upload()
  {
    if (null === $this->file) {
      return;
    }
 
   
    // if there is an error when moving the file, an exception will
    // be automatically thrown by move(). This will properly prevent
    // the entity from being persisted to the database on error
    $d = $this->compress($this->file->getPathname(), $this->file->getPathname(), 50) ;
    $this->file->move( $this->getUploadDir(), $this->url);
 
    unset($this->file);
 
 
    // Remove old file in filesystem
    if (null !== $this->originalFilePathToDelete && file_exists($this->originalFilePathToDelete)) {
      @unlink($this->originalFilePathToDelete);
    }
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
     * Set type
     *
     * @param string $type
     *
     * @return Post
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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Post
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
     * Set url
     *
     * @param string $url
     *
     * @return Post
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set user
     *
     * @param \BackendBundle\Entity\User $user
     *
     * @return Post
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
     * Add share
     *
     * @param \BackendBundle\Entity\Share $share
     *
     * @return Post
     */
    public function addShare(\BackendBundle\Entity\Share $share)
    {
        $this->shares[] = $share;

        return $this;
    }

    /**
     * Remove share
     *
     * @param \BackendBundle\Entity\Share $share
     */
    public function removeShare(\BackendBundle\Entity\Share $share)
    {
        $this->shares->removeElement($share);
    }

    /**
     * Get shares
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getShares()
    {
        return $this->shares;
    }


    

    /**
     * Add reply
     *
     * @param \BackendBundle\Entity\Reply $reply
     *
     * @return Post
     */
    public function addReply(\BackendBundle\Entity\Reply $reply)
    {
        $this->replies[] = $reply;

        return $this;
    }

    /**
     * Remove reply
     *
     * @param \BackendBundle\Entity\Reply $reply
     */
    public function removeReply(\BackendBundle\Entity\Reply $reply)
    {
        $this->replies->removeElement($reply);
    }

    /**
     * Get replies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReplies()
    {
        return $this->replies;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Post
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

  

    /**
     * Set repliesIsPrivate
     *
     * @param string $repliesIsPrivate
     *
     * @return Post
     */
    public function setRepliesIsPrivate($repliesIsPrivate)
    {
        $this->replies_is_private = $repliesIsPrivate;

        return $this;
    }

    /**
     * Get repliesIsPrivate
     *
     * @return string
     */
    public function getRepliesIsPrivate()
    {
        return $this->replies_is_private;
    }

    /**
     * Set repliesIsClosed
     *
     * @param string $repliesIsClosed
     *
     * @return Post
     */
    public function setRepliesIsClosed($repliesIsClosed)
    {
        $this->replies_is_closed = $repliesIsClosed;

        return $this;
    }

    /**
     * Get repliesIsClosed
     *
     * @return string
     */
    public function getRepliesIsClosed()
    {
        return $this->replies_is_closed;
    }
}
