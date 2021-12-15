<?php

namespace App\Controller;

use App\Dto\Cart;
use App\Entity\Order;
use App\Form\CartValidType;
use App\Entity\OrderDetails;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/my-cart", name="cart")
     */
    public function myCart(Cart $cart, Request $request): Response
    {
        $form = $this->createForm(CartValidType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


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
        return $this->render('cart/myCart.html.twig', [
            'cart' => $cart->getFullCart(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/my-cart/add/{id}", name="add_to_cart")
     */
    public function addCart($id, Cart $cart): Response
    {
        $cart->add($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/my-cart/remove", name="remove_my_cart")
     */
    public function removeCart(Cart $cart): Response
    {
        $cart->remove();
        return $this->redirectToRoute('home');
    }


    /**
     * @Route("/my-cart/delete/{id}", name="delete_to_cart")
     */
    public function deleteToCart(Cart $cart, $id): Response
    {
        $cart->delete($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/my-cart/decrease/{id}", name="decrease_to_cart")
     */
    public function decreaseToCart(Cart $cart, $id): Response
    {
        $cart->decrease($id);
        return $this->redirectToRoute('cart');
    }
}
