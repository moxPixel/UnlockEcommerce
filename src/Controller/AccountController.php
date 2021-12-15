<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OrderDetailsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{

    private $doctrine;
    public function __construct(EntityManagerInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }
    /**
     * @Route("/account", name="account")
     */
    public function account(OrderDetailsRepository $orderRepository): Response
    {
        $ordersValid = $orderRepository->findByOrderIsPaidForUser(1,$this->getUser());

        return $this->render('account/account.html.twig', [
            'ordersValid' => $ordersValid,
        ]);
    }
}
