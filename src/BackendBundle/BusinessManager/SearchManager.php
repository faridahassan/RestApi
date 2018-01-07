<?php
namespace BackendBundle\BusinessManager;

use Doctrine\ORM\EntityManager;

class SearchManager 
{
	private $em;
	public function __construct(EntityManager $entityManager) 
	{
		$this->em = $entityManager;

	}
}