<?php

namespace App\Controller;

use App\Dto\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/order", name="order")
     */
    public function order(Cart $cart): Response
    {

     
        if ($cart->getFullCart()) {
          
            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt(new \DateTime());
            $order->setIsPaid(0);

            foreach ($cart->getFullCart() as $item) {
                // dd($item);
                $order->setCategoryName($item['product']->getCategory()->getName());
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($item['product']->getName());
                $orderDetails->setQuantity($item['quantity']);
                $orderDetails->setPrice($item['product']->getPrice());
                $orderDetails->setTotal($item['product']->getPrice() * $item['quantity']);
                $this->entityManager->persist($order);
                $this->entityManager->persist($orderDetails);
                $this->entityManager->flush();
            }
          
            return $this->render('order/order.html.twig', [
                'cart' => $cart->getFullCart(),
          
            ]);
        } else {
            return $this->redirectToRoute('cart');
        }
    
    }
}
