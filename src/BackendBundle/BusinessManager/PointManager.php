<?php
namespace BackendBundle\BusinessManager;

use Doctrine\ORM\EntityManager;

use BackendBundle\Entity\User;
use BackendBundle\Entity\UserPoints;
use BackendBundle\Entity\Reply;

class PointManager 
{
	private $em;
	public function __construct(EntityManager $entityManager) 
	{
		$this->em = $entityManager;

	}
	public function addInvitationPoints($invitationCode)
	{	
		try {
			$invitationPoints = 2; 
			$user = $this->em->getRepository('BackendBundle:User')->findOneByInvitationCode($invitationCode);
			$this->addPointsToUser($user,$invitationPoints);
		} catch (Exception $e) {
			return "could not addInvitationPoints to user" . $e->getMessage();
		}
		return "Success: Added points succesfully";
	}

	public function addReplyPoints(Reply $reply)
	{	
		try {
			$supplierRecommendationPoints = 1;
			$personalIntroductionPoints = 2;
			$advicePoints = 1;
			$user = $reply->getUser();
			switch ($reply->getPost()->getType()) {
				case 'sr':
				$this->addPointsToUser($user,$supplierRecommendationPoints);
				break;
				case 'pi':
				$this->addPointsToUser($user,$personalIntroductionPoints);
				break;
				case 'a':
				$this->addPointsToUser($user,$advicePoints);
				break;
				default:
				
				break;
			}
		} catch (Exception $e) {
			
			return "could not addReplyPoints  to user" . $e->getMessage();
		}
		
		return "Success: Added points succesfully";
		
	}

	public function addSharePoints(User $user)
	{
		try {
			$shareOnMediaPoints = 1;
			$this->addPointsToUser($user,$shareOnMediaPoints);
			
		} catch (Exception $e) {
			return "could not addKnowSomeonePoints  to user" . $e->getMessage();	
		}
		return "Success: Added points succesfully";
	}

	public function addKnowSomeonePoints(User $user)
	{
		try {
			$knowSomeone = 1;
			$this->addPointsToUser($user,$knowSomeone);
		} catch (Exception $e) {
			return "could not  addKnowSomeonePoints  to user" . $e->getMessage();
		}
		return "Success: Added points succesfully";
	}

	private function addPointsToUser(User $user , $points)
	{
		try {
			$pointrecord = new UserPoints($user);
			$pointrecord->setPoints($points);
			(is_null($user->getPoints())) ? $user->setPoints($points) : $user->setPoints($user->getPoints()+$points);
			$this->em->persist($user);
			$this->em->persist($pointrecord);
			$this->em->flush($user);
		} catch (Exception $e) {
			return "could not add points to user" . $e->getMessage();	
		}
		return "Success: Added points succesfully";
	}

}