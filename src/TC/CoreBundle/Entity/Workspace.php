<?php

namespace TC\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use TC\UserBundle\Entity\User;

/**
 * TC\CoreBundle\Entity\Workspace
 *
 * @ORM\Table(name="tc_workspace")
 * @ORM\Entity
 * @ORM\HasLifeCycleCallbacks
 */
class Workspace {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User $user
     * 
     * @ORM\OneToOne(targetEntity="\TC\UserBundle\Entity\User", mappedBy="workspace", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @var ContactList $contactList
     * 
     * @ORM\OneToOne(targetEntity="ContactList", cascade={"persist", "remove"})
     */
    private $contactList;

    /**
     * @var Pricebook $pricebook
     * 
     * @ORM\OneToOne(targetEntity="Pricebook", cascade={"persist", "remove"})
     */
    private $pricebook;

    /**
     * @var ArrayCollection $enrollments
     * 
     * @ORM\OneToMany(targetEntity="Enrollment", mappedBy="workspace")
     */
    private $enrollments;

    /**
     * @var ArrayCollection $projects
     * 
     * @ORM\OneToMany(targetEntity="Project", mappedBy="workspace")
     */
    private $projects;

    /**
     *
     * @var ArrayCollection $vendorEnrollments
     */
    private $vendorEnrollments;

    /**
     *
     * @var ArrayCollection $clientEnrollments
     */
    private $clientEnrollments;

    /**
     * @var ArrayCollection $vendorRelations
     */
    private $vendorRelations;

    /**
     * @var ArrayCollection $clientRelations
     */
    private $clientRelations;

    /**
     * @var ArrayCollection $collaboratorRelations
     */
    private $collaboratorRelations;

    public function __construct() {
        $this->contactList = new ContactList();
        $this->pricebook = new Pricebook();
        $this->enrollments = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->vendorEnrollments = new ArrayCollection();
        $this->clientEnrollments = new ArrayCollection();
        $this->vendorRelations = new ArrayCollection();
        $this->clientRelations = new ArrayCollection();
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
     * Get user
     *
     * @return User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Get relations
     *
     * @return Collection 
     */
    public function getRelations() {
        $relations = array();

        foreach ( $this->enrollments->toArray() as $key => $enrollment ) {
            if( $enrollment->getVendorRelation() ){
                $relations[] = $enrollment->getVendorRelation();
            }else{
                $relations[] = $enrollment->getClientRelation();
            }
        }

        return $relations;
    }

    /**
     * Get vendorRelations
     *
     * @return Collection 
     */
    public function getVendorRelations() {
        $relations = array();

        foreach ( $this->enrollments->toArray() as $key => $enrollment ) {
            if ( $enrollment->getEnrollAs() == Enrollment::ENROLLMENT_TYPE_VENDOR ) {
                $relations[] = $enrollment->getVendorRelation();
            }
        }

        return $relations;
    }

    /**
     * Get clientRelations
     *
     * @return Collection 
     */
    public function getClientRelations() {
        $relations = array();

        foreach ( $this->enrollments->toArray() as $key => $enrollment ) {
            if ( $enrollment->getEnrollAs() == Enrollment::ENROLLMENT_TYPE_CLIENT ) {
                $relations[] = $enrollment->getClientRelation();
            }
        }

        return $relations;
    }

    /**
     * Set contactList
     *
     * @param ContactList $contactList
     * @return Contact
     */
    public function setContactList( ContactList $contactList = null ) {
        $this->contactList = $contactList;

        return $this;
    }

    /**
     * Get contactList
     *
     * @return ContactList 
     */
    public function getContactList() {
        return $this->contactList;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return Workspace
     */
    public function setUser( User $user = null ) {
        $this->user = $user;

        return $this;
    }

    /**
     * Set pricebook
     *
     * @param Pricebook $pricebook
     * @return Workspace
     */
    public function setPricebook( Pricebook $pricebook = null ) {
        $this->pricebook = $pricebook;

        return $this;
    }

    /**
     * Get pricebook
     *
     * @return Pricebook 
     */
    public function getPricebook() {
        return $this->pricebook;
    }

    /**
     * get user's email
     * 
     * @return string
     */
    public function getEmail() {
        return $this->user->getEmail();
    }

    /**
     * get user's firstname
     * 
     * @return string
     */
    public function getFirstName() {
        return $this->user->getFirstName();
    }

    /**
     * get user's lastname
     * 
     * @return string
     */
    public function getLastName() {
        return $this->user->getLastName();
    }
    
    /**
     * get user's lastname
     * 
     * @return string
     */
    public function getFullName() {
        return $this->user->getFullName();
    }

    /**
     * Add enrollments
     *
     * @param Enrollment $enrollments
     * @return Workspace
     */
    public function addEnrollment( Enrollment $enrollment ) {
        $this->enrollments[] = $enrollment;

        return $this;
    }

    /**
     * Remove enrollments
     *
     * @param Enrollment $enrollments
     */
    public function removeEnrollment( Enrollment $enrollments ) {
        $this->enrollments->removeElement( $enrollments );
    }

    /**
     * Get enrollments
     *
     * @return Collection
     */
    public function getEnrollments() {
        return $this->enrollments;
    }

    /**
     * Add projects
     *
     * @param Project $projects
     * @return Workspace
     */
    public function addProject( Project $projects ) {
        $this->projects[] = $projects;

        return $this;
    }

    /**
     * Remove projects
     *
     * @param Project $projects
     */
    public function removeProject( Project $projects ) {
        $this->projects->removeElement( $projects );
    }

    /**
     * Get projects
     *
     * @return Collection 
     */
    public function getProjects() {
        return $this->projects;
    }

}