<?php

namespace StoriesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use StoriesBundle\Entity\User\Phone;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PhoneController extends Controller
{
    /**
     * @Route("/{userId}/addPhone")
     */
    public function addPhoneAction(Request $request, $userId)
    {
        $form = $this->createFormMethod($userId);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return $this->render('StoriesBundle:Phone:add_phone.html.twig', array(
                'form'=>$form->createView()));
        }
        else {
            $this->saveToDB($form, $userId);
            return $this->redirectToRoute('stories_user_showuser', array('id' => $userId));
        }
    }

    /**
     * @Route("/{id}/editPhone")
     */
    public function editPhoneAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getRepository('StoriesBundle:User\Phone');
        $phone = $repository->findOneById($id);
        $form = $this->createFormMethodToEdit($phone, $id);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return $this->render('StoriesBundle:Phone:add_phone.html.twig', array(
                'form'=>$form->createView()));
        }
        else {
            $this->saveToDB($form);
            return new Response('Phone updated');
        }
    }

    /**
     * @Route("/{id}/deletePhone")
     */
    public function deletePhoneAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('StoriesBundle:User\Phone');
        $phone = $repository->findOneById($id);
        $em = $this->getDoctrine()->getManager();
        if (!$phone) {
            return new Response('Phone doesnt exist');
        }
        $em->remove($phone);
        $em->flush();
        return new Response('Phone deleted');
    }

    /**
     * @Route("{userId}/allPhoneesByUser")
     */
    public function allPhoneesByUserAction($userId)
    {
        $repository = $this->getDoctrine()->getRepository('StoriesBundle:User\Phone');
        $phonees = $repository->findAllByUserId($userId);
        return $phonees;
    }

    public function createFormMethod($userId, $phone = null) {
        if ($phone == null) {
            $phone = new Phone();
        }
        $form = $this->createFormBuilder($phone)
            ->setAction($this->generateUrl('stories_phone_addphone', array("userId"=>$userId)))
            ->setMethod('POST')
            ->add('number', 'integer')
            ->add('type', 'text')
            ->add('save', 'submit', array('label'=>'Add phone'))
            ->getForm();
        return $form;
    }
    
    public function createFormMethodToEdit($phone = null, $id) {
        if ($phone == null) {
            $phone = new Phone();
        }
        $form = $this->createFormBuilder($phone)
            ->setAction($this->generateUrl('stories_phone_editphone', array("id" => $id)))
            ->setMethod('POST')
            ->add('number', 'integer')
            ->add('type', 'text')
            ->add('save', 'submit', array('label'=>'Add phone'))
            ->getForm();
        return $form;
    }

    public function saveToDB($form, $userId) {
        $phone = $form->getData();
        $repository = $this->getDoctrine()->getRepository('StoriesBundle:User\User');
        $user = $repository->findOneById($userId);
        $phone->setUser($user);
        $em = $this->getDoctrine()->getManager();
        $em->persist($phone);
        $em->flush();
    }
}
