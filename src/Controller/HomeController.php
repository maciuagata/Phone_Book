<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/", name="home.")
 */

class HomeController extends AbstractController
{
     /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $userId = $this->getUser()->getId();
        $contacts = $this->getDoctrine()
            ->getRepository('App\Entity\ContactsBook')
            ->findBy(array('user_id' => $userId));
        return $this->render(
            'show.html.twig',
            array('contacts' => $contacts)
        );
    }
}