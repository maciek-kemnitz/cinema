<?php

namespace Enjoy\MainBundle\Entity;

class Movie
{
    protected $id;
    protected $name;
    protected $dates;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dates = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Movie
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
     * Add dates
     *
     * @param \Enjoy\MainBundle\Entity\MovieDate $dates
     * @return Movie
     */
    public function addDate(\Enjoy\MainBundle\Entity\MovieDate $dates)
    {
        $this->dates[] = $dates;
    
        return $this;
    }

    /**
     * Remove dates
     *
     * @param \Enjoy\MainBundle\Entity\MovieDate $dates
     */
    public function removeDate(\Enjoy\MainBundle\Entity\MovieDate $dates)
    {
        $this->dates->removeElement($dates);
    }

    /**
     * Get dates
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDates()
    {
        return $this->dates;
    }
}