<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\Post;
use BackendBundle\Entity\User;
use BackendBundle\Form\PostType;
use JMS\Serializer\SerializationContext;

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
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Voryx\RESTGeneratorBundle\Controller\VoryxController;

/**
 * Post controller.
 * @RouteResource("Post")
 */
class PostRESTController extends VoryxController
{
     /**
     * Get a Newsfeed entity
     *
     * @View(serializerEnableMaxDepthChecks=true ,  serializerGroups={"myposts_a" , "myposts_sr" , "myposts_pi"})
     *
     * @return Response
     * @QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing notes.")
     * @QueryParam(name="limit", requirements="\d+", default="20", description="How many notes to return.")
     * @QueryParam(name="order_by", nullable=true, array=true, description="Order by fields. Must be an array ie. &order_by[name]=ASC&order_by[description]=DESC")
     * @QueryParam(name="filters", nullable=true, array=true, description="Filter by fields. Must be an array ie. &filters[id]=3")
     */
    public function getNewsfeedAction(ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $limit = $paramFetcher->get('limit');
        $filter = $paramFetcher->get('filters');
//        return $filter;
        $NewsfeedManager = $this->container->get('reciprocasrest.backendbundle.newsFeedManager');
        $posts = $NewsfeedManager->getNewsFeed( $filter , $limit , $offset );
        foreach ($posts as $obj) {
            $obj->getReplyDistinctNames();
        }
        if($posts)
        {
            return $posts; 
        }
        else
        {
            return "no new posts";
        }
       
    }
    
    /**
     * Get a Post entity
     *
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @return Response
     *
     */
    public function getAction(Post $entity, Request $request)
    {
        // $serializer = $this->get('serializer');
        // $serializer->serialize(new Post(), 'json', SerializationContext::create()->setGroups({'myposts_sr'}));
        //If conditions
        switch ($entity->getType()) {
            case 'sr':
                $request->attributes->get('_view')->setSerializerGroups(['myposts_sr']);
                break;
            case 'pi':
                $request->attributes->get('_view')->setSerializerGroups(['myposts_pi']);
                break;
            case 'a':
                $request->attributes->get('_view')->setSerializerGroups(['myposts_a']);
                break;
            
            default:
                # code...
                break;
        }
        
        return $entity;
    }
    /**
     * Get all Post entities.
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
            $entities = $em->getRepository('BackendBundle:Post')->findBy($filters, array('created'=>'DESC'), $limit, $offset);
            if ($entities) {
                foreach ($entities as $obj) {
                        $obj->getReplyDistinctNames();
                }
                return $entities;
            }

            return FOSView::create('Not Found', Codes::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get all Supplier Recommendation Post entities.
     *
     * @View(serializerEnableMaxDepthChecks=true, serializerGroups={"myposts_sr"})
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
    public function cgetMineSrAction(User $loggedIn ,ParamFetcherInterface $paramFetcher)
    {
        try {
            $offset = $paramFetcher->get('offset');
            $limit = $paramFetcher->get('limit');
            $order_by = $paramFetcher->get('order_by');
            // $filters = !is_null($paramFetcher->get('filters')) ? $paramFetcher->get('filters') : array();
            
            $em = $this->getDoctrine()->getManager();

            //Overriden filders
            $filters = ['user' => $loggedIn->getId(), 'type' => 'sr'];
            $entities = $em->getRepository('BackendBundle:Post')->findBy($filters, array('created'=>'DESC'), $limit, $offset);
            if ($entities) {
                 foreach ($entities as $obj) {
                        $obj->getReplyDistinctNames();
                }
                return $entities;
            }

            return FOSView::create('Not Found', Codes::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get all Advice Post entities.
     *
     * @View(serializerEnableMaxDepthChecks=true, serializerGroups={"myposts_a"})
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
    public function cgetMineAAction(User $loggedIn ,ParamFetcherInterface $paramFetcher)
    {
        try {
            $offset = $paramFetcher->get('offset');
            $limit = $paramFetcher->get('limit');
            $order_by = $paramFetcher->get('order_by');
            // $filters = !is_null($paramFetcher->get('filters')) ? $paramFetcher->get('filters') : array();
            
            $em = $this->getDoctrine()->getManager();

            //Overriden filders
            $filters = ['user' => $loggedIn->getId(), 'type' => 'a'];
            $entities = $em->getRepository('BackendBundle:Post')->findBy($filters, array('created'=>'DESC'), $limit, $offset);
            if ($entities) {
                 foreach ($entities as $obj) {
                        $obj->getReplyDistinctNames();
                }
                return $entities;
            }

            return FOSView::create('Not Found', Codes::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get all Personal Introduction Post entities.
     *
     * @View(serializerEnableMaxDepthChecks=true, serializerGroups={"myposts_pi"})
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
    public function cgetMinePiAction(User $loggedIn ,ParamFetcherInterface $paramFetcher)
    {
        try {
            $offset = $paramFetcher->get('offset');
            $limit = $paramFetcher->get('limit');
            $order_by = $paramFetcher->get('order_by');
            // $filters = !is_null($paramFetcher->get('filters')) ? $paramFetcher->get('filters') : array();

            $em = $this->getDoctrine()->getManager();

            //Overriden filders
            $filters = ['user' => $loggedIn->getId(), 'type' => 'pi'];
            $entities = $em->getRepository('BackendBundle:Post')->findBy($filters,array('created'=>'DESC'), $limit, $offset);
            if ($entities) {
                foreach ($entities as $obj) {
                    $obj->getReplyDistinctNames();
                }
                return $entities;
            }

            return FOSView::create('Not Found', Codes::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }








    /**
     * Create a Post entity.
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
        $entity = new Post();
        $form = $this->createForm(new PostType(), $entity, array("method" => $request->getMethod()));
        $this->removeExtraFields($request, $form);
        $form->handleRequest($request);
            // return $request;
        if ($form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $t = $entity->getType();
            
            if($t == 'q' && !$this->getUser()->hasRole('ROLE_SUPER_ADMIN'))
                throw new AccessDeniedException();

            if($t == ('a' or 'sr' or 'pi' or 'q') ){
              //  echo "<pre>"; \Doctrine\Common\Util\Debug::dump($entity);echo "<pre>"; exit;
       
               // $entity->upload();
               
                $em->persist($entity);
                $em->flush();
                return $entity;
            } else {
                return 'invalid post type';
                exit;
            }
//            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $entity;
        }

        return FOSView::create(array('errors' => $form->getErrors()), Codes::HTTP_INTERNAL_SERVER_ERROR);
    }
    /**
     * Update a Post entity.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request
     * @param $entity
     *
     * @return Response
     */
    public function putAction(Request $request, Post $entity)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $request->setMethod('PATCH'); //Treat all PUTs as PATCH
            $form = $this->createForm(new PostType(), $entity, array("method" => $request->getMethod()));
            $this->removeExtraFields($request, $form);
            $form->handleRequest($request);
            if ($form->isValid()) {
                
                $em->persist($entity);
                $em->flush();

                return $entity;
            }

            return FOSView::create(array('errors' => $form->getErrors()), Codes::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Partial Update to a Post entity.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request
     * @param $entity
     *
     * @return Response
     */
    public function patchAction(Request $request, Post $entity)
    {
        return $this->putAction($request, $entity);
    }
    /**
     * Delete a Post entity.
     *
     * @View(statusCode=204)
     *
     * @param Request $request
     * @param $entity
     *
     * @return Response
     */
    public function deleteAction(Request $request, Post $entity)
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
