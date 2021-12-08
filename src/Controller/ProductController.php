<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    private $doctrine;
    public function __construct( EntityManagerInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }
    /**
     * @Route("/product", name="product")
     */
    public function product(Request $request): Response
    {
        $products = $this->doctrine->getRepository(Product::class)->findAll();
        $product = new Product();
    

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          
           
            $em = $this->doctrine;
            $em->persist($product);
            $em->flush();

  
            $this->addFlash('success', 'Nouvelle formule ajouter.');
        }

        return $this->render('product/product.html.twig', [
            'form' => $form->createView(),
            'products' => $products
        ]);
    }
}
