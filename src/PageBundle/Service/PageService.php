<?php

namespace PageBundle\Service;

use AppBundle\Service\AppService;
use PageBundle\Entity\Page;

class PageService
{
    /**
     *
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
     * Get Item based on its ID. If ID is null then create a new Item
     * 
     * @param int $pageId
     * @return type
     */
    public function getPage($pageId = null)
    {
        if (null === $pageId) {
            return $this->makePage();
        }
        
        // Get Page form repository
        $page = $this->appService->getEntityManager()
            ->getRepository('PageBundle:Page')
            ->getPage($pageId);

        // Check if page is found
        if (!$page instanceof Page) {
            throw $this->appService
                ->getAppException('alert.error.noItemFound');
        }

        return $page;
    }
    
    /**
     * 
     * @param type $justQuery
     * @return type
     */
    public function getPages($justQuery = true)
    {
        return $this->appService->getEntityManager()
            ->getRepository('PageBundle:Page')
            ->getPages($justQuery);
    }
    
    /**
     * 
     * @return Page
     */
    public function makePage()
    {
        return new Page();
    }
}