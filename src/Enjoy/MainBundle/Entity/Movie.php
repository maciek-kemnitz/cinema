<?php

namespace Enjoy\MainBundle\Entity;

class Movie
{
    protected $id;
    protected $name;
    protected $dates;
    protected $img_url;
    protected $description;
    protected $originalName;
    protected $length;
    protected $direction;
    protected $cast;


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

    /**
     * Set img_url
     *
     * @param string $imgUrl
     * @return Movie
     */
    public function setImgUrl($imgUrl)
    {
        $this->img_url = $imgUrl;
    
        return $this;
    }

    /**
     * Get img_url
     *
     * @return string 
     */
    public function getImgUrl()
    {
        return $this->img_url;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Movie
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
     * Set originalName
     *
     * @param string $originalName
     * @return Movie
     */
    public function setOriginalName($originalName)
    {
        $this->originalName = $originalName;
    
        return $this;
    }

    /**
     * Get originalName
     *
     * @return string 
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }

    /**
     * Set length
     *
     * @param string $length
     * @return Movie
     */
    public function setLength($length)
    {
        $this->length = $length;
    
        return $this;
    }

    /**
     * Get length
     *
     * @return string 
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set direction
     *
     * @param string $direction
     * @return Movie
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;
    
        return $this;
    }

    /**
     * Get direction
     *
     * @return string 
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * Set cast
     *
     * @param string $cast
     * @return Movie
     */
    public function setCast($cast)
    {
        $this->cast = $cast;
    
        return $this;
    }

    /**
     * Get cast
     *
     * @return string 
     */
    public function getCast()
    {
        return $this->cast;
    }
}