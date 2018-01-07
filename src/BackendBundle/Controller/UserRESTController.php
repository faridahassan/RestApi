<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\User;
use BackendBundle\Entity\Founder;
use BackendBundle\Entity\User_Link;
use BackendBundle\Entity\Post;
use BackendBundle\Form\UserType;

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
use JMS\Serializer\SerializationContext;
use BackendBundle\BusinessManager\InvitationManager;
use Voryx\RESTGeneratorBundle\Controller\VoryxController;

/**
 * User controller.
 * @RouteResource("User")
 */
class UserRESTController extends VoryxController
{
     /**
     * Get Top Enterpreneurs Users entity
     *
     * @View(serializerEnableMaxDepthChecks=true  , serializerGroups={"usersummery"})
     *
     * @return Response
     * 
     *
     * @QueryParam(name="name", nullable=true, description="message")
     * @QueryParam(name="email", nullable=true, description="message")
     * @QueryParam(name="country", nullable=true, description="message")
     */
    public function getJoinrequestAction(ParamFetcherInterface $paramFetcher)
    {
        $name = $paramFetcher->get('name');
        $email = $paramFetcher->get('email');
        $country = $paramFetcher->get('country');
        $founder = new Founder();
        $founder->setName($name);
        $founder->setEmail($email);
        $founder->setCountry($country);
        $em = $this->getDoctrine()->getManager();
        $em->persist($founder);
        $em->flush();
        $response2=$this->get('reciprocasrest.backendbundle.mailingManager')->sendJoinRequestEmail('s.sayed@equation-solutions.com', $email );
        return $response2; 
    }
            
    /**
     * Get Top Enterpreneurs Users entity
     *
     * @View(serializerEnableMaxDepthChecks=true , serializerGroups={"usersummery"})
     *
     * @return Response
     * 
     *@QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing notes.")
     * @QueryParam(name="limit", requirements="\d+", default="20", description="How many notes to return.")
     * @QueryParam(name="order_by", nullable=true, array=true, description="Order by fields. Must be an array ie. &order_by[name]=ASC&order_by[description]=DESC")
     * @QueryParam(name="filters",  default="4", description="Filter by fields. Must be an array ie. &filters[id]=3")
     *
     */
    public function getTopenterpreneursAction(ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $limit = $paramFetcher->get('limit');
        $filter = $paramFetcher->get('filters');
        
        $TopEnterprenuersManager = $this->container->get('reciprocasrest.backendbundle.topEnterpreneursManager');
        $users = $TopEnterprenuersManager->getTopEnterpreneurs( $filter ,  $limit , $offset );
        if($users)
        {
        return $users;
        }
        else
        {
            return "NO Users available";
        }
    }
    /**
     * Get a User entity
     *
     * @View(serializerEnableMaxDepthChecks=true , serializerGroups={"usersummery"})
     *
     * @return Response
     *
     */
    public function getAction(User $entity)
    {
        
        return $entity;
    }
    function make_absolute($url, $base) 
    {
        // Return base if no url
        if( ! $url) return $base;

        // Return if already absolute URL
        if(parse_url($url, PHP_URL_SCHEME) != '') return $url;

        // Urls only containing query or anchor
        if($url[0] == '#' || $url[0] == '?') return $base.$url;

        // Parse base URL and convert to local variables: $scheme, $host, $path
        extract(parse_url($base));

        // If no path, use /
        if( ! isset($path)) $path = '/';

        // Remove non-directory element from path
        $path = preg_replace('#/[^/]*$#', '', $path);

        // Destroy path if relative url points to root
        if($url[0] == '/') $path = '';

        // Dirty absolute URL
        $abs = "$host$path/$url";

        // Replace '//' or '/./' or '/foo/../' with '/'
        $re = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#' , '#\\\\#', '#\\\.\.#');
        for($n = 1; $n > 0; $abs = preg_replace($re, '/', $abs, -1, $n)) {}
       // $abs=str_replace('/',"/",$abs);
    //    return $abs;
        // Absolute URL is ready!
        return $scheme.'://'.$abs;
    }
    
     /**
     * Create a User entity.
     *
     * @View(statusCode=201, serializerEnableMaxDepthChecks=true , serializerGroups={"usersummery"})
     *
     *
     * @param Request $req
     *
     * @return Response
     *@QueryParam(name="url", nullable=true, description="Url of another website")
     *
     */
    public function postBusinesspulledAction(Request $req)
    {
      //  $url = $paramFetcher->get('url');
       
        $paragraph= array();
        $paragraphs= array();
        $links= array();
        $count=0;
        $url = $req->request->get('url');

        $url = "http://".$url;
        $request = curl_init();
        curl_setopt_array($request, array
        (
            CURLOPT_URL => $url,

            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HEADER => FALSE,

            CURLOPT_SSL_VERIFYPEER => TRUE,
            CURLOPT_CAINFO => 'cacert.pem',

            CURLOPT_FOLLOWLOCATION => TRUE,
            CURLOPT_MAXREDIRS => 10,
        ));
        $response = curl_exec($request);
        curl_close($request);
        $document = new \DOMDocument();
        if($response)
        {
            libxml_use_internal_errors(true);
            $document->loadHTML($response);
            libxml_clear_errors();
        }
        $images = array();

        foreach($document->getElementsByTagName('img') as $img)
        {
            // Extract what we want
            $image = array
            (
                'src' => self::make_absolute($img->getAttribute('src'), $url),
            );

            // Skip images without src
            if( ! $image['src'])
                continue;

            // Add to collection. Use src as key to prevent duplicates.
            $images[$count] = $image;
            $count ++;
        }
        $count=0;
        foreach($document->getElementsByTagName('p') as $p)
        {
            // Extract what we want
            $paragraph = array
            (
                'value' => $p->textContent,
            );

            // Skip images without src
            if( ! $paragraph['value'])
                continue;

            // Add to collection. Use src as key to prevent duplicates.
            $paragraphs[$count] =  $paragraph;
            $count++;
        }

        
        $tags = get_meta_tags($url);
        if($paragraph)
        {
        $paragraph = array_values($paragraph);
        }
       
        header('content-type: application/json; charset=utf-8');
        if(isset($tags['description']))
        {
            $description = $tags['description'];
        }
        elseif (isset( $paragraphs[0]['value'])) {
          $description = $paragraphs[0]['value'].$paragraphs[1]['value'];
         }
        else {
             $description ="No description available for this website";
        }
        if(isset($images[0]['src']))
        {
            $logourl = $images[0]['src'];
        }
        else
        {
            $logourl ="NAN";
        }
        $result = array('logo' =>  $logourl  ,'description'=>  $description );
        return $result;

    }
    /**
     * Get a User entity
     *
     * @View(serializerEnableMaxDepthChecks=true , serializerGroups={"usersummery"} )
     *
     * @return Response
     *
     */
    public function getLoggeduserAction()
    {
        
        return $this->getUser();
    }
    /**
     * Get a User entity
     *
     * @View(serializerEnableMaxDepthChecks=true , serializerGroups={"usersummery"})
     *
     * @return Response
     *
     */
    public function getElasticAction($keyword)
    {
        $finder = $this->container->get('fos_elastica.finder.reciprocas.user');
        $results = $finder->find($keyword);
        // $results = $finder->findHybrid($keyword);
        return $results;
    }

    /**
     * Get a User Summery entity
     *
     * @View(serializerEnableMaxDepthChecks=true, serializerGroups={"usersummery"})
     *
     * @return Response
     *
     */
    public function getSummeryAction(User $entity)
    {
        
        return $entity;
    }
    /**
     * Get a logged in Summery entity
     *
     *  @View(serializerEnableMaxDepthChecks=true, serializerGroups={"usersummery"})
     *
     * @return Response
     *
     */
    public function getMySummeryAction()
    {
        
        return $this->getUser();
    }
     /**
     * Get a Business Overview entity
     *
     *  @View(serializerEnableMaxDepthChecks=true, serializerGroups={"businessoverview"})
     *
     * @return Response
     *
     */
    public function getBusinessOverviewAction(User $entity)
    {
        
        return $entity;
    }
    /**
     * Get a Business Overview entity
     *
     *  @View(serializerEnableMaxDepthChecks=true, serializerGroups={"businessoverview"})
     *
     * @return Response
     *
     */
    public function getMyBusinessOverviewAction()
    {
        
        return $this->getUser();
    }
    /**
     * Get all User entities.
     *
     * @View(serializerEnableMaxDepthChecks=true , serializerGroups={"usersummery"})
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
            $entities = $em->getRepository('BackendBundle:User')->findBy($filters, $order_by, $limit, $offset);
            if ($entities) {
                return $entities;
            }

            return FOSView::create('Not Found', Codes::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Create a User entity.
     *
     * @View(statusCode=201, serializerEnableMaxDepthChecks=true)
     *
     *
     * @param Request $request
     *
     * @return Response
     *
     * @QueryParam(name="email", nullable=false,description="Email.")
     * @QueryParam(name="filters", nullable=true, array=true, description="Filter by fields. Must be an array ie. &filters[id]=3")
     *
     */
    public function postInviteAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $invitationManager = $this->get('reciprocasrest.backendbundle.mailingManager')->sendEmail();

        $email = $paramFetcher->get('email');

        return $email;

    }


    /**
     * Create a User entity.
     *
     * @View(statusCode=201, serializerEnableMaxDepthChecks=true)
     *
     *
     * @param Request $request
     *
     * @return Response
     *
     * @QueryParam(name="firstname", nullable=false, description="first name.")
     * @QueryParam(name="lastname", nullable=false, description="last name.")
     * @QueryParam(name="email", nullable=false,description="Email.")
     * @QueryParam(name="businessurl", nullable=false,description="url")
     * @QueryParam(name="businessoverview", nullable=false,description="overview")
     * @QueryParam(name="password", nullable=false, description="Password in plain text.")
     * @QueryParam(name="code", nullable=true, description="Password in plain text.")
     * @QueryParam(name="filters", nullable=true, array=true, description="Filter by fields. Must be an array ie. &filters[id]=3")
     *
     */
    public function postAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        
        ////////////////////            //////////////////////////////////
        // $entity = new User();
        // $form = $this->createForm(new UserType(), $entity, array("method" => $request->getMethod()));
        // $this->removeExtraFields($request, $form);
        // $form->handleRequest($request);

        // if ($form->isValid()) {
        //     $em = $this->getDoctrine()->getManager();
        //     $em->persist($entity);
        //     $em->flush();

        //     return $entity;
        // }
        

        // return FOSView::create(array('errors' => $form->getErrors()), Codes::HTTP_INTERNAL_SERVER_ERROR);
        ///////////////////////////////////////////////////////////////////

        $em = $this->getDoctrine()->getManager();
        /////////////     THIS METHOD IS TEMPORARILY    ///////////////////
        // $username = $paramFetcher->get('username');
        $firstName = $paramFetcher->get('firstname');
        $lastName = $paramFetcher->get('lastname');
        $email    = $paramFetcher->get('email');
        $businessurl    = $paramFetcher->get('businessurl');
        $businessoverview    = $paramFetcher->get('businessoverview');
        $password = $paramFetcher->get('password');
        $code     = $paramFetcher->get('code');
        $entities = $em->getRepository('BackendBundle:User')->findBy(array('email'=>$email));
        if( $entities)
        {  
            return "Sorry, This email already exists.";
        }
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->createUser();
        $user->setUsername($email);
        
        $user->setEmail($email);
        $user->setFirstname($firstName);
        $user->setLastname($lastName);
        $user->setPlainPassword($password);
        $user->setBusinessurl($businessurl);
        $user->setBusinessoverview($businessoverview);
        $user->addLink(new User_Link('facebook',$user , 'NAN'));
        $user->addLink(new User_Link('twitter',$user , 'NAN'));
        $user->addLink(new User_Link('linkedin',$user , 'NAN'));
        $user->addLink(new User_Link('cape66',$user , 'www.cape66.com/'.$firstName.'.'.$lastName));
        $user->setImage('NAN');
        $user->setLogourl('NAN');
        $user->setEnabled(true);

        // SET REFERENCE OF PARENT // 
        // Check to see if this code is valid
        if(!is_null($code)){
            $parent = $em->getRepository('BackendBundle:User')->findOneByInvitationCode($code);
            if (!is_null($parent)){
                $this->get('reciprocasrest.backendbundle.pointManager')->addInvitationPoints($code);
                $user->setParent($parent);
            }
        }
        //////////////////////////////
        try {
            $userManager->updateUser($user);
            
            // Generate welcome post
            $post = new Post();
            $post->setType('w');
            $post->setMessage('Please welcome this user user');
            $post->setUrl('google.com');
            $post->setUser($user);
            $em->persist($post);
            // End of generate welcome post
            $em->persist($user);
            $em->flush();
            return $user;
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Upload  a User Image entity.
     *@View(serializerEnableMaxDepthChecks=true )
     * 
     *
     * @param Request $request
     * @param $entity
     *
     * @return Response
     */
    public function postImageAction(Request $request ,User $entity )
    {   
       
        $logourl = $entity->getLogourl();
        $image =$entity->getImage();
        $em = $this->getDoctrine()->getManager();
        $emailcon= $entity->getEmailCanonical();
        $email = $entity->getEmail();
        $pass = $entity->getPassword();
        $form = $this->createForm(new UserType(), $entity, array("method" => $request->getMethod()));

        $form->handleRequest($request);
         
        if($entity->filetype === "profile")
        {
            $entity->setLogourl($logourl);
        }
        else if ($entity->filetype ==="logo")
        {
            $entity->setImage($image);
        }
        
        $entity->setEmail($email);
        $entity->setEmailCanonical($emailcon);
        $entity->setPassword($pass);

        $em->persist($entity);
        $em->flush();
        return $entity;
          
    }
    /**
     * Update a User entity.
     *
     * @View(serializerEnableMaxDepthChecks=true )
     *
     * @param Request $request
     * @param $entity
     *
     * @return Response
     */
    public function putUpdateAction(Request $request, User $entity)
    {
     
        try {
            $em = $this->getDoctrine()->getManager();
            $image = $entity->getImage();
            $request->setMethod('PATCH'); //Treat all PUTs as PATCH
            
            
            $form = $this->createForm(new UserType(), $entity, array("method" => $request->getMethod()));
            $links = $request->get('links', array());
           
            $this->removeExtraFields($request, $form);
            $form->handleRequest($request);
            
            if ($form->isValid()) {
                
                if($links!= NULL )
                {
                    foreach ($links as $link )
                    {

                       $linkEntity  = $em->getRepository('BackendBundle:User_Link')->find($link['id']);
                       $linkEntity->setLink($link['link']);
                       $em->persist($linkEntity);

                    }
                }
                $entity->setImage($image);
                $entities = $em->getRepository('BackendBundle:User')->findBy(array('email'=>$entity->getEmail()));
                if($entities && $entities[0]->getId() == $entity->getId() || !$entities)
                {
                    $em->persist($entity);
                    $em->flush();
                    return $entity;
                }
                else
                {
                    return "Sorry, This email already exists.";
                }

            }

            return FOSView::create(array('errors' => $form->getErrors()), Codes::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
     /**
     * Update Business Overview
     *
     * @View(serializerEnableMaxDepthChecks=true , serializerGroups={"businessoverview" })
     *
     * @param Request $request
     * @param $entity
     *
     * @return Response
     */
    public function putBusinessOverviewAction(Request $request, User $entity)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $request->setMethod('PATCH'); //Treat all PUTs as PATCH
            $form = $this->createForm(new UserType(), $entity, array("method" => $request->getMethod()));
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
     * Partial Update to a User entity.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request
     * @param $entity
     *
     * @return Response
     */
    public function patchAction(Request $request, User $entity)
    {
        return $this->putAction($request, $entity);
    }
    /**
     * Delete a User entity.
     *
     * @View(statusCode=204)
     *
     * @param Request $request
     * @param $entity
     *
     * @return Response
     */
    public function deleteAction(Request $request, User $entity)
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

    public function getNotificationsAction(Request $request)
    {
        try {
            $entity = $this->getUser();
            $notifications = $this->get('reciprocasrest.notificationsbundle.notificationManager')->getNotifications($entity);;
            return $notifications;
           return FOSView::create('Not Found', Codes::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function putNotificationsAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $entity = $this->getUser();
            $notifications = $this->get('reciprocasrest.notificationsbundle.notificationManager')->getNotifications($entity);
            foreach ($notifications as $notification) {
                $notification->setSeen(true);
                $em->persist($notification);
                $em->flush();
            }
            return $notifications;
           return FOSView::create('Not Found', Codes::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @View(serializerEnableMaxDepthChecks=true , serializerGroups={"myteam"})
     *
     * @param Request $request
     * @param $entity
     *
     * @return Response
     */
    public function getHelpedmeAction(Request $request, User $user)
    {
        try {
            $entity = $this->getUser();
            $users = $this->get('reciprocasrest.backendbundle.userManager')->getPeopleHelpedMe($user);;
            return $users;
           return FOSView::create('Not Found', Codes::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @View(serializerEnableMaxDepthChecks=true , serializerGroups={"myteam"})
     *
     * @param Request $request
     * @param $entity
     *
     * @return Response
     */
    public function getTeamsizeAction(Request $request, User $user)
    {
        try {
            $teamSize = $this->get('reciprocasrest.backendbundle.userManager')->getMyTeamSize($user);;
            return $teamSize;
           return FOSView::create('Not Found', Codes::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @View(serializerEnableMaxDepthChecks=true , serializerGroups={"myteam"})
     *
     * @param Request $request
     * @param $entity
     *
     * @return Response
     */
    public function getIhelpedAction(Request $request, User $user)
    {
        try {
            $entity = $this->getUser();
            $users = $this->get('reciprocasrest.backendbundle.userManager')->getPeopleIHelped($user);;
            return $users;
           return FOSView::create('Not Found', Codes::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
