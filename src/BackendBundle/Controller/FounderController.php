<?php

namespace BackendBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BackendBundle\Entity\Founder;

/**
 * Founder controller.
 *
 */
class FounderController extends Controller
{

    /**
     * Lists all Founder entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackendBundle:Founder')->findAll();

        return $this->render('BackendBundle:Founder:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Founder entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackendBundle:Founder')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Founder entity.');
        }

        return $this->render('BackendBundle:Founder:show.html.twig', array(
            'entity'      => $entity,
        ));
    }
}
