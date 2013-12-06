<?php

namespace TC\CoreBundle\Entity;

use DateTime as DateTime2;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\DateTime;
use TC\CoreBundle\Validator\Constraints as TCAssert;

/**
 * ProjectObjective
 *
 * @ORM\Table(name="tc_project_objective")
 * @ORM\Entity
 */
class ProjectObjective
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
    private $created_at;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var Project $project
     * 
     * @Assert\Type(type="Project")
     * @ORM\ManyToOne(targetEntity="Project", cascade={"persist"})
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    private $project;

    /**
     * @var ArrayCollection $orders
     * 
     * @ORM\OneToMany(targetEntity="Order", mappedBy="relation", cascade={"persist", "remove"})
     * @ORM\OrderBy({"created_at" = "DESC"})
     */
    private $orders;
    
    public function __construct() {
        $this->orders = new ArrayCollection();
        $this->created_at = new DateTime();
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
     * @return ProjectObjective
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
     * Set created_at
     *
     * @param DateTime2 $created_at
     * @return ProjectObjective
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    
        return $this;
    }

    /**
     * Get created_at
     *
     * @return DateTime2 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return ProjectObjective
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
     * Set project
     *
     * @param \TC\CoreBundle\Entity\Project $project
     * @return ProjectObjective
     */
    public function setProject(\TC\CoreBundle\Entity\Project $project = null)
    {
        $this->project = $project;
    
        return $this;
    }

    /**
     * Get project
     *
     * @return \TC\CoreBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Add orders
     *
     * @param \TC\CoreBundle\Entity\Order $orders
     * @return ProjectObjective
     */
    public function addOrder(\TC\CoreBundle\Entity\Order $orders)
    {
        $this->orders[] = $orders;
    
        return $this;
    }

    /**
     * Remove orders
     *
     * @param \TC\CoreBundle\Entity\Order $orders
     */
    public function removeOrder(\TC\CoreBundle\Entity\Order $orders)
    {
        $this->orders->removeElement($orders);
    }

    /**
     * Get orders
     *
     * @return Collection 
     */
    public function getOrders()
    {
        return $this->orders;
    }
}