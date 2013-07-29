<?php

namespace Admin\CategoryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Admin\CategoryBundle\Entity\Category;
use Admin\CategoryBundle\Form\CategoryType;
use Admin\CommonBundle\Entity\Common;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $amExtraParameters = Common::bindQueryParams($this,'lft', 'title');

        $listObject = $this->getDoctrine()->getRepository('AdminCategoryBundle:Category')->getList($amExtraParameters);

        $paginator = $this->get('knp_paginator');
        $listObject = $paginator->paginate(
            $listObject,
            $this->get('request')->request->get('page',1),
            Common::$per_page
        );

        $asReturn = array('listObject' => $listObject,  'amExtraParameters' => $amExtraParameters);

        if($request->isXmlHttpRequest()) {
            $asContent = $this->render('AdminCategoryBundle:Default:listingPart.html.twig', $asReturn);
            $return = json_encode(array('number' => $listObject->getTotalItemCount(), 'content' => $asContent->getContent()));
            return new Response($return, 200, array('Content-Type'=>'application/json'));
        } else
            return $this->render('AdminCategoryBundle:Default:index.html.twig', array('listObject' => $listObject));
    }

    public function addAction(Request $request)
    {
        if($request->query->get('id'))
        {
            $entity = $this->getDoctrine()->getRepository('AdminCategoryBundle:Category')->find($request->query->get('id'));
        }
        else
            $entity = new Category();


        $form = $this->createForm(new CategoryType(), $entity);

        if($request->isMethod('post')){
            $form->bind($request);

            if ($form->isValid()) {

                $em = $this->getDoctrine()->getEntityManager();

                $em->persist($form->getData());
                $em->flush();

                return $this->redirect($this->generateUrl('category_list'));
            }
        }

        return $this->render('AdminCategoryBundle:Default:add.html.twig', array('form' => $form->createView()));
    }
}
