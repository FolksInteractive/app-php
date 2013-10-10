<?php

namespace TC\CoreBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use TC\CoreBundle\Validator\Constraints as TCAssert;

/**
 * TC\CoreBundle\Entity\Enrollment
 * @TCAssert\Enrollment()
 * @ORM\Table(name="tc_enrollment")
 * @ORM\Entity()
 */
class Enrollment {
    const ENROLLMENT_TYPE_VENDOR = 'vendor';
    const ENROLLMENT_TYPE_CLIENT = 'client';
    const ENROLLMENT_TYPE_COLLABORATOR = 'collaborator';
    
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $email
     * 
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     * @Assert\NotNull()
     * 
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;
    
    /**
     *
     * @var string $enrollAs
     * 
     * @Assert\Choice(callback = "getEnrollmentTypes") 
     * @Assert\NotNull()
     * 
     * @ORM\Column(name="enroll_as", type="string")
     */
    private $enrollAs;
        
    /**
     * @var Workspace $sender; 
     * 
     * @ORM\ManyToOne(targetEntity="Workspace")
     */
    private $sender;
            
    /**
     * @var Workspace $workspace; 
     * 
     * @ORM\ManyToOne(targetEntity="Workspace", inversedBy="enrollments")
     */
    private $workspace;
    
    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;
            
    /**
     * @var Relation $relation; 
     * 
     * @ORM\OneToOne(targetEntity="Relation", mappedBy="clientEnrollment")
     */
    private $clientRelation;
    
            
    /**
     * @var Relation $relation; 
     * 
     * @ORM\OneToOne(targetEntity="Relation", mappedBy="vendorEnrollment")
     */
    private $vendorRelation;

    public function __construct() {
        $this->createdAt = new DateTime();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime 
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Set sender
     *
     * @param Workspace $sender
     */
    public function setSender(Workspace $sender)
    {
        $this->sender = $sender;
    }

    /**
     * Get sender
     *
     * @return Workspace
     */
    public function getSender()
    {
        return $this->sender;
    }
    
    /**
     * Get Enrollment Types
     */
    public static function getEnrollmentTypes(){
        return array( Enrollment::ENROLLMENT_TYPE_VENDOR, Enrollment::ENROLLMENT_TYPE_CLIENT, Enrollment::ENROLLMENT_TYPE_COLLABORATOR);
    }

    /**
     * Set enrollAs
     *
     * @param string $enrollAs
     * @return Enrollment
     */
    public function setEnrollAs($enrollAs)
    {
        $this->enrollAs = $enrollAs;
    
        return $this;
    }

    /**
     * Get enrollAs
     *
     * @return string 
     */
    public function getEnrollAs()
    {
        return $this->enrollAs;
    }

    /**
     * Set workspace
     *
     * @param \TC\CoreBundle\Entity\Workspace $workspace
     * @return Enrollment
     */
    public function setWorkspace(\TC\CoreBundle\Entity\Workspace $workspace)
    {
        $this->workspace = $workspace;
        $this->email = $workspace->getEmail();
                
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
     * Set clientRelation
     *
     * @param Relation $clientRelation
     * @return Enrollment
     */
    public function setClientRelation(Relation $clientRelation = null)
    {
        $this->clientRelation = $clientRelation;
    
        return $this;
    }

    /**
     * Get clientRelation
     *
     * @return Relation 
     */
    public function getClientRelation()
    {
        return $this->clientRelation;
    }

    /**
     * Set vendorRelation
     *
     * @param Relation $vendorRelation
     * @return Enrollment
     */
    public function setVendorRelation(Relation $vendorRelation = null)
    {
        $this->vendorRelation = $vendorRelation;
    
        return $this;
    }

    /**
     * Get vendorRelation
     *
     * @return Relation 
     */
    public function getVendorRelation()
    {
        return $this->vendorRelation;
    }
}