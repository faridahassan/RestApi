<?php
namespace NotificationsBundle\BusinessManager;

use BackendBundle\Entity\User;
use BackendBundle\Entity\Reply;
use BackendBundle\BusinessManager\MailingManager;
use NotificationsBundle\Entity\ReplyNotification;
use Doctrine\ORM\EntityManager;



class NotificationManager 
{
	private $em;
    private $mailingManager;
    public function __construct(EntityManager $entityManager, MailingManager $mailingManager) 
    {
        $this->em = $entityManager;
        $this->mailingManager = $mailingManager;
    }

    public function shareNotification(User $sharer, User $user)
    {
        try {
            $notification = new ShareNotification();
            $notification->setType("Share Bussiness Overview");
            $notification->setText("Has promoted your business");
            $notification->setFromUser($sharer);
            $notification->setToUser($user);
            $this->em->persist($notification);
            $this->em->flush();
            $this->getContainer()->get('reciprocasrest.backendbundle.mailingManager')->sendEmail("Monday Email",$user,'Emails/Scheduled/monday.html.twig');
        } catch (Exception $e) {
            return "Could not notifiy user" . $e->getMessage();
        }
    }
    public function inivitationNotification(User $user)
    {
        try {

            $notification = new InvitationNotification();
            $notification->setType("Share Bussiness Overview");
            $notification->setText("Has provided you with");
            $notification->setFromUser($user);
            $notification->setToUser($user->getParent());
            $this->em->persist($notification);
            // send email to user 
        } catch (Exception $e) {
            return "Could not notifiy user" . $e->getMessage();
        }
    }
    public function replyNotification(Reply $reply)
    {
        try {
            $notification = new ReplyNotification();
            switch ($reply->getType()) {
                case 'a':
                $notification->setType("Advice");
                $notification->setText("Has gave you an advice");
                break;
                case 'pi':
                $notification->setType("Personal Intro");
                $notification->setText("Has provided you with ");
                break;
                case 'sr':
                $notification->setType("Supplier Recommendation");
                $notification->setText("Has provided you with ");
                break;
                
                default:
                    # code...
                break;
            }
            $notification->setPost($reply->getPost());
            $notification->setFromUser($reply->getUser());
            $notification->setToUser($reply->getPost()->getUser());
            $this->em->persist($notification);
            $this->em->flush();

            return $notification;
        } catch (Exception $e) {
            return "Could not notifiy user" . $e->getMessage();
        }
    }

    public function topRatedNotification(User $user)
    {
        try {

            $notification = new TopRatedNotification();
            $notification->setType("Share Bussiness Overview");
            $notification->setText("Has provided you with");
            $notification->setToUser($user);
            $this->em->persist($notification);
            // send email to user 
        } catch (Exception $e) {
            return "Could not notifiy user" . $e->getMessage();
        }
    }

    public function getNotifications(User $user)
    {
        try {
         $connection = $this->em->getConnection();
         $statement = $connection->prepare(
                "SELECT users.image as img , users.firstname as firstname , users.lastname as lastname , share_notification.id    ,  share_notification.touser_id     ,type,share_notification.created,seen ,  text , share_notification.fromuser_id  , 'post_id' as post_id FROM share_notification , users  WHERE share_notification.touser_id= :user AND  share_notification.fromuser_id = users.id
                UNION
                SELECT  users.image as img , users.firstname as firstname , users.lastname as lastname , top_rated_notification.id ,  top_rated_notification.touser_id ,type,top_rated_notification.created,seen ,  text , 'fromuser_id' , 'post_id' as post_id FROM top_rated_notification , users WHERE top_rated_notification.touser_id= :user 
                UNION
                SELECT users.image as img ,  users.firstname as firstname , users.lastname as lastname , reply_notification.id     ,  reply_notification.touser_id     ,type, reply_notification.created,seen ,  text , reply_notification.fromuser_id  , post_id as post_id  FROM reply_notification , users WHERE reply_notification.touser_id= :user AND  reply_notification.fromuser_id = users.id
                UNION
                SELECT users.image as img , users.firstname as firstname , users.lastname as lastname , invitation_notification.id,invitation_notification.touser_id  ,type, invitation_notification.created,seen ,  text , invitation_notification.fromuser_id ,'post_id' as post_id FROM invitation_notification , users WHERE invitation_notification.touser_id= :user AND  invitation_notification.fromuser_id = users.id ");
            $params['user'] = $user->getId();
            $statement->execute($params);
            return $statement->fetchAll();
        } catch (Exception $e) {
            return  "Could not retrieve notifications".$e->getMessage();
        }
    }

}