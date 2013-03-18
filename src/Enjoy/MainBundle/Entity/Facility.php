<?php

namespace Enjoy\MainBundle\Entity;

class Facility
{
    protected $id;
    protected $crowlId;
    protected $companyName;
    protected $locationName;
    protected $city;
    protected $street;
    protected $lat;
    protected $lon;

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
     * Set companyName
     *
     * @param string $companyName
     * @return Facility
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
    
        return $this;
    }

    /**
     * Get companyName
     *
     * @return string 
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set locationName
     *
     * @param string $locationName
     * @return Facility
     */
    public function setLocationName($locationName)
    {
        $this->locationName = $locationName;
    
        return $this;
    }

    /**
     * Get locationName
     *
     * @return string 
     */
    public function getLocationName()
    {
        return $this->locationName;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Facility
     */
    public function setCity($city)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set street
     *
     * @param string $street
     * @return Facility
     */
    public function setStreet($street)
    {
        $this->street = $street;
    
        return $this;
    }

    /**
     * Get street
     *
     * @return string 
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set lat
     *
     * @param float $lat
     * @return Facility
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    
        return $this;
    }

    /**
     * Get lat
     *
     * @return float 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lon
     *
     * @param float $lon
     * @return Facility
     */
    public function setLon($lon)
    {
        $this->lon = $lon;
    
        return $this;
    }

    /**
     * Get lon
     *
     * @return float 
     */
    public function getLon()
    {
        return $this->lon;
    }

    /**
     * Set crowlId
     *
     * @param string $crowlId
     * @return Facility
     */
    public function setCrowlId($crowlId)
    {
        $this->crowlId = $crowlId;
    
        return $this;
    }

    /**
     * Get crowlId
     *
     * @return string 
     */
    public function getCrowlId()
    {
        return $this->crowlId;
    }
}