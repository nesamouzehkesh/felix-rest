<?php

namespace ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Library\Base\BaseEntity;

/**
 * Order
 *
 * @ORM\Table(name="felix_product_order")
 * @ORM\Entity(repositoryClass="ProductBundle\Entity\Repository\OrderRepository")
 */
class Order extends BaseEntity
{
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=255)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="zip", type="string", length=255)
     */
    private $zip;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;

    /**
     * @var bool
     *
     * @ORM\Column(name="giftwrap", type="boolean", nullable=true)
     */
    private $giftwrap;
    
    /**
     * @ORM\OneToMany(targetEntity="OrderDetail", mappedBy="order")
     */
    private $orderDetail;
    
    /**
     * 
     */
    public function __construct()
    {
        parent::__construct();
        $this->orderDetail = new ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ProductOrder
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
     * Set street
     *
     * @param string $street
     *
     * @return ProductOrder
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
     * Set city
     *
     * @param string $city
     *
     * @return ProductOrder
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
     * Set state
     *
     * @param string $state
     *
     * @return ProductOrder
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set zip
     *
     * @param string $zip
     *
     * @return ProductOrder
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip
     *
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return ProductOrder
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set giftwrap
     *
     * @param boolean $giftwrap
     *
     * @return ProductOrder
     */
    public function setGiftwrap($giftwrap)
    {
        $this->giftwrap = $giftwrap;

        return $this;
    }

    /**
     * Get giftwrap
     *
     * @return bool
     */
    public function getGiftwrap()
    {
        return $this->giftwrap;
    }

    /**
     * Add orderDetail
     *
     * @param \ProductBundle\Entity\OrderDetail $orderDetail
     *
     * @return Order
     */
    public function addOrderDetail(\ProductBundle\Entity\OrderDetail $orderDetail)
    {
        $this->orderDetail[] = $orderDetail;

        return $this;
    }

    /**
     * Remove orderDetail
     *
     * @param \ProductBundle\Entity\OrderDetail $orderDetail
     */
    public function removeOrderDetail(\ProductBundle\Entity\OrderDetail $orderDetail)
    {
        $this->orderDetail->removeElement($orderDetail);
    }

    /**
     * Get orderDetail
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrderDetail()
    {
        return $this->orderDetail;
    }
}
