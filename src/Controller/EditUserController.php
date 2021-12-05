<?php

namespace App\Controller;

use App\Form\EditUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EditUserController extends AbstractController
{

    private $passwordEncoder; //declaration de variable 
    private $doctrine;
    public function __construct(UserPasswordHasherInterface $passwordEncoder, EntityManagerInterface $doctrine) // le contructeur permet de construir une variable et de l associer a une classe permettant de l utiliser plus bas
    {
        $this->passwordEncoder = $passwordEncoder; // simplification et assigniation de la variable 
        $this->doctrine = $doctrine;
    }

    /**
     * @Route("/edit/user", name="edit_user")
     */
    public function editUser(Request $request, SluggerInterface $slugger): Response
    {

        $user = $this->getUser();
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $avatarFile = $form->get('picture')->getData();


            // this condition is needed because the 'avatar' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($avatarFile) {
                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $avatarFile->guessExtension();

                // Move the file to the directory where avatars are stored
                try {
                    $avatarFile->move(
                        $this->getParameter('avatar'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'avatarFilename' property to store the PDF file name
                // instead of its contents
                $user->setPicture($newFilename);
            }
            $new_pwd = $form->get('password')->getData();


            $em = $this->doctrine;
            $user->setPassword(
                $this->passwordEncoder->hashPassword($user, $new_pwd)
            );

            $em->flush();
            $this->addFlash('success', 'Votre mot de passe a bien etait modifier !');
        }
        return $this->render('edit_user/editUser.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
