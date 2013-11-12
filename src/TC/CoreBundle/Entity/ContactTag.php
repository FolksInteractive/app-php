<?php

namespace TC\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ContactTag
 *
 * @ORM\Table(name="tc_contact_tag")
 * @ORM\Entity
 */
class ContactTag
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /**
     * @var ContactList $contactList
     * 
     * @Assert\Type(type="ContactList")
     * @ORM\ManyToOne(targetEntity="ContactList")
     */
    private $contactList;


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
     * Set name
     *
     * @param string $name
     * @return ContactTag
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set contactList
     *
     * @param \TC\CoreBundle\Entity\ContactList $contactList
     * @return ContactTag
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
}