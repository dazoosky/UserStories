<?php

namespace StoriesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use StoriesBundle\Entity\User\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EmailController extends Controller
{
    /**
     * @Route("/{userId}/addEmail")
     */
    public function addEmailAction(Request $request, $userId)
    {
        $form = $this->createFormMethod($userId);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return $this->render('StoriesBundle:Email:add_email.html.twig', array(
                'form'=>$form->createView()));
        }
        else {
            $this->saveToDB($form, $userId);
            return $this->redirectToRoute('stories_user_showuser', array('id' => $userId));
        }
    }

    /**
     * @Route("/{id}/editEmail")
     */
    public function editEmailAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getRepository('StoriesBundle:User\Email');
        $email = $repository->findOneById($id);
        $form = $this->createFormMethodToEdit($email, $id);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return $this->render('StoriesBundle:Email:add_email.html.twig', array(
                'form'=>$form->createView()));
        }
        else {
            $this->saveToDB($form);
            return new Response('Phone updated');
        }
    }

    /**
     * @Route("/{id}/deleteEmail")
     */
    public function deleteEmailAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('StoriesBundle:User\Email');
        $email = $repository->findOneById($id);
        $em = $this->getDoctrine()->getManager();
        if (!$email) {
            return new Response('Email doesnt exist');
        }
        $em->remove($email);
        $em->flush();
        return new Response('Email deleted');
    }

    /**
     * @Route("{userId}/allEmailesByUser")
     */
    public function allEmailesByUserAction($userId)
    {
        $repository = $this->getDoctrine()->getRepository('StoriesBundle:User\Email');
        $emailes = $repository->findAllByUserId($userId);
        return $emailes;
    }

    public function createFormMethod($userId, $email = null) {
        if ($email == null) {
            $email = new Email();
        }
        $form = $this->createFormBuilder($email)
            ->setAction($this->generateUrl('stories_email_addemail', array("userId"=>$userId)))
            ->setMethod('POST')
            ->add('email', 'email')
            ->add('type', 'text')
            ->add('save', 'submit', array('label'=>'Add email'))
            ->getForm();
        return $form;
    }
    
    public function createFormMethodToEdit($email = null, $id) {
        if ($email == null) {
            $email = new Email();
        }
        $form = $this->createFormBuilder($email)
            ->setAction($this->generateUrl('stories_email_editemail', array("id" => $id)))
            ->setMethod('POST')
            ->add('email', 'email')
            ->add('type', 'text')
            ->add('save', 'submit', array('label'=>'Add email'))
            ->getForm();
        return $form;
    }

    public function saveToDB($form, $userId) {
        $email = $form->getData();
        $repository = $this->getDoctrine()->getRepository('StoriesBundle:User\User');
        $user = $repository->findOneById($userId);
        $email->setUser($user);
        $em = $this->getDoctrine()->getManager();
        $em->persist($email);
        $em->flush();
    }

}
