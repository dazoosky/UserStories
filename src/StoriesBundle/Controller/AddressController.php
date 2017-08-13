<?php

namespace StoriesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use StoriesBundle\Entity\User\Address;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class AddressController extends Controller
{
    /**
     * @Route("/{userId}/addAddress")
     */
    public function addAddressAction(Request $request, $userId)
    {
        $form = $this->createFormMethod($userId);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return $this->render('StoriesBundle:Address:add_address.html.twig', array(
                'form'=>$form->createView()));
        }
        else {
            $this->saveToDB($form, $userId);
            return $this->redirectToRoute('stories_user_showuser', array('id' => $userId));
        }
    }

    /**
     * @Route("/{id}/editAddress")
     */
    public function editAddressAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getRepository('StoriesBundle:User\Address');
        $address = $repository->findOneById($id);
        $form = $this->createFormMethodToEdit($address, $id);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return $this->render('StoriesBundle:Address:add_address.html.twig', array(
                'form'=>$form->createView()));
        }
        else {
            $this->saveToDB($form);
            return new Response('Address updated');
        }
    }

    /**
     * @Route("/{id}/deleteAddress")
     */
    public function deleteAddressAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('StoriesBundle:User\Address');
        $address = $repository->findOneById($id);
        $em = $this->getDoctrine()->getManager();
        if (!$address) {
            return new Response('Address doesnt exist');
        }
        $em->remove($address);
        $em->flush();
        return new Response('Address deleted');
    }

    /**
     * @Route("{userId}/allAddressesByUser")
     */
    public function allAddressesByUserAction($userId)
    {
        $repository = $this->getDoctrine()->getRepository('StoriesBundle:User\Address');
        $addresses = $repository->findAllByUserId($userId);
        return $addresses;
    }

    public function createFormMethod($userId, $address = null) {
        if ($address == null) {
            $address = new Address();
        }
        $form = $this->createFormBuilder($address)
            ->setAction($this->generateUrl('stories_address_addaddress', array("userId"=>$userId)))
            ->setMethod('POST')
            ->add('street', 'text')
            ->add('streetno', 'text')
            ->add('localno', 'text')
            ->add('postcode', 'text')
            ->add('city', 'text')
            ->add('save', 'submit', array('label'=>'Add address'))
            ->getForm();
        return $form;
    }
    
    public function createFormMethodToEdit($address = null, $id) {
        if ($address == null) {
            $address = new Address();
        }
        $form = $this->createFormBuilder($address)
            ->setAction($this->generateUrl('stories_address_editaddress', array("id" => $id)))
            ->setMethod('POST')
            ->add('street', 'text')
            ->add('streetno', 'text')
            ->add('localno', 'text')
            ->add('postcode', 'text')
            ->add('city', 'text')
            ->add('save', 'submit', array('label'=>'Add address'))
            ->getForm();
        return $form;
    }

    public function saveToDB($form, $userId) {
        $address = $form->getData();
        $repository = $this->getDoctrine()->getRepository('StoriesBundle:User\User');
        $user = $repository->findOneById($userId);
        $address->setUser($user);
        $em = $this->getDoctrine()->getManager();
        $em->persist($address);
        $em->flush();
    }
}
