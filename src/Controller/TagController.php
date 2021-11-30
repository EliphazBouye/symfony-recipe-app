<?php

namespace App\Controller;

use App\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    #[Route('/tag', name: 'tag')]
    public function index(): Response
    {
        return $this->render('tag/index.html.twig', [
            'controller_name' => 'TagController',
        ]);
    }

    #[Route('/tag/new', name: 'new_tag')]
    public function new(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $tag = new Tag();

        $tag->setName('Grillade');

        $entityManager->persist($tag);
        $entityManager->flush();

        return new Response('Tag added  id : '.$tag->getId());
    }
}