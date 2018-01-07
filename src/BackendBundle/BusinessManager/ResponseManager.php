<?php
namespace BackendBundle\BusinessManager;

use Doctrine\ORM\EntityManager;

class ResponseManager 
{
	private $em;
	public function __construct(EntityManager $entityManager) 
	{
		$this->em = $entityManager;

	}
}