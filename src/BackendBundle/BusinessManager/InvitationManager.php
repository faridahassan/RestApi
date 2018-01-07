<?php
namespace BackendBundle\BusinessManager;

use Doctrine\ORM\EntityManager;


class InvitationManager 
{
	private $mailer;
	public function __construct($mailer) 
	{
		$this->mailer = $mailer;

	}
	public function sendEmail()
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('georgesamy0@gmail.com')
            ->setTo('georgesamy0@gmail.com')
            ->setBody('fucka youuuuu')
            //$this->renderView('HelloBundle:Hello:email.txt.twig', array('name' => $name))
        ;
        $this->mailer->send($message);
    }
}