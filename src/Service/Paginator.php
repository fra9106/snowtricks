<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

class Paginator 
{
    private $entityClass;
    private $limit = 5;
    private $currentPage = 1;
    private $manager;
    private $order;
    private $attribut;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function getData()
    {  
         if(empty($this->entityClass)) {
            throw new Exception("L'entité n'a pas été spécifié");      
        }
        $offset = $this->currentPage * $this->limit - $this->limit;
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->findBy($this->attribut,$this->order, $this->limit, $offset);
        return $data;   
    }

    public function getPages()
    {
        if(empty($this->entityClass)) {
            throw new Exception("L'entité n'a pas été spécifié");      
        }
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findBy($this->attribut));
        $pages = ceil($total / $this->limit);
        return $pages;  
    }

    /**
     * Get the value of entityClass
     */ 
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * Set the value of entityClass
     *
     * @return  self
     */ 
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    /**
     * Get the value of limit
     */ 
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Set the value of limit
     *
     * @return  self
     */ 
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Get the value of currentPage
     */ 
    public function getPage()
    {
        return $this->currentPage;
    }

    /**
     * Set the value of currentPage
     *
     * @return  self
     */ 
    public function setPage($currentPage)
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    /**
     * Get the value of manager
     */ 
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Set the value of manager
     *
     * @return  self
     */ 
    public function setManager($manager)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * Get the value of order
     */ 
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set the value of order
     *
     * @return  self
     */ 
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get the value of attribut
     */ 
    public function getAttribut()
    {
        return $this->attribut;
    }

    /**
     * Set the value of attribut
     *
     * @return  self
     */ 
    public function setAttribut($attribut)
    {
        $this->attribut = $attribut;

        return $this;
    }
}