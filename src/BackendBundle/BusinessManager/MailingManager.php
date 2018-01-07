<?php
namespace BackendBundle\BusinessManager;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityManager;
class MailingManager 
{
	private $em;
	private $mailer;
	private $templating;
        private $context;
	public function __construct(EntityManager $entityManager, $mailer, $templating ,   SecurityContext $context) 
	{
		$this->mailer = $mailer;
		$this->em = $entityManager;
		$this->templating = $templating;
                $this->context = $context;

	}
        public function getUser()
        {
            return $this->context->getToken()->getUser();
        }
	public function sendEmail($subject,$user,$template)
        {
            try {
                    $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom('georgesamy0@gmail.com')
                ->setTo($user->getEmail())
                ->setBody($this->templating->render($template, ['user'=>$user]),'text/html');
                    $this->mailer->send($message);	
            } catch (Exception $e) {
                    return $e->getMessage();
            }

        }
        public function sendEmailIntro($subject,$email, $mailmessage, $user,$template)
        {
            try {
                $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom('georgesamy0@gmail.com')
                ->setTo($email)
                ->setBody($this->templating->render($template, ['user'=>$user , 'message'=>$mailmessage]),'text/html');
                    $this->mailer->send($message);	
            } catch (Exception $e) {
                    return $e->getMessage();
            }

        }
         public function sendJoinEmail($subject,$email, $mailmessage,$template)
        {
             
            try {
                $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom('georgesamy0@gmail.com')
                ->setTo($email)
                ->setBody($this->templating->render($template, [ 'message'=>$mailmessage]),'text/html');
                    $this->mailer->send($message);	
            } catch (Exception $e) {
                    return $e->getMessage();
            }

        }
	public function sendInactivityEmails()
	{
		try {
			
		} catch (Exception $e) {
			return "Could not send Daily Inactivity Email" . $e->getMessage();	
		}
		return "Daily Inactivity Email Sent Succesfully";
	}

	public function sendTopEntrepreneur()
	{
		try {
			
		} catch (Exception $e) {
			return "Could not send Monthly Top Entrepreneur Email" . $e->getMessage();		
		}
		return "Monthly Top Entrepreneur Email Sent Succesfully";
	}

	//get list of all subscribed users and send them email
	public function sendMondayEmail()
	{
		try {
			$users = $this->em->getRepository('BackendBundle:User')->findBy(['subscribed'=> true]);
			foreach ($users as $user) {
				$this->sendEmail("Monday Email",$user,'Emails/Scheduled/monday.html.twig');
			}
			$this->em->flush();	
		} catch (Exception $e) {
			return "Could not send Weekly Monday Email" . $e->getMessage();
		}
		return "Weekly Monday Email Sent Succesfully";
	}
        public function sendIntroductionEmail($email , $message, $intoducedUser)
	{
            
		try {
			$user=$this->context->getToken()->getUser();

                        $this->sendEmailIntro("Introduction Email From:".$user->getFirstname()." ".$user->getLastname(),$email,$message,$intoducedUser,'Emails/Introduction/introduction.html.twig');
			$this->em->flush();	
		} catch (Exception $e) {
			return "Could not send Introduction Email" . $e->getMessage();
		}
		return "Introduction Email Sent Succesfully";
	}
         public function sendJoinRequestEmail($email , $message)
	{
            
		try {
			//$user=$this->context->getToken()->getUser();

                        $this->sendJoinEmail("Request to join Email",$email,$message,'Emails/Join/join.html.twig');
			$this->em->flush();	
		} catch (Exception $e) {
			return "Could not send Join Email" . $e->getMessage();
		}
		return "Join Email Sent Succesfully";
	}
        
	public function sendWednesdayEmail()
	{
		try {
			
		} catch (Exception $e) {
			return "Could not send Weekly Wednesday Email" . $e->getMessage();	
		}
		return "Weekly Wednesday Email Sent Succesfully";
		
	}
}