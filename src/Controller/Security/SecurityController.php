<?php

namespace App\Controller\Security;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        $error = $authenticationUtils->getLastAuthenticationError();
        // get the login error if there is one

//        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(array('email' => $token));

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/emailConfirmation/{token}", name="email_confirmation")
     */
    public function emailConfirmation(string $token)
    {

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(array('verify_token' => $token));
        $user->setStatus(true);
        $user->setVerifyToken(NULL);

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $this->addFlash('success', 'Регистрация успешно завершена!');

        return $this->render('security/confrmationEnd.html.twig');
    }


    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
