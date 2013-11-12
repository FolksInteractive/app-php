<?php

namespace TC\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Contact
 *
 * @ORM\Table(name="tc_contact")
 * @ORM\Entity(repositoryClass="TC\CoreBundle\Repository\ContactRepository")
 */
class Contact
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255, name="first_name")
     * @Assert\Length(
     *      min = "2",
     *      max = "50",
     *      minMessage = "Your first name must be at least {{ limit }} characters length",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters length" 
     * )
     * 
     * @var string $firstName
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", length=255, name="last_name")
     * @Assert\Length(
     *      min = "2",
     *      max = "50",
     *      minMessage = "Your last name must be at least {{ limit }} characters length",
     *      maxMessage = "Your last name cannot be longer than {{ limit }} characters length" 
     * )
     *
     * @var string $lastName
     */
    protected $lastName;
    
    /**
     * @Assert\Email()
     * @Assert\NotBlank(message="Please enter your contact's email address")
     * @ORM\Column(type="string", length=255, name="email")
     * @var string $email
     */
    protected $email;
    
    /**
     * @Assert\Email()
     * @ORM\Column(type="string", length=255, name="phone", nullable=true)
     * @var string $email
     */
    protected $phone;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     * 
     * @var DateTime $createdAt
     */
    protected $createdAt;
        
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $tags
     * 
     * @ORM\ManyToMany(targetEntity="ContactTag")
     * @ORM\JoinTable(name="tc_contacts_tags")
     */
    private $tags;
    
    /**
     * @var ContactList $contactList
     * 
     * @Assert\Type(type="TC\CoreBundle\Entity\ContactList")
     * @ORM\ManyToOne(targetEntity="ContactList")
     */
    private $contactList;

    /**
     * @var string
     *
     * @ORM\Column(name="company_name", type="string", length=255, nullable=true)
     */
    private $companyName;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255, nullable=true)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="zip_code", type="string", length=255, nullable=true)
     */
    private $zipCode;

    /**
     * @var string
     *
     * 
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=255, nullable=true)
     */
    private $website;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set companyName
     *
     * @param string $companyName
     * @return Contact
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
    
        return $this;
    }

    /**
     * Get companyName
     *
     * @return string 
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Contact
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Contact
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
     * Set zipCode
     *
     * @param string $zipCode
     * @return Contact
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
    
        return $this;
    }

    /**
     * Get zipCode
     *
     * @return string 
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return Contact
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
     * Set workspace
     *
     * @param \TC\CoreBundle\Entity\Workspace $workspace
     * @return Contact
     */
    public function setWorkspace(\TC\CoreBundle\Entity\Workspace $workspace = null)
    {
        $this->workspace = $workspace;
    
        return $this;
    }

    /**
     * Get workspace
     *
     * @return \TC\CoreBundle\Entity\Workspace 
     */
    public function getWorkspace()
    {
        return $this->workspace;
    }

    /**
     * Add tags
     *
     * @param \TC\CoreBundle\Entity\ContactTag $tags
     * @return Contact
     */
    public function addTag(\TC\CoreBundle\Entity\ContactTag $tags)
    {
        $this->tags[] = $tags;
    
        return $this;
    }

    /**
     * Remove tags
     *
     * @param \TC\CoreBundle\Entity\ContactTag $tags
     */
    public function removeTag(\TC\CoreBundle\Entity\ContactTag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set contactList
     *
     * @param \TC\CoreBundle\Entity\ContactList $contactList
     * @return Contact
     */
    public function setContactList(\TC\CoreBundle\Entity\ContactList $contactList = null)
    {
        $this->contactList = $contactList;
    
        return $this;
    }

    /**
     * Get contactList
     *
     * @return \TC\CoreBundle\Entity\ContactList 
     */
    public function getContactList()
    {
        return $this->contactList;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return Contact
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    
        return $this;
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
     * @return Contact
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    
        return $this;
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
     * @param \DateTime $createdAt
     * @return Contact
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Contact
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

    /**
     * Set state
     *
     * @param string $state
     * @return Contact
     */
    public function setState($state)
    {
        $this->state = $state;
    
        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set website
     *
     * @param string $website
     * @return Contact
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    
        return $this;
    }

    /**
     * Get website
     *
     * @return string 
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Contact
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }
}