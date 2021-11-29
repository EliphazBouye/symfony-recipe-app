<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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


     /**
     * @Route("/recipe/new", methods={"GET","POST"})
     */
    public function createRecipe(Request $request): Response
    {

        $recipe = new Recipe();
        // $recipe->setName('Sauce graine');
        // $recipe->setContent('Graine de palm, Viande , Poison fumer etc...');
        

        $form = $this->createForm(RecipeType::class);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $recipe = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($recipe);
            $entityManager->flush();

            return $this->redirectToRoute('list_recipe');
        }

        return $this->renderForm('recipe/new.html.twig', [
            'form' => $form
        ]);
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

        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe
        ]);
    }


    public function update(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $recipe = $entityManager->getRepository(Recipe::class)->find($id);


        $form = $this->createForm(RecipeType::class, $recipe);

        if(!$recipe){
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $recipe = $form->getData();
            $entityManager->flush();
            
            return $this->redirectToRoute('show_recipe', ['id' => $id]);
        }


        return $this->renderForm('recipe/edit.html.twig', [
            'form' => $form
        ]);

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

        $this->addFlash(
            'notice',
            "Recipe Deleted successfully"
        );

        return $this->redirectToRoute('list_recipe');
    }
}