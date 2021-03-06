<?php 
namespace BackendBundle\Entity;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
/**
 * User
 * @ORM\Entity(repositoryClass="BackendBundle\DAO\UserRepository")
 * @ORM\Table("users")
 * @ORM\HasLifecycleCallbacks
 */
class User extends BaseUser  implements ParticipantInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"myposts_sr", "myposts_a", "myposts_pi", "post_reply","myteam", "usersummery", "usersummeryedit" , "message"})
     */
    protected $id;
    /**
     * @ORM\Column(type="string" , nullable = true )
     * @Groups({"myposts_sr", "myposts_a", "myposts_pi", "post_reply","myteam", "usersummery" , "usersummeryedit" ,"message"} )
     */
     protected $firstname ;
     /**
     * @ORM\Column(type="string" , nullable = true )
     * @Groups({"usersummery" , "usersummeryedit","myteam" , "myposts_sr", "myposts_a", "myposts_pi", "post_reply","message"   })
     */
     protected $lastname ;
     
      /**
     * @ORM\Column(type="string" , nullable = true )
     * @Groups({"myposts_sr", "myposts_a", "myposts_pi", "post_reply","myteam", "usersummery" , "usersummeryedit"})
     */
     protected $businessname ;
      /**
     * @ORM\Column(type="string" , nullable = true )
     * @Groups({"myposts_sr", "myposts_a", "myposts_pi", "post_reply","myteam", "usersummery" , "usersummeryedit"})
     */
     protected $industry ;
    /**
    * 
    * @ORM\Column(type="string" , nullable = true )
    *  @Groups({"myposts_sr", "myposts_a", "myposts_pi", "post_reply","myteam", "usersummery", "usersummeryedit"})
    * 
    */
     protected $businessoverview ;
     /**
    * 
    * @ORM\Column(type="string" , nullable = true )
    *  @Groups({"myposts_sr", "myposts_a", "myposts_pi", "post_reply","myteam", "usersummery", "usersummeryedit"})
    * 
    */
     protected $businessurl ;
     /**
     * @ORM\Column(type="string" , nullable = true )
     * @Groups({"myposts_sr", "myposts_a", "myposts_pi", "post_reply","myteam", "usersummery" , "usersummeryedit"})
     */
     protected $country ;
     /**
     * @ORM\Column(type="string" , nullable = true )
     * @Groups({"myposts_sr", "myposts_a", "myposts_pi", "post_reply","myteam", "usersummery" , "usersummeryedit"})
     */
     protected $city ;

     /**
     * This is the code it will use to invite other people
     * @ORM\Column(type="string" , nullable = true )
     */
     protected $invitationCode ;

     /**
     * This is the code it will use to reference their inviter
     * @ORM\Column(type="string" , nullable = true )
     */
     protected $referenceCode ;
     
     /**
     * @ORM\Column(type="boolean" , nullable = true )
     */
     private $subscribed = true;
       /**
     * @ORM\Column(type="integer" , nullable = true )
     * @Groups({"usersummery" , "usersummeryedit"} )
     */
     protected $noOfinvitations = 0;
       /**
     * @ORM\Column(type="integer" , nullable = true )
     * @Groups({"usersummery"})
     */
     protected $points = 0;
      /**
     * @ORM\Column(type="integer" , nullable = true )
       * @Groups({"usersummery" , "usersummeryedit"})
     */
     protected $phone;
      /**
    * @ORM\Column(type="string" , nullable = true )
    * @Groups({"usersummery"})
    */
    protected $badge;
     /**
    * @ORM\Column(type="string" , nullable = true )
    * @Groups({"myposts_sr", "myposts_a", "myposts_pi", "post_reply","myteam","message" ,"usersummery" , "usersummeryedit"} )
    */
    protected $image;
     /**
    * @ORM\Column(type="string" , nullable = true )
    * @Groups({"myposts_sr", "myposts_a", "myposts_pi", "post_reply","myteam", "usersummery" , "usersummeryedit"} )
   
    */
    protected $logourl;
    
     /**
     *
     * @Assert\File(maxSize="6000000")
     * @Assert\File
     * @var UploadedFile
      * @Groups({"usersummery" , "usersummeryedit"})
     */
    private $file;
     /**
     *
     * @Assert\File(maxSize="6000000")
     * @Assert\File
     * @var UploadedFile
     * @Groups({"usersummery" , "usersummeryedit"})
     */
    protected $logofile;

    /**
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\User")
     */
    private $parent;
    
    /**
     * @ORM\OneToMany(targetEntity="BackendBundle\Entity\User_Link", mappedBy="user" ,  cascade={"persist"}  )
     * @Groups({"usersummery"  , "usersummeryedit"})
     */
     private $links = array();
      /**
     * @ORM\OneToMany(targetEntity="BackendBundle\Entity\UserPoints", mappedBy="user" ,  cascade={"persist"}  )
     * 
     */
     private $pointshistory = array();
      /**
     * @ORM\OneToMany(targetEntity="BackendBundle\Entity\Post", mappedBy="user" ,  cascade={"persist"}  )
     *   @Groups({"usersummery"  , "usersummeryedit"})
     */
     private $posts = array();
      /**
     * @ORM\OneToMany(targetEntity="BackendBundle\Entity\Share", mappedBy="user" ,  cascade={"persist"}  )
     *  
     */
     private $shares = array();
    /**
     * @ORM\OneToMany(targetEntity="BackendBundle\Entity\Reply", mappedBy="user" ,  cascade={"persist"}  )
     *  
     */
     private $replies = array();


     /**
     * @ORM\Column(type="datetime")
     * @Groups({"usersummery"  , "usersummeryedit"})
     */
     public $created;
     public $filetype;

   public function __toString() {
        return $this->id;
    }

    public function __construct()
    {
        parent::__construct();
        // generate identifier only once, here a 6 characters length code
        $this->invitationCode = substr(md5(uniqid(rand(), true)), 0, 6);
        $this->image = "NAN";
        $this->badge = "NAN";
        $this->logourl = "NAN";
        $this->noOfinvitations = 0;
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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
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
    $this->image="NAN";
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
     /**
   * Sets logofile.
   *
   * @param UploadedFile $file
   */
  public function setLogofile(UploadedFile $file = null)
  {
    if($file === null){
      return;
    }
//    $this->logourl="NAN";
   $this->logofile = $file;
  }
 
  /**
   * Get logofile.
   *
   * @return UploadedFile
   */
  public function getLogofile()
  {
    return $this->logofile;
  }
  public function getWebPath()
  {
    if(null !== $this->file)
    {
    return null === $this->image ? null : $this->getUploadDir() . '/' . $this->image;
    }
    if(null !== $this->logofile)
    {
       return null === $this->logourl ? null : $this->getUploadDir() . '/' . $this->logourl; 
    }
  }
 
  public function getAbsolutePath()
  {
    if(null !== $this->file)
    {
    return null === $this->image ? null : $this->getUploadRootDir() . '/' . $this->image;
    }
    if(null !== $this->logofile)
    {
        return null === $this->logourl ? null : $this->getUploadRootDir() . '/' . $this->logourl;
    }
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
//      $this->image= "http://localhost:8000/Symfony_Angularjs/RecopricasRestApi/web/uploads/UserImages/".md5($this->file->getClientOriginalName()) . '-' . mt_rand() . '.' . $this->file->guessExtension();
      $this->image = "http://equation-solutions.com/reciprocas/reciprocasrest/web/uploads/UserImages/".md5($this->file->getClientOriginalName()) . '-' . mt_rand() . '.' . $this->file->guessExtension();
//    print_r($this->image);
    }
    
      
    if (null !== $this->logofile) {
       
      $this->alt = $this->logofile->getClientOriginalName();
      //Set path of old file to remove it in file system
      $this->originalFilePathToDelete = $this->getAbsolutePath();
//      $this->logourl= "http://localhost:8000/Symfony_Angularjs/RecopricasRestApi/web/uploads/UserImages/".md5($this->logofile->getClientOriginalName()) . '-' . mt_rand() . '.' . $this->logofile->guessExtension();
      $this->logourl = "http://equation-solutions.com/reciprocas/reciprocasrest/web/uploads/UserImages/".md5($this->logofile->getClientOriginalName()) . '-' . mt_rand() . '.' . $this->logofile->guessExtension();
//            print_r($this->logourl);exit;
      
//      print_r($this->image);
    }
  }
 
  public function getUploadDir()
  {
    return 'uploads/UserImages/';
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
    if (null === $this->file && null === $this->logofile) {
      return;
    }
   
   
    // if there is an error when moving the file, an exception will
    // be automatically thrown by move(). This will properly prevent
    // the entity from being persisted to the database on error
    if (null !== $this->file)
    {
    $d = $this->compress($this->file->getPathname(), $this->file->getPathname(), 50) ;
    $this->file->move( $this->getUploadDir(), $this->image);
    unset($this->file);
    }
    if (null !== $this->logofile)
    {
    $d = $this->compress($this->logofile->getPathname(), $this->logofile->getPathname(), 50) ;
//    print_r( $this->logourl);exit;
    $this->logofile->move( $this->getUploadDir(), $this->logourl);
    unset($this->logofile);
    }
 
    
   
 
    // Remove old file in filesystem
    if (null !== $this->originalFilePathToDelete && file_exists($this->originalFilePathToDelete)) {
      @unlink($this->originalFilePathToDelete);
    }
    
  }

    /**
     * Set businessname
     *
     * @param string $businessname
     *
     * @return User
     */
    public function setBusinessname($businessname)
    {
        $this->businessname = $businessname;

        return $this;
    }

    /**
     * Get businessname
     *
     * @return string
     */
    public function getBusinessname()
    {
        return $this->businessname;
    }

    /**
     * Set industry
     *
     * @param string $industry
     *
     * @return User
     */
    public function setIndustry($industry)
    {
        $this->industry = $industry;

        return $this;
    }

    /**
     * Get industry
     *
     * @return string
     */
    public function getIndustry()
    {
        return $this->industry;
    }

    /**
     * Set businessoverview
     *
     * @param string $businessoverview
     *
     * @return User
     */
    public function setBusinessoverview($businessoverview)
    {
        $this->businessoverview = $businessoverview;

        return $this;
    }

    /**
     * Get businessoverview
     *
     * @return string
     */
    public function getBusinessoverview()
    {
        return $this->businessoverview;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return User
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
     * Set city
     *
     * @param string $city
     *
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set noOfinvitations
     *
     * @param integer $noOfinvitations
     *
     * @return User
     */
    public function setNoOfinvitations($noOfinvitations)
    {
        $this->noOfinvitations = $noOfinvitations;

        return $this;
    }

    /**
     * Get noOfinvitations
     *
     * @return integer
     */
    public function getNoOfinvitations()
    {
        return $this->noOfinvitations;
    }

    /**
     * Set points
     *
     * @param integer $points
     *
     * @return User
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set phone
     *
     * @param integer $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return integer
     */
    public function getPhone()
    {
        return $this->phone;
    }

   

    
    /**
     * Add link
     *
     * @param \BackendBundle\Entity\User_Link $link
     *
     * @return User
     */
    public function addLink(\BackendBundle\Entity\User_Link $link)
    {
        $this->links[] = $link;

        return $this;
    }

    /**
     * Remove link
     *
     * @param \BackendBundle\Entity\User_Link $link
     */
    public function removeLink(\BackendBundle\Entity\User_Link $link)
    {
        $this->links->removeElement($link);
    }

    /**
     * Get links
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Add post
     *
     * @param \BackendBundle\Entity\User_Link $post
     *
     * @return User
     */
    public function addPost(\BackendBundle\Entity\User_Link $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param \BackendBundle\Entity\User_Link $post
     */
    public function removePost(\BackendBundle\Entity\User_Link $post)
    {
        $this->posts->removeElement($post);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Add share
     *
     * @param \BackendBundle\Entity\Share $share
     *
     * @return User
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
     * @return User
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
     * Set invitationCode
     *
     * @param string $invitationCode
     *
     * @return User
     */
    public function setInvitationCode($invitationCode)
    {
        $this->invitationCode = $invitationCode;

        return $this;
    }

    /**
     * Get invitationCode
     *
     * @return string
     */
    public function getInvitationCode()
    {
        return $this->invitationCode;
    }

    /**
     * Set referenceCode
     *
     * @param string $referenceCode
     *
     * @return User
     */
    public function setReferenceCode($referenceCode)
    {
        $this->referenceCode = $referenceCode;

        return $this;
    }

    /**
     * Get referenceCode
     *
     * @return string
     */
    public function getReferenceCode()
    {
        return $this->referenceCode;
    }

    /**
     * Set subscribed
     *
     * @param boolean $subscribed
     *
     * @return User
     */
    public function setSubscribed($subscribed)
    {
        $this->subscribed = $subscribed;
    }
    /**
     * Set parent
     *
     * @param \BackendBundle\Entity\User $parent
     *
     * @return User
     */
    public function setParent(\BackendBundle\Entity\User $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get subscribed
     *
     * @return boolean
     */
    public function getSubscribed()
    {
        return $this->subscribed;
    }
    /**
     * Get parent
     *
     * @return \BackendBundle\Entity\User
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add pointshistory
     *
     * @param \BackendBundle\Entity\User_Link $pointshistory
     *
     * @return User
     */
    public function addPointshistory(\BackendBundle\Entity\User_Link $pointshistory)
    {
        $this->pointshistory[] = $pointshistory;

        return $this;
    }

    /**
     * Remove pointshistory
     *
     * @param \BackendBundle\Entity\User_Link $pointshistory
     */
    public function removePointshistory(\BackendBundle\Entity\User_Link $pointshistory)
    {
        $this->pointshistory->removeElement($pointshistory);
    }

    /**
     * Get pointshistory
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPointshistory()
    {
        return $this->pointshistory;
    }

    /**
     * Set businessurl
     *
     * @param string $businessurl
     *
     * @return User
     */
    public function setBusinessurl($businessurl)
    {
        $this->businessurl = $businessurl;

        return $this;
    }

    /**
     * Get businessurl
     *
     * @return string
     */
    public function getBusinessurl()
    {
        return $this->businessurl;
    }

    /**
     * Set logourl
     *
     * @param string $logourl
     *
     * @return User
     */
    public function setLogourl($logourl)
    {
        $this->logourl = $logourl;

        return $this;
    }

    /**
     * Get logourl
     *
     * @return string
     */
    public function getLogourl()
    {
        return $this->logourl;
    }

   

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return User
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
     * Set badge
     *
     * @param string $badge
     *
     * @return User
     */
    public function setBadge($badge)
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * Get badge
     *
     * @return string
     */
    public function getBadge()
    {
        return $this->badge;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return User
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }
}
