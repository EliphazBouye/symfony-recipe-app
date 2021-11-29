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

        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes
        ]);
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

        return new Response('Check out this great recipe: '.$recipe->getName());
    }


    public function update(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $recipe = $entityManager->getRepository(Recipe::class)->find($id);

        if(!$recipe){
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $recipe->setName('Sauce arrachide');
        $entityManager->flush();

        return new Response('Recipe updated id : '.$recipe->getId());

    }

    public function delete(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $recipe = $entityManager->getRepository(Recipe::class)->find($id);

        if(!$recipe){
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $entityManager->remove($recipe);
        $entityManager->flush();

        return new Response('Recipe deleted');
    }
}