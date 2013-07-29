<?php

namespace Admin\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Admin\UserBundle\Entity\User;
use Admin\UserBundle\Form\UserType;
use Admin\CommonBundle\Entity\Common;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $amExtraParameters = Common::bindQueryParams($this, 'id', 'email');

        $listObject = $this->getDoctrine()->getRepository('AdminUserBundle:User')->getList($amExtraParameters);

        $paginator = $this->get('knp_paginator');
        $listObject = $paginator->paginate(
            $listObject,
            $this->get('request')->request->get('page',1),
            Common::$per_page
        );

        $asReturn = array('listObject' => $listObject,  'amExtraParameters' => $amExtraParameters);

        if($request->isXmlHttpRequest()) {
            $asContent = $this->render('AdminUserBundle:Default:listingPart.html.twig', $asReturn);
            $return = json_encode(array('number' => 0, 'content' => $asContent->getContent()));
            return new Response($return,200,array('Content-Type'=>'application/json'));
        } else
            return $this->render('AdminUserBundle:Default:index.html.twig', array('listObject' => $listObject));
    }

    public function addAction(Request $request)
    {
        if($request->query->get('id'))
        {
            $entity = $this->getDoctrine()->getRepository('AdminUserBundle:User')->find($request->query->get('id'));
        }
        else
            $entity = new User();


        $form = $this->createForm(new UserType(), $entity);

        if($request->isMethod('post')){
            $form->bind($request);

            if ($form->isValid()) {

                $em = $this->getDoctrine()->getEntityManager();

                $entity->upload();

                if(!$request->query->get('id'))
                    $entity->prePersist();

                $em->persist($form->getData());
                $em->flush();

                return $this->redirect($this->generateUrl('user_list'));
            }
        }

        return $this->render('AdminUserBundle:Default:add.html.twig', array('form' => $form->createView()));
    }
}
