<?php

namespace App\Controller;


use App\Entity\User;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Category;
use App\Repository\UserRepository;
use App\Repository\OrderRepository;
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
    public function dashboard(UserRepository $userRepository,OrderRepository $orderRepository): Response
    {
     
        $stats = [
            "users" => $this->doctrine->getRepository(User::class)->findAll(),
            "products" => $this->doctrine->getRepository(Product::class)->findAll(),
            "categories" => $this->doctrine->getRepository(Category::class)->findAll(),
            "orders" => $this->doctrine->getRepository(Order::class)->findBy(['isPaid' => '1']),
            "userByMonth" => $userRepository->findUsersForMonth(),
            "orderByMonth" => $orderRepository-> findOrderByMonth()
            
        ];
      
        return $this->render('dashboard/dashboard.html.twig', [
            'stats' => $stats,

        ]);
    }
}
