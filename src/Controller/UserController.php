<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    private $doctrine;
    public function __construct( EntityManagerInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }
    /**
     * @Route("/user", name="user")
     */
    public function user(): Response
    {
        $users = $this->doctrine->getRepository(User::class)->findAll();
        return $this->render('user/user.html.twig', [
            'users' => $users,
        ]);
    }
}
