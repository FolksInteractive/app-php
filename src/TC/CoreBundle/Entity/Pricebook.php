<?php

namespace TC\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Pricebook
 *
 * @ORM\Table(name="tc_pricebook")
 * @ORM\Entity
 */
class Pricebook
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
     * @var \Doctrine\Common\Collections\ArrayCollection $item
     * 
     * @ORM\OneToMany(targetEntity="PricebookItem", mappedBy="priceBook", cascade={"persist", "remove"})
     */
    private $items;

    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add items
     *
     * @param \TC\CoreBundle\Entity\PricebookItem $items
     * @return Pricebook
     */
    public function addItem(\TC\CoreBundle\Entity\PricebookItem $items)
    {
        $this->items[] = $items;
    
        return $this;
    }

    /**
     * Remove items
     *
     * @param \TC\CoreBundle\Entity\PricebookItem $items
     */
    public function removeItem(\TC\CoreBundle\Entity\PricebookItem $items)
    {
        $this->items->removeElement($items);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItems()
    {
        return $this->items;
    }
}