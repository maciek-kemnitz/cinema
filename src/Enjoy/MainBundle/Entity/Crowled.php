<?php

namespace Enjoy\MainBundle\Entity;

class Crowled
{
    protected $id;
    protected $facility;
    protected $date;

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
     * Set date
     *
     * @param \DateTime $date
     * @return Crowled
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set facility
     *
     * @param \Enjoy\MainBundle\Entity\Facility $facility
     * @return Crowled
     */
    public function setFacility(\Enjoy\MainBundle\Entity\Facility $facility = null)
    {
        $this->facility = $facility;
    
        return $this;
    }

    /**
     * Get facility
     *
     * @return \Enjoy\MainBundle\Entity\Facility 
     */
    public function getFacility()
    {
        return $this->facility;
    }
}