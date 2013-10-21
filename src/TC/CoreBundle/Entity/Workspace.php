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
     * @ORM\OneToOne(targetEntity="\TC\UserBundle\Entity\User", inversedBy="workspace", cascade={"persist", "remove"})
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
     * @var ArrayCollection $projects
     * 
     * @ORM\OneToMany(targetEntity="Project", mappedBy="workspace")
     */
    private $projects;

    /**
     * @var ArrayCollection $vendorRelations
     * @ORM\OneToMany(targetEntity="Relation", mappedBy="vendor", cascade={"persist", "remove"})
     */
    private $vendorRelations;

    /**
     * @var ArrayCollection $clientRelations
     * @ORM\OneToMany(targetEntity="Relation", mappedBy="client", cascade={"persist", "remove"})
     */
    private $clientRelations;

    public function __construct() {
        $this->contactList = new ContactList();
        $this->pricebook = new Pricebook();
        $this->projects = new ArrayCollection();
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
        $user->setWorkspace($this);
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


    /**
     * Add vendorRelations
     *
     * @param \TC\CoreBundle\Entity\Workspace $vendorRelations
     * @return Workspace
     */
    public function addVendorRelation(\TC\CoreBundle\Entity\Workspace $vendorRelations)
    {
        $this->vendorRelations[] = $vendorRelations;
    
        return $this;
    }

    /**
     * Remove vendorRelations
     *
     * @param \TC\CoreBundle\Entity\Workspace $vendorRelations
     */
    public function removeVendorRelation(\TC\CoreBundle\Entity\Workspace $vendorRelations)
    {
        $this->vendorRelations->removeElement($vendorRelations);
    }

    /**
     * Get vendorRelations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVendorRelations()
    {
        return $this->vendorRelations;
    }

    /**
     * Add clientRelations
     *
     * @param \TC\CoreBundle\Entity\Workspace $clientRelations
     * @return Workspace
     */
    public function addClientRelation(\TC\CoreBundle\Entity\Workspace $clientRelations)
    {
        $this->clientRelations[] = $clientRelations;
    
        return $this;
    }

    /**
     * Remove clientRelations
     *
     * @param \TC\CoreBundle\Entity\Workspace $clientRelations
     */
    public function removeClientRelation(\TC\CoreBundle\Entity\Workspace $clientRelations)
    {
        $this->clientRelations->removeElement($clientRelations);
    }

    /**
     * Get clientRelations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getClientRelations()
    {
        return $this->clientRelations;
    }
    
    
    public function jsonSerialize() {
        $data = array(
            "id" => $this->getId(),
            "fullname" =>$this->getFullName(),
            "avatar" =>$this->getUser()->getAvatarName()
        );
        
        return $data;
    }
}