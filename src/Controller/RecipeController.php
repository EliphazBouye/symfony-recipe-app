<?php

namespace App\Controller;

use App\Entity\Recipe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController
{
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

}