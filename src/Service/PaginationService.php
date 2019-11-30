<?php

namespace App\Service;

use Twig\Environment;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;

class PaginationService {
   
   private $entityClass;
   private $limit = 10;
   private $currentPage = 1;
   private $manager;
   private $route;
   private $templatePath;



   public function __construct(ObjectManager $manager, Environment $twig, RequestStack $request, $templatePath)
   {
      $this->route = $request->getCurrentRequest()->attributes->get('_route');
      $this->manager = $manager;
      $this->twig = $twig;
      $this->templatePath = $templatePath;
   }

   public function setTemplatePath($templatePath){
      $this->templatePath = $templatePath;

      return $this;
   }

   public function getTemplatePath(){
      return $this->templatePath;
   }


   public function setRoute($route)
   {
      $this->route = $route;

      return $this;
   }

   public function getRoute()
   {
      return $this->route;
   }
   
   public function render()
   {
      $this->twig->display($this->templatePath, [
         'page' => $this->currentPage,
         'pages' => $this->getPages(),
         'route' => $this->route
      ]);
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