<?php

namespace TC\UserBundle\Entity;

use FOS\UserBundle\Model\User as BasedUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="tc_user")
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
     * @ORM\Column(type="string", length=255, name="first_name")
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
     * @ORM\Column(type="string", length=255, name="last_name")
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
     * @Assert\Type(type="TC\CoreBundle\Entity\Workspace")
     * @ORM\OneToOne(targetEntity="TC\CoreBundle\Entity\Workspace",  inversedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="workspace_id", referencedColumnName="id", nullable=false)
     *
     * @var Workspace $workspace
     */
    protected $workspace;
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->active = true;
        $this->createdAt = new \DateTime();
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
     * @param TC\CoreBundle\Entity\Workspace $workspace
     */
    public function setWorkspace(\TC\CoreBundle\Entity\Workspace $workspace)
    {
        $this->workspace = $workspace;
    }

    /**
     * Get workspace
     *
     * @return TC\CoreBundle\Entity\Workspace 
     */
    public function getWorkspace()
    {
        return $this->workspace;
    }
}