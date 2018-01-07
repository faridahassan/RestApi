<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Reply
 *
 * @author Farida
 */
namespace BackendBundle\Entity;
use JMS\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * Reply
 * @ORM\Entity(repositoryClass="BackendBundle\DAO\ReplyRepository")
 * @ORM\Table("reply")
 * @ORM\HasLifecycleCallbacks
 */
class Reply {
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"myposts_sr", "myposts_a", "myposts_pi", "post_reply","myteam"})
     */
    protected $id;
     /**
     * @ORM\Column(type="datetime")
     * @Groups({"myposts_sr", "myposts_a", "myposts_pi", "post_reply","myteam"})
     */
     public $created;
      /**
     * @ORM\Column(type="string" , nullable = true )
     * @Groups({"myposts_sr", "myposts_a", "myposts_pi", "post_reply","myteam"})
     */
     protected $message ;
    /**
    * @ORM\Column(type="string", nullable = true)
    * @Groups({"myposts_sr", "myposts_a", "myposts_pi", "post_reply","myteam"})
    */
    protected $is_private;
    /**
    * @ORM\Column(type="string" , nullable = true )
    * @Groups({"myposts_sr", "myposts_a", "myposts_pi", "post_reply","myteam"}) 
    */
    protected $url;
     /**
     * @Assert\File(maxSize="6000000")
     * @Assert\File
     * @var UploadedFile
     * @Groups({"myposts_sr", "myposts_a", "myposts_pi", "post_reply","myteam"})
     */
     private $file;
      /**
     * @ORM\Column(type="string", nullable = true  )
     * @Groups({"myposts_sr", "myposts_a", "myposts_pi", "post_reply","myteam"})
     */
     protected $type;
     /**
     * 
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\Post", inversedBy="replies" ,  cascade={"persist"} )
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id" , onDelete="SET NULL" )
     * @Groups({"myteam"})
     */
     protected $post;
      /**
     * 
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\User", inversedBy="replies" ,  cascade={"persist"} )
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id" , onDelete="SET NULL" )
     * @Groups({"myposts_sr", "myposts_a", "myposts_pi", "post_reply","myteam"})
     */
     protected $user;
     
     /**
     * Constructor
     */
    public function __construct()
    {
        $this->setCreated(new \DateTime('now'));
        $this->is_private = false;
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
      $this->url =  md5($this->file->getClientOriginalName()) . '-' . mt_rand() . '.' . $this->file->guessExtension();
    
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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Response
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
     * Set message
     *
     * @param string $message
     *
     * @return Response
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
     * Set url
     *
     * @param string $url
     *
     * @return Response
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
     * Set type
     *
     * @param string $type
     *
     * @return Response
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
     * Set post
     *
     * @param \BackendBundle\Entity\Post $post
     *
     * @return Response
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
     * @return Response
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
     * Set isPrivate
     *
     * @param string $isPrivate
     *
     * @return Reply
     */
    public function setIsPrivate($isPrivate)
    {
        $this->is_private = $isPrivate;

        return $this;
    }

    /**
     * Get isPrivate
     *
     * @return string
     */
    public function getIsPrivate()
    {
        return $this->is_private;
    }
}
