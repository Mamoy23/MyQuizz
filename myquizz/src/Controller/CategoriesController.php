<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Categorie;

class CategoriesController extends AbstractController
{
    /**
     * @Route("/categories", name="categorie")
     */
    public function index()
    {
        $categories = $this->getDoctrine()
                            ->getRepository(Categorie::class)
                            ->findAll();

        return $this->render('categories/index.html.twig', [
            'controller_name' => 'CategoriesController', 'categories' => $categories
        ]);
    }

    /**
     * @Route("/categories/{id}", name="categories_show")
     */
    public function show($id)
    {
        $categorie = $this->getDoctrine()
            ->getRepository(Categorie::class)
            ->find($id);

        if (!$categorie) {
            throw $this->createNotFoundException(
                'No catgeorie found for id '.$id
            );
        }
        
        $quizzes = $categorie->getQuizzes();
        //return new Response('Check out this great product: '.$categorie->getName());

        // or render a template
        // in the template, print things with {{ product.name }}
        return $this->render('categories/show.html.twig', ['categorie' => $categorie, 'quizzes' => $quizzes]);
    }
}
