<?php

namespace StoriesBundle\Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * Address
 *
 * @ORM\Table(name="user_address")
 * @ORM\Entity(repositoryClass="StoriesBundle\Repository\User\AddressRepository")
 */
class Address
{   
    /**
    * @ORM\ManyToOne(targetEntity="User", inversedBy="addresses")
    * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
    */
    private $user;
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=50)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="streetno", type="string", length=5)
     */
    private $streetno;

    /**
     * @var string
     *
     * @ORM\Column(name="localno", type="string", length=5)
     */
    private $localno;

    /**
     * @var string
     *
     * @ORM\Column(name="postcode", type="string", length=6)
     */
    private $postcode;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=25)
     */
    private $city;


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
     * Set street
     *
     * @param string $street
     * @return Address
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
     * Set streetno
     *
     * @param string $streetno
     * @return Address
     */
    public function setStreetno($streetno)
    {
        $this->streetno = $streetno;

        return $this;
    }

    /**
     * Get streetno
     *
     * @return string 
     */
    public function getStreetno()
    {
        return $this->streetno;
    }

    /**
     * Set localno
     *
     * @param string $localno
     * @return Address
     */
    public function setLocalno($localno)
    {
        $this->localno = $localno;

        return $this;
    }

    /**
     * Get localno
     *
     * @return string 
     */
    public function getLocalno()
    {
        return $this->localno;
    }

    /**
     * Set postcode
     *
     * @param string $postcode
     * @return Address
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * Get postcode
     *
     * @return string 
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Address
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
    
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string 
     */
    public function getUser()
    {
        return $this->user;
    }
}
