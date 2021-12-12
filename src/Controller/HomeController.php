<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    private $doctrine;
    public function __construct( EntityManagerInterface $doctrine)
    {
        $this->doctrine = $doctrine;
        

    }
    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        $productForDev = $this->doctrine->getRepository(Product::class)->findBy(['category' => '1']);
        $productForForm = $this->doctrine->getRepository(Product::class)->findBy(['category' => '2']);
        return $this->render('home/home.html.twig', [
            'dev' => $productForDev,
            'form' => $productForForm,
        ]);
    }
}
