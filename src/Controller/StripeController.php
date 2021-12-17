<?php

namespace App\Controller;

use App\Dto\Cart;
use Stripe\Stripe;
use App\Entity\Order;
use App\Entity\OrderDetails;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{

    private $stripe_public_key;
    private $stripe_secret_ke;

    public function __construct($stripe_public_key, $stripe_secret_key,EntityManagerInterface $entityManager)
    {
        $this->stripe_public_key = $stripe_public_key;
        $this->stripe_secret_key = $stripe_secret_key;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/checkout", name="checkout")
     */
    public function stripe(Cart $cart): Response
    {
        
        $products_for_Stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        foreach ($cart->getFullCart() as $item) {
            $products_for_Stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $item['product']->getPrice() * 100,
                    'product_data' => [
                        'name' => $item['product']->getName(),
                        'description' => $item['product']->getCategory()->getName(),
                    ],

                ],
                'quantity' => $item['quantity'],
            ];
        }

        Stripe::setApiKey($this->stripe_secret_key);


        $checkout_session = Session::create([

            'payment_method_types' => ['card'],

            'line_items' => [$products_for_Stripe],

            'mode' => 'payment',

            'success_url' => $YOUR_DOMAIN . '/success.html',

            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',

        ]);

        if($this->getUser()){
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

          
            return $this->redirect($checkout_session->url, 303);
        }else{
            return $this->redirectToRoute('app_login');
        }

    }
}}
