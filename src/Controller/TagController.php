<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    #[Route('/tags', name: 'tags')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $tags = $entityManager->getRepository(Tag::class)->findAll();
        return $this->render('tag/index.html.twig', [
            'tags' => $tags,
        ]);
    }

    #[Route('/tags/new', name: 'new_tag')]
    public function new(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $tag = new Tag();

        $form = $this->createForm(TagType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $tag = $form->getData();

            $entityManager->persist($tag);
            $entityManager->flush();

            return $this->redirectToRoute('tags');
        }

        return $this->renderForm('tag/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('tags/show/{id}', name:'show_tag')]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        $tag = $entityManager->getRepository(Tag::class)->find($id);

        $tagRecipes = $tag->getRecipes();
        
        return $this->render('tag/show.html.twig', [
            'tag' => $tag,
            'recipes' => $tagRecipes
        ]);
    }

    /**
     * @Route("/tags/delete/{id}", methods={"GET","POST"}, name="delete_tag")
     */
    public function delete(int $id,Request $request, EntityManagerInterface $entityManager): Response
    {
        $tag = $entityManager->getRepository(Tag::class)->find($id);
        if(!$tag){
            throw $this->createNotFoundException(
                'Id invalid'
            );
        }
        $entityManager->remove($tag);
        $entityManager->flush();

        return $this->redirectToRoute('tags');
    }


}