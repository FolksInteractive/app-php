<?php

namespace TC\CoreBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use TC\CoreBundle\Validator\Constraints as TCAssert;

/**
 * Project
 *
 * @ORM\Table(name="tc_project")
 * @ORM\Entity
 */
class Project
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
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var DateTime2
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var ArrayCollection $orders
     * 
     * @ORM\OneToMany(targetEntity="ProjectObjective", mappedBy="relation", cascade={"persist", "remove"})
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $objectives;
    
    /**
     * @var ArrayCollection $orders
     * 
     * @ORM\ManyToOne(targetEntity="Workspace", inversedBy="projects", cascade={"persist"})
     */
    private $workspace;

    /**
     * @var boolean $active
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active = true;

    public function __construct() {
        $this->objectives = new ArrayCollection();
        $this->createdAt = new DateTime();
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
     * Set name
     *
     * @param string $name
     * @return Project
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
     * Set createdAt
     *
     * @param DateTime2 $createdAt
     * @return Project
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return DateTime2 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Project
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add objectives
     *
     * @param \TC\CoreBundle\Entity\ProjectObjective $objectives
     * @return Project
     */
    public function addObjective(\TC\CoreBundle\Entity\ProjectObjective $objectives)
    {
        $this->objectives[] = $objectives;
    
        return $this;
    }

    /**
     * Remove objectives
     *
     * @param \TC\CoreBundle\Entity\ProjectObjective $objectives
     */
    public function removeObjective(\TC\CoreBundle\Entity\ProjectObjective $objectives)
    {
        $this->objectives->removeElement($objectives);
    }

    /**
     * Get objectives
     *
     * @return Collection 
     */
    public function getObjectives()
    {
        return $this->objectives;
    }

    /**
     * Set workspace
     *
     * @param \TC\CoreBundle\Entity\Workspace $workspace
     * @return Project
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
     * Set active
     *
     * @param boolean $active
     * @return Project
     */
    public function setActive($active)
    {
        $this->active = $active;
    
        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }
}