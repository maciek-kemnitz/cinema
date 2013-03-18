<?php

namespace Enjoy\MainBundle\Entity;

class MovieDate
{
    protected $id;
    protected $date;
    protected $movie;
    protected $facility;


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
     * Set movie
     *
     * @param \Enjoy\MainBundle\Entity\Movie $movie
     * @return MovieDate
     */
    public function setMovie(\Enjoy\MainBundle\Entity\Movie $movie = null)
    {
        $this->movie = $movie;
    
        return $this;
    }

    /**
     * Get movie
     *
     * @return \Enjoy\MainBundle\Entity\Movie 
     */
    public function getMovie()
    {
        return $this->movie;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return MovieDate
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
     * @return MovieDate
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