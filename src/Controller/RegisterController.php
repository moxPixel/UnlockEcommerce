<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Mailjet;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use App\Notification\NotificationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class RegisterController extends AbstractController
{
    private $passwordEncoder;
    private $doctrine;
    private $mailjet;
    public function __construct(UserPasswordHasherInterface $passwordEncoder, EntityManagerInterface $doctrine, Mailjet $mailjet)
    {
        $this->mailjet = $mailjet;
        $this->doctrine = $doctrine;
        $this->passwordEncoder = $passwordEncoder;

    }
    /**
     * Enregitrement d'un utilisateur
     * @Route("/register", name="register")
     * @param NotificationService $notificationService
     * @param User $user
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function register(Request $request): Response
    {
     
        $temporaryPassword = $this->passwordAleatory(10);
        $user = new User();
    

        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setCreatedAt(new \DateTime());
            $user->setPassword(
                $this->passwordEncoder->hashPassword($user, $user->getPassword())
            );
            $em = $this->doctrine;
            $em->persist($user);
            $em->flush();

            // $this->notificationService->sendNotification("Félicitation {$user->getFirstname()} ! Vous faite desormait partit de l'aventure.", $user);
             $this->mailjet->sendEmail($user, "Bienvenue chez Unlock technologies, nous venons de vous créer un espace dedier. Vous y'trouvera votre tableau de bord personalisé !");

            $this->addFlash('success', 'Inscription reussi ! vous allez recevoir un email de confirmation.');
        }

        return $this->render('register/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    function passwordAleatory($nbChar)
    {
        $chaine = "mnoTUzS5678kVvwxy9WXYZRNCDEFrslq41GtuaHIJKpOPQA23LcdefghiBMbj0";
        srand((float)microtime() * 1000000);
        $pass = '';
        for ($i = 0; $i < $nbChar; $i++) {
            $pass .= $chaine[rand() % strlen($chaine)];
        }
        return $pass;
    }

    function passgen2($nbChar)
    {
        return substr(str_shuffle(
            'abcdefghijklmnopqrstuvwxyzABCEFGHIJKLMNOPQRSTUVWXYZ0123456789'
        ), 1, $nbChar);
    }
}
