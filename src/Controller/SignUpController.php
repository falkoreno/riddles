<?php
/**
 * Created by PhpStorm.
 * User: Naim Arshad
 * Date: 20/03/2019
 * Time: 23:22
 */

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;

class SignUpController extends AbstractController
{
    public function signUp(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $notification = '';

        if(!empty($request->request->get('signup')))
        {

            $username = $request->request->get('username');
            $password = $request->request->get('password');

            if(empty($username) && empty($password))
                $notification = 'Please put a username and password';
            else if(empty($username))
                $notification = 'Please put a username';
            else if(empty($password))
                $notification = 'Please put a password';
            else {
                $entityManager = $this->getDoctrine()->getManager();
                $user = new User();
                $user->setRoles(['ROLE_USER']);
                $user->setUsername($username);
                $user->setPassword($encoder->encodePassword($user, $password));

                $entityManager->persist($user);
                $entityManager->flush();
            }
        }
        return $this->render("signup.html.twig", ["notification" => $notification]);
    }
}