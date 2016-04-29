<?php

namespace AppBundle\Service;

use AppBundle\Library\Component\LoremIpsumGenerator;
use AppBundle\Service\AppService;
use UserBundle\Entity\Address;
use UserBundle\Entity\User;
use UserBundle\Entity\Role;
use PageBundle\Entity\Page;
use ProductBundle\Entity\Product;
use ProductBundle\Entity\Category as ProductCategory;

class DataFixture
{
    private $data = array(
        'pages' => 20,
        'roles' => array(
            array('name' => 'User', 'role' => Role::ROLE_USER),
            array('name' => 'Admin', 'role' => Role::ROLE_ADMIN),
        ),
        'productCategories' => 20,
        'products' => 100,        
        'users' => 50,
        'usersData' => array(
            array(
                'username' => 'admin@admin.com',
                'password' => 'admin',
                'role' => Role::ROLE_ADMIN,
                'firstName' => 'firstName',
                'lastName' => 'lastName',
                'addresses' => array(
                    array(
                        'type' => Address::ADDRESS_TYPE_BILLING,
                        'locationType' => Address::LOCATION_TYPE_RESIDENTIAL,
                        'city' => 'Sydney',
                        'country' => 'AU',
                        'fullName' => 'Admin User',
                        'state' => 'NSW',
                        'firstAddressLine' => '43/6 Jon Street, Wellington',
                        'phoneNumber' => '23489234',
                        'postCode' => '2345',
                    ),
                    array(
                        'type' => Address::ADDRESS_TYPE_SHIPPING,
                        'locationType' => Address::LOCATION_TYPE_RESIDENTIAL,
                        'city' => 'Sydney',
                        'country' => 'AU',
                        'fullName' => 'Admin User',
                        'state' => 'NSW',
                        'firstAddressLine' => '43/2 Timberland Street, Wellington',
                        'phoneNumber' => '3454359',
                        'postCode' => '2345',
                    ),
                )
            ),
            array(
                'username' => 'user@user.com',
                'password' => 'user',
                'role' => Role::ROLE_USER,
                'firstName' => 'firstName',
                'lastName' => 'lastName',
                'addresses' => array(
                    array(
                        'type' => Address::ADDRESS_TYPE_BILLING_SHIPPING,
                        'locationType' => Address::LOCATION_TYPE_RESIDENTIAL,
                        'city' => 'Sydney',
                        'country' => 'AU',
                        'fullName' => 'User',
                        'state' => 'NSW',
                        'firstAddressLine' => '12/1 A32 Street, Rhod',
                        'phoneNumber' => '09378265',
                        'postCode' => '1675',
                    ),
                )
            ),
        ),
    );
    
    /** @var Role[] */
    protected $roles;
    
    /**
     * @var AppService $appService
     */
    protected $appService;
    
    /**
     * 
     * @param AppService $appService
     */
    public function __construct(AppService $appService) 
    {
        $this->appService = $appService;
    }
    
    /**
     * Load user roles
     * 
     * @return \AppBundle\Service\DataFixture
     */
    public function loadUserRoles()
    {
        foreach ($this->data['roles'] as $roleData) {
            $role = new Role();
            $role->setName($roleData['name']);
            $role->setRole($roleData['role']);
            $this->roles[$roleData['role']] = $role;
            $this->appService->getEntityManager()->persist($role);
        }
        $this->appService->getEntityManager()->flush();
        
        return $this;
    }    
    
    /**
     * Load user data
     * 
     * @return \AppBundle\Service\DataFixture
     */
    public function loadUsers()
    {
        $loremIpsum = new LoremIpsumGenerator();
        $usersData = $this->data['usersData'];
        for ($i = 0; $i < $this->data['users']; $i++) {
            $email = $loremIpsum->getEmail();
            $usersData[] = array(
                'username' => $email,
                'password' => 'abcd',
                'role' => Role::ROLE_USER,
                'firstName' => $loremIpsum->getName(),
                'lastName' => $loremIpsum->getName(),
            );
        }
        
        foreach ($usersData as $userData) {
            $user = new User();
            $user->setUsername($userData['username']);
            $user->setPassword($userData['password']);
            $user->addRole($this->roles[$userData['role']]);
            
            if (isset($userData['firstName'])) {
                $user->setFirstName($userData['firstName']);
            }
            if (isset($userData['lastName'])) {
                $user->setLastName($userData['lastName']);
            }
            if (isset($userData['addresses'])) {
                foreach ($userData['addresses'] as $addressData) {
                    $address = new Address();
                    $address->setAddressType($addressData['type']);
                    $address->setCity($addressData['city']);
                    $address->setCountry($addressData['country']);
                    $address->setFullName($addressData['fullName']);
                    $address->setState($addressData['state']);
                    $address->setLocationType($addressData['locationType']);
                    $address->setFirstAddressLine($addressData['firstAddressLine']);
                    $address->setPhoneNumber($addressData['phoneNumber']);
                    $address->setPostCode($addressData['postCode']);
                    $address->setType(Address::TYPE_PRIMARY);
                    $address->setUser($user);
                    $user->addAddress($address);
                    $this->appService->getEntityManager()->persist($address);
                }
            }
            $this->appService->getEntityManager()->persist($user);
        }
        $this->appService->getEntityManager()->flush();
        
        return $this;
    }
    
    /**
     * Load pages
     * 
     * @return \AppBundle\Service\DataFixture
     */
    public function loadPages()
    {
        $loremIpsum = new LoremIpsumGenerator();
        $page = new Page();
        $page->setTitle('Home Page');
        $page->setContent($loremIpsum->getDescription());
        $page->setUrl('');
        $this->appService->getEntityManager()->persist($page);

        for ($i = 0; $i < $this->data['pages']; $i++) {
            $page = new Page();
            $page->setTitle($loremIpsum->getTitle());
            $page->setContent($loremIpsum->getDescription());
            $page->setUrl($loremIpsum->getUrl(true));
            /*
            for ($j = 0; $j < rand(1 , count($this->labels)); $j++) {
                $page->addLabel($this->labels[$j]);
            }
            */
            $this->appService->getEntityManager()->persist($page);
        }
        $this->appService->getEntityManager()->flush();
        
        return $this;
    }
    
    /**
     * Load products
     * 
     * @return \AppBundle\Service\DataFixture
     */
    public function loadProducts()
    {
        $productCategories = array();
        $loremIpsum = new LoremIpsumGenerator();
        for ($i = 0; $i < $this->data['productCategories']; $i++) {
            $productCategory = new ProductCategory();
            $productCategory->setTitle($loremIpsum->getTitle());
            $productCategory->setDescription($loremIpsum->getDescription());
            $productCategories[$i] = $productCategory;
            $this->appService->getEntityManager()->persist($productCategory);
        }
        
        for ($i = 0; $i < $this->data['products']; $i++) {
            $product = new Product();
            $product->setTitle($loremIpsum->getTitle());
            $product->setDescription($loremIpsum->getDescription());
            $product->setPrice(1);
            $product->setOriginalPrice(1.99);
            shuffle($productCategories);
            for ($j = 0; $j < rand(1 , 3); $j++) {
                $productCategory = $productCategories[$j];
                $product->addCategory($productCategory);
            }
            $this->appService->getEntityManager()->persist($product);
        }
        $this->appService->getEntityManager()->flush();
        
        return $this;
    }    
    
    /**
     * Truncate all doctrine entities
     * 
     * @return \AppBundle\Service\DataFixture
     */
    public function truncateEntities()
    {
        $em = $this->appService->getEntityManager();
        $schemaManager = $em->getConnection()->getSchemaManager();

        $query = array('SET FOREIGN_KEY_CHECKS=0;');
        foreach($schemaManager->listTables() as $table) {
            array_push($query, sprintf('TRUNCATE %s;', $table->getName()));
        }
        array_push($query, 'SET FOREIGN_KEY_CHECKS=1;');
        $em->getConnection()->executeQuery(implode('', $query), array(), array());
        
        return $this;
    }
}