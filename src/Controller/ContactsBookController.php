<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\ContactsBook;
use App\Form\ContactsFormType;
use Symfony\Component\Security\Core\User\User;
use App\Entity\Contacts;

class ContactsBookController extends AbstractController
{
    /**
     * @Route("/contacts", name="contacts")
     */
    public function index()
    {
        return $this->render('contacts_book/index.html.twig', [
            'controller_name' => 'ContactsBookController',
        ]);
    }
    /**
     * @Route("/create-contact", name="create-contact")
     */
    public function createContact(Request $request)
    {
        $contact = new ContactsBook();
        $form = $this->createForm(ContactsFormType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $user = $this->getUser();
            $userId = $user->getId();
            $contact->setUserId($userId);
            $contact = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();
            return $this->redirect('/index.php');
        }
        return $this->render(
            'edit.html.twig',
            array('form' => $form->createView())
        );
    }
    /**
     * @Route("/view-contact/{id}", name="view-contact")
     */
    public function viewContacts($id)
    {
        $contact = $this->getDoctrine()
            ->getRepository('App\Entity\ContactsBook')
            ->find($id);
        if (!$contact) {
            throw $this->createNotFoundException(
                'There are no contacts with the following id: ' . $id
            );
        }
        return $this->render(
            'view.html.twig',
            array('contact' => $contact)
        );
    }
    /**
     * @Route("/", name="index")
     */
    public function showContacts()
    {
        $userId = $this->getUser()->getId();
        $contacts = $this->getDoctrine()
            ->getRepository('App\Entity\ContactsBook')
            ->findBy(array('user_id' => $userId),  array('name' => 'ASC'));
        $shContacts = $this->getDoctrine()
            ->getRepository('App\Entity\Contacts') 
            ->findBy(array('user_id' => $userId));
        if(!empty($shContacts))
        {
            $contactIds = array();
            foreach ($shContacts as $contact)
            {
                $contactId = $contact->getContactId();
                $contactIds[] = $contactId;
            }
            $sharedContacts = $this->getDoctrine()
            ->getRepository('App\Entity\ContactsBook')
            ->findBy(array('id' => $contactIds), array('name' => 'ASC'));
            return $this->render(
                'show.html.twig',
                array('contacts' => $contacts, 'sharedContacts' => $sharedContacts)
            );
        }else{
            return $this->render(
                'show.html.twig',
                array('contacts' => $contacts, 'sharedContacts' => '')
            );
        }
    }
    /**
     * @Route("/delete-contact/{id}", name="delete-contact")
     */
    public function deleteContact($id)
    {
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository('App\Entity\ContactsBook')->find($id);
        if (!$contact) {
            throw $this->createNotFoundException(
                'There are no contacts with the following id: ' . $id
            );
        }
        $em->remove($contact);
        $em->flush();
        return $this->redirect('/index.php');
    }
    /**
     * @Route("/edit-contact/{id}", name="edit-contact")
     */
    public function updateContact(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository('App\Entity\ContactsBook')->find($id);
        if (!$contact) {
            throw $this->createNotFoundException(
                'There are no contacts with the following id: ' . $id
            );
        }
        $form = $this->createForm(ContactsFormType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $contact = $form->getData();
            $em->flush();
            return $this->redirect('/index.php');
        }
        return $this->render(
            'edit.html.twig',
            array('form' => $form->createView())
        );
    }
    /**
     * @Route("/share-contact/{id}", name="share-contact")
     */
    public function shareContact($id)
    {
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository('App\Entity\ContactsBook')->find($id);
        $userId = $this->getUser()->getId();
        $users = $em->getRepository('App\Entity\User')->findAllExceptThis($userId);
        return $this->render(
            'share.html.twig',
            array('contact' => $contact, 'users' => $users));
    }
    /**
     * @Route("/begin-share/{id}", name="begin-share")
     */
    public function createShare(Request $request, $id)
    {
        $relation = new Contacts();
        $relation->setContactId($id);
        $user = $this->getUser();
        $userId = $user->getId();
        $relation->setOwnerId($userId);
        $owner_id = $request->get('owner');
        $relation->setUserId($owner_id);
        $em = $this->getDoctrine()->getManager();
        $em->persist($relation);
        $em->flush();
        return $this->redirect('/sharing');
    }
    /**
     * @Route("/sharing", name="sharing")
     */
    public function sharing()
    {
        $userId = $this->getUser()->getId();
        $contacts = $this->getDoctrine()
            ->getRepository('App\Entity\Contacts')
            ->findBy(array('owner_id' => $userId));
        $contactIds = array();
        foreach ($contacts as $contact)
        {
            $contactId = $contact->getContactId();
            $contactIds[] = $contactId;
        }
        $sharedContacts = $this->getDoctrine()
            ->getRepository('App\Entity\ContactsBook')
            ->findBy(array('id' => $contactIds), array('name' => 'ASC'));
        return $this->render(
            'sharing.html.twig',
            array('sharedContacts' => $sharedContacts));
    }
    /**
     * @Route("/terminate-share/{id}", name="terminate-share")
     */
    public function terminateShare($id)
    {
        $em = $this->getDoctrine()->getManager();
        $sharedContact = $em->getRepository('App\Entity\Contacts')
        ->findOneBy(array('contact_id' => $id));
        $em->remove($sharedContact);
        $em->flush();
        return $this->redirect('/sharing');
    }
}

