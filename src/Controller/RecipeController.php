<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController
{
    public function listRecipe(RecipeRepository $recipeRepository): Response
    {
        $recipes = $recipeRepository->findAll();

        return new Response(
            dump($recipes)
        );
    }

    public function createRecipe(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $recipe = new Recipe();
        $recipe->setName('Sauce graine');
        $recipe->setContent('Graine de palm, Viande , Poison fumer etc...');
        

        $entityManager->persist($recipe);
        $entityManager->flush();

        return new Response('Saved recipe with id '.$recipe->getId());
    }

    public function show(int $id): Response
    {
        $recipe = $this->getDoctrine()
            ->getRepository(Recipe::class)
            ->find($id);


        if(!$recipe) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return new Response('Check out this great recipe: '.$recipe->getContent());


    }

}