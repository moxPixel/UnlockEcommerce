<?php

namespace App\Controller;


use App\Entity\User;
use App\Entity\Product;
use App\Entity\Category;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    private $doctrine;
    public function __construct(EntityManagerInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }
    /**
     * @Route("/admin/dashboard", name="dashboard")
     */
    public function dashboard(UserRepository $userRepository): Response
    {
     
        $stats = [
            "users" => $this->doctrine->getRepository(User::class)->findAll(),
            "products" => $this->doctrine->getRepository(Product::class)->findAll(),
            "categories" => $this->doctrine->getRepository(Category::class)->findAll(),
            "userByMonth" => $userRepository->findUsersForMonth()
        ];


        return $this->render('dashboard/dashboard.html.twig', [
            'stats' => $stats,

        ]);
    }
}
