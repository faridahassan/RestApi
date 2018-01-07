<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\Message;
use BackendBundle\Form\MessageType;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View as FOSView;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Voryx\RESTGeneratorBundle\Controller\VoryxController;

/**
 * Message controller.
 * @RouteResource("Message")
 */
class MessageRESTController extends VoryxController
{
    /**
     * Get Unread Messages Number  entities.
     *
     * @View(serializerEnableMaxDepthChecks=true, serializerGroups={"message"})
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return Response
     *
     *  */
    function getMessageUnreadNumberAction()
    {
        $provider = $this->container->get('fos_message.provider');
        $provider->getNbUnreadMessages();
        return $provider->getNbUnreadMessages();
        
    }
    /**
     * Get Inbox Message entities.
     *
     * @View(serializerEnableMaxDepthChecks=true , serializerGroups={"message"})
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return Response
     *
     */
    function getMessageInboxAction()
    {
        $provider = $this->container->get('fos_message.provider');
        $threads = $provider->getInboxThreads();
        $sent = $provider->getSentThreads();
        $messages = array();
        $index =  0 ;
        $messages = array_merge($threads,$sent);
        $messages = array_unique( $messages , SORT_REGULAR);

        return $messages;
        
    }
    /**
     * Get Sent Message entities.
     *
     * @View(serializerEnableMaxDepthChecks=true, serializerGroups={"message"})
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return Response
     *
     */
    function getMessageSentAction()
    {
        $provider = $this->container->get('fos_message.provider');
        $sent = $provider->getSentThreads();
        return $sent;
        
    }
     /**
     * Post Message entity
     *
     * @View(serializerEnableMaxDepthChecks=true, serializerGroups={"message"})
     *
     * @return Response
     * @QueryParam(name="message", nullable=true, description="Offset from which to start listing notes.")
     * @QueryParam(name="thread", requirements="\d+",default="-1", nullable=true, description="How many notes to return.")
     * @QueryParam(name="recepientid", requirements="\d+",nullable=true, description="How many notes to return.")
     *   
     */
    function postMyMessageAction(ParamFetcherInterface $paramFetcher ,Request $request)
    {
        $threadid = $request->request->get('thread');
        $message = $request->request->get('message');
        $recepientid = $request->request->get('recepientid');
        $em = $this->getDoctrine()->getManager();
    
        if($threadid!= -1 )
        {
            $thread=$em->getRepository('BackendBundle:Thread')->find($threadid);
            $composer =$this->container->get('fos_message.composer');
            $message = $composer->reply($thread)
                ->setSender($this->getUser())
                ->setBody($message)
                ->getMessage();
        }
        else
        {
            $sendermessage=$em->getRepository('BackendBundle:User')->find($recepientid);
            $threads=$em->getRepository('BackendBundle:Thread')->findAll();
            $newThread = 0;

            foreach($threads as $thread )
            {
               
                if($thread->getParticipants()[0]->getId() == $this->getUser()->getId())
                {
                    if($thread->getParticipants()[1]->getId()== $sendermessage->getId())
                    {
                        $composer =$this->container->get('fos_message.composer');
                        $message = $composer->reply($thread)
                            ->setSender($this->getUser())
                            ->setBody($message)
                            ->getMessage();
                        $newThread=1;
                         
                        
                    }
                    
                }
                elseif ($thread->getParticipants()[1]->getId() == $this->getUser()->getId())
                {
                    {
                        $composer =$this->container->get('fos_message.composer');
                        $message = $composer->reply($thread)
                            ->setSender($this->getUser())
                            ->setBody($message)
                            ->getMessage();
                        $newThread=1;
                         
                    }
                    
                }
 
            }
            if($newThread == 0 )
            {

                $composer =$this->container->get('fos_message.composer');
                $message = $composer->newThread()
                ->setSender($this->getUser())
                ->addRecipient($sendermessage)
                ->setSubject('New Thread')
                ->setBody($message)
                ->getMessage();
               
                
            }
          
        }
        $sender = $this->container->get('fos_message.sender');
        $sender->send($message);
        
        return $message;
        
    }
   
    /**
     * Get a Message entity
     *
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @return Response
     *
     */
    public function getAction(Message $entity)
    {
        return $entity;
    }
    /**
     * Get all Message entities.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return Response
     *
     * @QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing notes.")
     * @QueryParam(name="limit", requirements="\d+", default="20", description="How many notes to return.")
     * @QueryParam(name="order_by", nullable=true, array=true, description="Order by fields. Must be an array ie. &order_by[name]=ASC&order_by[description]=DESC")
     * @QueryParam(name="filters", nullable=true, array=true, description="Filter by fields. Must be an array ie. &filters[id]=3")
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher)
    {
        try {
            $offset = $paramFetcher->get('offset');
            $limit = $paramFetcher->get('limit');
            $order_by = $paramFetcher->get('order_by');
            $filters = !is_null($paramFetcher->get('filters')) ? $paramFetcher->get('filters') : array();

            $em = $this->getDoctrine()->getManager();
            $entities = $em->getRepository('BackendBundle:Message')->findBy($filters, $order_by, $limit, $offset);
            if ($entities) {
                return $entities;
            }

            return FOSView::create('Not Found', Codes::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Create a Message entity.
     *
     * @View(statusCode=201, serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request
     *
     * @return Response
     *
     */
    public function postAction(Request $request)
    {
        $entity = new Message();
        $form = $this->createForm(new MessageType(), $entity, array("method" => $request->getMethod()));
        $this->removeExtraFields($request, $form);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $entity;
        }

        return FOSView::create(array('errors' => $form->getErrors()), Codes::HTTP_INTERNAL_SERVER_ERROR);
    }
    /**
     * Update a Message entity.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request
     * @param $entity
     *
     * @return Response
     */
    public function putAction(Request $request, Message $entity)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $request->setMethod('PATCH'); //Treat all PUTs as PATCH
            $form = $this->createForm(new MessageType(), $entity, array("method" => $request->getMethod()));
            $this->removeExtraFields($request, $form);
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em->flush();

                return $entity;
            }

            return FOSView::create(array('errors' => $form->getErrors()), Codes::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Partial Update to a Message entity.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request
     * @param $entity
     *
     * @return Response
     */
    public function patchAction(Request $request, Message $entity)
    {
        return $this->putAction($request, $entity);
    }
    /**
     * Delete a Message entity.
     *
     * @View(statusCode=204)
     *
     * @param Request $request
     * @param $entity
     *
     * @return Response
     */
    public function deleteAction(Request $request, Message $entity)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($entity);
            $em->flush();

            return null;
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
