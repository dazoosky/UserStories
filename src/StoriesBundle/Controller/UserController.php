<?php

namespace StoriesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use StoriesBundle\Entity\User\User;
use StoriesBundle\Entity\User\Address;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @Route ("new/")
     * @param \StoriesBundle\Controller\User $user
     * @return type
     */
    public function newUserAction(Request $request) {
        $form = $this->createFormMethod();
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return $this->render('StoriesBundle:User:newUser.html.twig', array(
                'form'=>$form->createView()));
        }
        else {
            $this->saveToDB($form);
            return new Response('User added');
        }
    }
    
    /**
     * @Route("{id}/modify")
     */
    public function editUserAction(Request $request, $id) {
        $repository = $this->getDoctrine()->getRepository('StoriesBundle:User\User');
        $user = $repository->findOneById($id);
        $form = $this->createFormMethodToEdit($user, $id);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return $this->render('StoriesBundle:User:newUser.html.twig', array(
                'form'=>$form->createView()));
        }
        else {
            $this->saveToDB($form);
            return new Response('User updated');
        }
    }
     /**
      * @Route("{id}/delete")
      */
    public function deleteUserAction($id) {
        $repository = $this->getDoctrine()->getRepository('StoriesBundle:User\User');
        $user = $repository->findOneById($id);
        $em = $this->getDoctrine()->getManager();
        if (!$user) {
            return new Response('User doesnt exist');
        }
        $em->remove($user);
        $em->flush();
        return new Response('User deleted');
    }

    /**
     * @Route("{id}")
     */
    public function showUserAction($id) {
        $repository = $this->getDoctrine()->getRepository('StoriesBundle:User\User');
        $user = $repository->findOneById($id);
        $repository = $this->getDoctrine()->getRepository('StoriesBundle:User\Address');
        $addresses = $repository->findByUser($id);
        $repository = $this->getDoctrine()->getRepository('StoriesBundle:User\Phone');
        $phones = $repository->findByUser($id);
        $repository = $this->getDoctrine()->getRepository('StoriesBundle:User\Email');
        $emails = $repository->findByUser($id);
        return $this->render('StoriesBundle:User:showUser.html.twig', array("user"=>$user, "addresses"=>$addresses, "phones"=>$phones, "emails"=>$emails));
    }
    
    /**
     * @Route("/")
     */
    public function showAllUsersAction()
    {
        $repository = $this->getDoctrine()->getRepository('StoriesBundle:User\User');
        $users = $repository->findAll();
        return $this->render('StoriesBundle:User:showAllUsers.html.twig', array("users"=>$users));
    }
    
    public function saveToDB($form) {
        $user = $form->getData();
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
    }
        
    public function createFormMethod($user = null) {
        if ($user == null) {
            $user = new User();
        }
        $form = $this->createFormBuilder($user)
            ->setAction($this->generateUrl('stories_user_newuser'))
            ->setMethod('POST')
            ->add('name', 'text')
            ->add('surname', 'text')
            ->add('description', 'text')
            ->add('save', 'submit', array('label'=>'Add user'))
            ->getForm();
        return $form;
    }
    
    public function createFormMethodToEdit($user = null, $id) {
        if ($user == null) {
            $user = new User();
        }
        $form = $this->createFormBuilder($user)
            ->setAction($this->generateUrl('stories_user_edituser', array("id" => $id)))
            ->setMethod('POST')
            ->add('name', 'text')
            ->add('surname', 'text')
            ->add('description', 'text')
            ->add('save', 'submit', array('label'=>'Add user'))
            ->getForm();
        return $form;
    }

    
    
    
}
