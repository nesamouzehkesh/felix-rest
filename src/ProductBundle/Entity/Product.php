<?php

namespace ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Library\Base\BaseEntity;

/**
 * User
 *
 * @ORM\Table(name="felix_product")
 * @ORM\Entity(repositoryClass="ProductBundle\Entity\Repository\ProductRepository")
 */
class Product extends BaseEntity
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;
    
    /**
     * @var array
     * @ORM\Column(name="image", type="array", nullable=true)
     */
    private $image;
    
    /**
     * @var array
     * @ORM\Column(name="images", type="array", nullable=true)
     */
    private $images;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="price", type="float", nullable=true)
     */
    private $price;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="original_price", type="float", nullable=true)
     */
    private $originalPrice;
    
    /**
     * @ORM\Column(name="available", type="boolean", options={"default"= 1})
     */
    protected $available;
    
    /**
     * @ORM\ManyToMany(targetEntity="Category")
     * @ORM\JoinTable(name="felix_product_products_categories",
     * joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")})
     */
    protected $categories; 
    
    /**
     * 
     */
    public function __construct()
    {
        parent::__construct();
        $this->categories = new ArrayCollection();
        $this->setAvailable(true);
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
     * Set title
     *
     * @param string $title
     * @return Product
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Product
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
     * Set image
     *
     * @param string $image
     * @return Product
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set images
     *
     * @param string $images
     * @return Product
     */
    public function setImages($images)
    {
        $this->images = $images;

        return $this;
    }

    /**
     * Get images
     *
     * @return string 
     */
    public function getImages()
    {
        return $this->images;
    }
    
    /**
     * Set price
     *
     * @param integer $price
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer 
     */
    public function getPrice()
    {
        return $this->price;
    }
    
    /**
     * Set originalPrice
     *
     * @param integer $originalPrice
     * @return Product
     */
    public function setOriginalPrice($originalPrice)
    {
        $this->originalPrice = $originalPrice;

        return $this;
    }

    /**
     * Get originalPrice
     *
     * @return integer 
     */
    public function getOriginalPrice()
    {
        return $this->originalPrice;
    }

    /**
     * Set available
     *
     * @param boolean $available
     * @return Product
     */
    public function setAvailable($available)
    {
        $this->available = $available;

        return $this;
    }

    /**
     * Get available
     *
     * @return boolean 
     */
    public function getAvailable()
    {
        return $this->available;
    }

    /**
     * Add categories
     *
     * @param \ProductBundle\Entity\Category $categories
     * @return Product
     */
    public function addCategory(\ProductBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \ProductBundle\Entity\Category $categories
     */
    public function removeCategory(\ProductBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set createdTime
     *
     * @param integer $createdTime
     *
     * @return Product
     */
    public function setCreatedTime($createdTime)
    {
        $this->createdTime = $createdTime;

        return $this;
    }

    /**
     * Get createdTime
     *
     * @return integer
     */
    public function getCreatedTime()
    {
        return $this->createdTime;
    }
}
