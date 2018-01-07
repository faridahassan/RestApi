<?php
namespace BackendBundle\BusinessManager;

use Doctrine\ORM\EntityManager;

class UserManager 
{
	private $em;
	public function __construct(EntityManager $entityManager) 
	{
		$this->em = $entityManager;

	}

	public function getPeopleIHelped($user)
	{
		try {
			$users = $this->em->getRepository('BackendBundle:User')->getIHelped($user);
			return $users;
		} catch (Exception $e) {
			return "Couldn't retreive list of people I helped";
		}
	}

	public function getPeopleHelpedMe($user)
	{
		try {
			$users = $this->em->getRepository('BackendBundle:User')->getHelpedMe($user);
			return $users;
		} catch (Exception $e) {
			return "Couldn't retreive list of people I helped";
		}
	}
	public function getMyTeamSize($user)
	{
		try {
			$teamSize  = $this->em->getRepository('BackendBundle:User')->getTeamSize($user);
			return $teamSize;
			return count($uniques);
		} catch (Exception $e) {
			return "0";
		}
	}
}