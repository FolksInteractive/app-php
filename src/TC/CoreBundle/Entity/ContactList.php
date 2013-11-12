<?php

namespace TC\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ContactList
 *
 * @ORM\Table(name="tc_contact_list")
 * @ORM\Entity
 */
class ContactList
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
     * @var \Doctrine\Common\Collections\ArrayCollection $contacts
     * 
     * @ORM\OneToMany(targetEntity="Contact", mappedBy="contactList", cascade={"persist", "remove"})
     */
    private $contacts;
    
    
    public function __construct()
    {
        $this->contacts = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add contacts
     *
     * @param \TC\CoreBundle\Entity\Contact $contacts
     * @return ContactList
     */
    public function addContact(\TC\CoreBundle\Entity\Contact $contacts)
    {
        $this->contacts[] = $contacts;
    
        return $this;
    }

    /**
     * Remove contacts
     *
     * @param \TC\CoreBundle\Entity\Contact $contacts
     */
    public function removeContact(\TC\CoreBundle\Entity\Contact $contacts)
    {
        $this->contacts->removeElement($contacts);
    }

    /**
     * Get contacts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getContacts()
    {
        return $this->contacts;
    }
}