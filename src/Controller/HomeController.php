<?php
/**
 * Created by PhpStorm.
 * User: Naim Arshad
 * Date: 20/03/2019
 * Time: 22:28
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Riddles;

class HomeController extends AbstractController
{
    public function index()
    {
        return $this->render("home.html.twig");
    }

    public function allRiddles()
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_USER');
        } catch(AccessDeniedException $e) {
            return new RedirectResponse('/');
        }
        $riddles = $this->getDoctrine()->getRepository(Riddles::class)->findAll();
        return $this->render("riddles/riddles.html.twig", ['riddles' => $riddles]);
    }

    public function myRiddles()
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_USER');
        } catch(AccessDeniedException $e) {
            return new RedirectResponse('/');
        }
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $riddles =  $this->getDoctrine()->getRepository(Riddles::class)->findBy([
            'id_user' => $user
        ]);
        return $this->render("riddles/riddles.html.twig", ['riddles' => $riddles]);
    }

    public function newRiddles(Request $request)
    {
        $notification = '';

        try {
            $this->denyAccessUnlessGranted('ROLE_USER');
        } catch(AccessDeniedException $e) {
            return new RedirectResponse('/');
        }
        if(!empty($request->request->get('submit'))) {
            if(empty($request->request->get('title')) && empty($request->request->get('subject')) &&
               empty($request->request->get('answer')))
                $notification = 'Please complete the form';
            elseif(empty($request->request->get('title')))
                $notification = 'Please put a title';
            elseif(empty($request->request->get('subject')))
                $notification = 'Please put a subject';
            elseif(empty($request->request->get('answer')))
                $notification = 'Please put an answer';
            else {
                $entityManager = $this->getDoctrine()->getManager();
                $newRid = new Riddles();
                $user = $this->get('security.token_storage')->getToken()->getUser();
                $newRid->setTitle($request->request->get('title'));
                $newRid->setSubject($request->request->get('subject'));
                $newRid->setAnswer($request->request->get('answer'));
                $newRid->setIdUser($user->getId());

                $entityManager->persist($newRid);
                $entityManager->flush();
                $this->redirectToRoute('myRiddles', [], 302);
            }
        }
        return $this->render('riddles/new.riddle.html.twig', ['notification' => $notification]);
    }

    public function riddle($id, Request $request)
    {
        $notification = '';

        $riddle = $this->getDoctrine()->getRepository(Riddles::class)
            ->find($id);
        if(!$riddle) {
            return new RedirectResponse('/');
        }
        if(!empty($request->request->get('submit'))) {
            if(empty($request->request->get('answer'))) {
                $notification = "Please put an answer";
            } else {
                $riddleAnswer = $this->getDoctrine()->getRepository(Riddles::class)->find($id)->getAnswer();
                if($request->request->get('answer') == $riddleAnswer) {
                    $notification = 'Good answer';
                } else {
                    $notification = 'Wrong answer';
                }
            }
        }
        return $this->render('riddles/riddle.html.twig', ['riddle' => $riddle, 'notification' => $notification]) ;
    }
}