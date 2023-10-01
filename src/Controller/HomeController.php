<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use App\Form\CommentType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    //RENDER HOME PAGE
    #[Route('/home/{targetUser}', name: 'app_home')]
    public function index(User $targetUser, UserRepository $userRepository, PostRepository $postRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_welcome');
        }

        //get (logged) user followings
        $followingIds = $userRepository->getFollowings($this->getUser()->getId());

        $test = [];

        foreach($followingIds as $followingId) {
            $userId = $followingId[1];
            $test[] = $userId;
        }

        $followings = $userRepository->findBy(['id' => $test]);

        //get target user posts
        $posts = $postRepository->findBy(['user' => $targetUser->getId()]);

            //get comment form on every posts
            $comment = new Comment();
            $forms = [];
            foreach ($posts as $post) {
                $form = $this->createForm(CommentType::class, $comment, [
                    'action' => $this->generateUrl('app_post_comment', [
                        'post' => $post->getId(),
                        'targetUser' => $targetUser->getId(),
                    ]),
                ]);
                $forms[$post->getId()] = $form->createView();
            }

//            dd($forms);


        return $this->render('home/index.html.twig', [
            'followings' => $followings,
            'targetUser' => $targetUser,
            'forms' => $forms
        ]);
    }
}
