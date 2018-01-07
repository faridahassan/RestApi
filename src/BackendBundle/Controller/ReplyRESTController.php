<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\Reply;
use BackendBundle\Form\ReplyType;

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
 * Reply controller.
 * @RouteResource("Reply")
 */
class ReplyRESTController extends VoryxController
{
    /**
     * Get a Reply entity
     *
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @return Response
     *
     */
    public function getAction(Reply $entity)
    {
        return $entity;
    }
    /**
     * Get all Reply entities.
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
            $entities = $em->getRepository('BackendBundle:Reply')->findBy($filters, $order_by, $limit, $offset);
            if ($entities) {
                return $entities;
            }

            return FOSView::create('Not Found', Codes::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Create a Reply entity.
     *
     * @View(statusCode=201, serializerEnableMaxDepthChecks=true, serializerGroups={"post_reply"})
     *
     * @param Request $request
     *
     *
     * @return Response
     *
     * @QueryParam(name="email", nullable=true, description="Offset from which to start listing notes.")
     *
     *  
     */
     
    public function postAction(Request $request )
    {
        $entity = new Reply();
        $email = $request->request->get('email');
        $message = $request->request->get('message');
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new ReplyType(), $entity, array("method" => $request->getMethod()));
        $this->removeExtraFields($request, $form);
        $form->handleRequest($request);
        $response2 ="gggg";
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $this->get('reciprocasrest.notificationsbundle.notificationManager')->replyNotification($entity);
            $response = $this->get('reciprocasrest.backendbundle.pointManager')->addReplyPoints($entity);
            
            if($entity->getType() === 'pi' )
            {
               $response2=$this->get('reciprocasrest.backendbundle.mailingManager')->sendIntroductionEmail($email, $message , $entity->getPost()->getUser());
            }
              if($entity->getType() === 'w' )
            {
               $response2=$this->get('reciprocasrest.backendbundle.mailingManager')->sendIntroductionEmail($email, $message , $entity->getPost()->getUser());
            }
            
            $em->persist($entity);
            $em->flush();
            
            return $entity;
        }

        return FOSView::create(array('errors' => $form->getErrors()), Codes::HTTP_INTERNAL_SERVER_ERROR);
    }
    /**
     * Update a Reply entity.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request
     * @param $entity
     *
     * @return Response
     */
    public function putAction(Request $request, Reply $entity)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $request->setMethod('PATCH'); //Treat all PUTs as PATCH
            $form = $this->createForm(new ReplyType(), $entity, array("method" => $request->getMethod()));
            $this->removeExtraFields($request, $form);
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em->flush();

                return $entity->getPost();
            }

            return FOSView::create(array('errors' => $form->getErrors()), Codes::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Partial Update to a Reply entity.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request
     * @param $entity
     *
     * @return Response
     */
    public function patchAction(Request $request, Reply $entity)
    {
        return $this->putAction($request, $entity);
    }
    /**
     * Delete a Reply entity.
     *
     * @View(statusCode=204)
     *
     * @param Request $request
     * @param $entity
     *
     * @return Response
     */
    public function deleteAction(Request $request, Reply $entity)
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
