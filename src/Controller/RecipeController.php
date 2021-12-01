<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Tag;
use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

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
     * @Route("/recipe/new", methods={"GET","POST"}, name="create_recipe")
     */
    public function createRecipe(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader, SluggerInterface $slugger): Response
    {
        $recipe = new Recipe();
        
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
           $recipe = $form->getData();
           $image = $form->get('image')->getData();
            if(isset($image)){
                $newImageName = $fileUploader->upload($image);
            

            $img = new Image();
            $img->setName($newImageName);
            $recipe->setImage($img);
            }
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

        $tags = $recipe->getTags();

        if(!$recipe) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
            'tags' => $tags
        ]);
    }


    public function update(Request $request, int $id, FileUploader $fileUploader): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $recipe = $entityManager->getRepository(Recipe::class)->find($id);

        $imageRecipe = null;

        if($recipe->getImage() != null){
            $imageRecipe = $recipe->getImage();
        }

        $form = $this->createForm(RecipeType::class, $recipe);

        if(!$recipe){
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $recipe = $form->getData();
            $image = $form->get('image')->getData();
            
            if(isset($image)){
                $newImageName = $fileUploader->upload($image);

                $img = new Image();
                // dump($img);
                $img->setName($newImageName);
                $recipe->setImage($img);
                //     $newImageName
                // );
            }

            $entityManager->flush();
            
            return $this->redirectToRoute('show_recipe', ['id' => $id]);
        }


        return $this->renderForm('recipe/edit.html.twig', [
            'form' => $form,
            'imageRecipe' => $imageRecipe
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