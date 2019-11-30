<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;

class PaginationService {
   
   private $entityClass;
   private $limit = 10;
   private $currentPage = 1;

   private $manager;

   public function __construct(ObjectManager $manager)
   {
      $this->manager = $manager;
   }

   public function getPages(){
      // 1) Connaitre le total des enregistrements de la table
      $repo = $this->manager->getRepository($this->entityClass);
      $total = count($repo->findAll());

      // 2) Faire la div et l'arrondi et return
      $pages = ceil($total/ $this->limit);

      return $pages;
   }


   public function getData(){
      // 1) calculer l'offset
      $offset = $this->currentPage * $this->limit - $this->limit;

      // 2) Demander au repo de trouver les elts
      $repo = $this->manager->getRepository($this->entityClass);
      $data = $repo->findBy([], [], $this->limit, $offset);
      // 3) REnvoyer les elts en question
      return $data;
   }

   public function setPage($page)
   {
      $this->currentPage = $page;

      return $this;
   }

   public function getPage()
   {
      return $this->currentPage;
   }

   public function setLimit($limit){
      $this->limit = $limit;

      return $this;
   }

   public function getLimit($limit){

      return $this->limit;
   }

   public function setEntityClass($entityClass){

      $this->entityClass = $entityClass;

      return $this;
   }

   public function getEntityClass(){

      return $this->entityClass;
   }

}