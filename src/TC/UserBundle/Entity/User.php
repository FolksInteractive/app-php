<?php

namespace TC\UserBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BasedUser;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use TC\CoreBundle\Entity\Workspace;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tc_user")
 * @Vich\Uploadable
 * 
 */
class User extends BasedUser {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     * @var integer $id
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, name="first_name")
     * @Assert\NotBlank(message="Please enter your first name.")
     * @Assert\Length(
     *      min = "2",
     *      max = "50",
     *      minMessage = "Your first name must be at least {{ limit }} characters length",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters length", 
     *      groups={"Registration", "Profile"}
     * )
     * 
     * @var string $firstName
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, name="last_name")
     * @Assert\NotBlank(message="Please enter your last name.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *      min = "2",
     *      max = "50",
     *      minMessage = "Your last name must be at least {{ limit }} characters length",
     *      maxMessage = "Your last name cannot be longer than {{ limit }} characters length", 
     *      groups={"Registration", "Profile"}
     * )
     *
     * @var string $lastName
     */
    protected $lastName;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     * 
     * @var DateTime $createdAt
     */
    protected $createdAt;
    
    /**
     * @ORM\Column(type="datetime", name="last_update_at")
     * 
     * @var DateTime $lastUpdateAt
     */
    protected $lastUpdateAt;
    
    /**
     * @Assert\Type(type="TC\CoreBundle\Entity\Workspace")
     * @ORM\OneToOne(targetEntity="TC\CoreBundle\Entity\Workspace",  mappedBy="user", cascade={"persist", "remove"})
     *
     * @var Workspace $workspace
     */
    protected $workspace;
    
    /**
     * @Assert\File(
     *     maxSize="1M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="user_avatar", fileNameProperty="avatarName")
     *
     * @var File $avatar
     */
    protected $avatar;

    /**
     * @ORM\Column(type="string", length=255, name="avatar_name", nullable=true)
     *
     * @var string $avatarName
     */
    protected $avatarName;
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->active = true;
        $this->createdAt = new DateTime();
    }
    

    /**
     * @ORM\PreUpdate()
     * @ORM\PrePersist()
     */
     public function preUpdate(){
        $this->lastUpdateAt = new Datetime();
    }
    
    /**
     * Gets the full name of the user.
     * 
     * @return string The full name
     */
    public function getFullName() {
        return sprintf('%s %s', $this->firstName, $this->lastName);
    }
    
    /**
     * Gets the short name of the user.
     * 
     * @return string The short name
     */
    public function getShortName() {
        return sprintf('%s %s.', $this->firstName, substr($this->lastName,0,1));
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
     * Set firstName
     *
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }
    
    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set lastUpdateAt
     *
     * @param datetime $lastUpdateAt
     */
    public function setLastUpdateAt($lastUpdateAt)
    {
        $this->lastUpdateAt = $lastUpdateAt;
    }
    
    /**
     * Get lastUpdateAt
     *
     * @return datetime 
     */
    public function getLastUpdateAt()
    {
        return $this->lastUpdateAt;
    }
    
    /**
     * Sets the email and the username.
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        parent::setEmail($email);
        $this->setUsername($email);
    }

    /**
     * Set workspace
     *
     * @param Workspace $workspace
     */
    public function setWorkspace(Workspace $workspace)
    {
        $this->workspace = $workspace;
    }

    /**
     * Get workspace
     *
     * @return Workspace 
     */
    public function getWorkspace()
    {
        return $this->workspace;
    }

    /**
     * Set avatarName
     *
     * @param string $avatarName
     * @return User
     */
    public function setAvatarName($avatarName)
    {
        $this->avatarName = $avatarName;
        
        return $this;
    }

    /**
     * Get avatarName
     *
     * @return string 
     */
    public function getAvatarName()
    {
        return $this->avatarName;
    }
    
    /**
     * Set avatarName
     *
     * @param UploadedFile $avatar
     * @return User
     */
    public function setAvatar(  UploadedFile $avatar = null)
    {
        if( $avatar == null )
            return;
        
        $this->avatar = $avatar;
        // This make the entity dirty to Doctrine
        // View this issue https://github.com/dustin10/VichUploaderBundle/issues/123
        $this->lastUpdateAt = new \DateTime(); 
        
        return $this;
    }

    /**
     * Get avatar
     *
     * @return UploadedFile 
     */
    public function getAvatar()
    {
        return $this->avatar;
    }
}