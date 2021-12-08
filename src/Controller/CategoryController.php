<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    private $doctrine;
    public function __construct( EntityManagerInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }
    /**
     * @Route("/category", name="category")
     */
    public function category(Request $request): Response
    {
        $categories = $this->doctrine->getRepository(Category::class)->findAll();
        $category = new Category();
    

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          
           $category->setCreatedAt(new \DateTime());
            $em = $this->doctrine;
            $em->persist($category);
            $em->flush();

  
            $this->addFlash('success', 'Nouvelle categorie ajouter.');
        }
        return $this->render('category/category.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories
        ]);
    }
}
