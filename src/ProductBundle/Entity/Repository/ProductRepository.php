<?php

namespace ProductBundle\Entity\Repository;

use AppBundle\Library\Base\BaseEntityRepository;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends BaseEntityRepository
{   
    /**
     * 
     * @param int $id
     * @return type
     */
    public function getProduct($id)
    {
        $qb = $this->getQueryBuilder()
            ->select(''
                . 'product.id, '
                . 'product.title, '
                . 'product.image,'
                . 'product.images,'
                . 'product.description,'
                . 'product.price'
                )
            ->from('ProductBundle:Product', 'product')
            ->where('product.id = :id AND product.deleted = 0')
            ->setParameter('id', $id);

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * 
     * @return type
     */
    public function getProducts($order = 'product.id')
    {
        $qb = $this->getQueryBuilder()
            ->select(''
                . 'product.id, '
                . 'product.title, '
                . 'product.image,'
                . 'product.description,'
                . 'product.price'
                )
            ->from('ProductBundle:Product', 'product')
            ->where('product.deleted = 0')
            ->orderBy($order);
        
        return $qb->getQuery()->getScalarResult();
    }
}