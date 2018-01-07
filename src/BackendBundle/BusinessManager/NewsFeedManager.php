<?php
namespace BackendBundle\BusinessManager;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityManager;

class NewsFeedManager 
{
	private $em;
        private $context;

	public function __construct(EntityManager $entityManager ,  SecurityContext $context) 
	{
		$this->em = $entityManager;
                $this->context = $context;

	}
        public function getUser()
        {
            return $this->context->getToken()->getUser();
        }
       public function getNewsFeed($filter ,  $limit ,  $offset )
       {
           $posts="No Posts Found.";
           
         
            
            if($filter[0]["globally"] != "0" && $filter[0]["industry"] != "0" )
            {
              
               $posts = $this->em->getRepository('BackendBundle:Post')->getNewsFeedbyIndustryGlobally($filter[0]["industry"] ,  $limit ,  $offset);
            }
            elseif($filter[0]["globally"] == "0" && $filter[0]["industry"] != "0" )
            {
               
               $posts = $this->em->getRepository('BackendBundle:Post')->getNewsFeedbyIndustryLocally($filter[0]["industry"] , $this->context->getToken()->getUser()->getCountry() ,  $limit ,  $offset);
            }
             elseif($filter[0]["globally"] != "0" && $filter[0]["type"] != "0" )
            {
               
               $posts = $this->em->getRepository('BackendBundle:Post')->getNewsFeedbyTypeGlobally($filter[0]["type"] ,  $limit ,  $offset);
            }
            elseif($filter[0]["globally"] == "0" && $filter[0]["type"] != "0" )
            {
                
               $posts = $this->em->getRepository('BackendBundle:Post')->getNewsFeedbyTypeLocally($filter[0]["type"] , $this->context->getToken()->getUser()->getCountry() ,  $limit ,  $offset);
            }
           elseif($filter[0]["country"] != "0")
            {
                
              $posts= $this->em->getRepository('BackendBundle:Post')->getNewsFeedbyCountry( $filter[0]["country"] ,  $limit ,  $offset);
            }

            elseif($filter[0]["globally"] != "0")
            {
               
               $posts = $this->em->getRepository('BackendBundle:Post')->getNewsFeedGlobally( $this->context->getToken()->getUser()->getCountry() ,  $limit ,  $offset);
            }
            else
           {
             $posts = $this->em->getRepository('BackendBundle:Post')->getNewsFeedbyCountry( $this->context->getToken()->getUser()->getCountry() ,  $limit ,  $offset);
           
           }
           return $posts;
           
       }
       
        
}