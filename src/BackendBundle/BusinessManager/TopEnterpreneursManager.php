<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TopEnterpreneursManager
 *
 * @author Farida
 */
namespace BackendBundle\BusinessManager;

use Doctrine\ORM\EntityManager;

class TopEnterpreneursManager {
     private $em;
      

    public function __construct(EntityManager $entityManager ) 
    {
        $this->em = $entityManager;


    }
    public function getTopEnterpreneurs($filter , $limit , $offset )
    {
        $users= "No Users available";
        
            if($filter[0]["industry"] != "0"  )
            {
                $users = $this->em->getRepository('BackendBundle:User')->getTopEnterpreneursIndustry( $limit ,  $offset ,$filter[0]["industry"] );
            }
            else if($filter[0]["country"] != "0"  )
            {
                $users = $this->em->getRepository('BackendBundle:User')->getTopEnterpreneursCountry(  $limit ,  $offset , $filter[0]["country"] );
            }
            else if($filter[0]["time"] != "0" )
            {
               
                $users = $this->em->getRepository('BackendBundle:User')->getTopEnterpreneursAllTime( $limit ,  $offset);
                
            
            }
            else 
            {
                $users = $this->em->getRepository('BackendBundle:User')->getTopEnterpreneursTimeRange( $limit ,  $offset);
               
            }
           
          
        return $users;
        
    }
}
