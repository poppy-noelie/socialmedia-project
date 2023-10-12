<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\SearchBarType;
use App\Repository\LikeRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class HomeController extends AbstractController
{
    //RENDER HOME PAGE
    #[Route('/home/{targetUser}', name: 'app_home')]
    public function index(User $targetUser, UserRepository $userRepository, PostRepository $postRepository, LikeRepository $likeRepository, Request $request): Response
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


        $userFollowingsPosts = [];
        foreach($followings as $follow) {
            $userFollowingsPosts[] = $postRepository->getLastsFollowingUserPost($follow->getId());
        }


        $userFollowingsPostsFlattened = array_reduce($userFollowingsPosts, function($carry, $item) {
            return array_merge($carry, $item);
        }, []);


            //get comment form on every posts
            $comment = new Comment();
            $forms = [];
            foreach ($userFollowingsPostsFlattened as $post) {
                $form = $this->createForm(CommentType::class, $comment, [
                    'action' => $this->generateUrl('app_post_comment', [
                        'post' => $post->getId(),
                        'targetUser' => $targetUser->getId(),
                    ]),
                ]);
                $forms[$post->getId()] = $form->createView();
            }


        $isLiked= [];
        foreach($userFollowingsPostsFlattened as $post) {
            $isLiked[$post->getId()] = $likeRepository->isLiked($this->getUser()->getId(), $post->getId());
        }


        //SEARCHBAR
        $searchbar = $this->createForm(SearchBarType::class);
        $searchbar->handleRequest($request);

        if($searchbar->isSubmitted() && $searchbar->isValid()) {
            $searchBarParameters = $request->get('search_bar');

            if (isset($searchBarParameters['content'])) {
                $query = $searchBarParameters['content'];
            } else {
                $query = null;
            }


            if(empty($userRepository->searchBy($query))) {
                $results = 'No results';
            } else {
                $results = $userRepository->searchBy($query);
            }

            return $this->render('home/search-results.html.twig', [
                'results' => $results
            ]);
        }



        return $this->render('home/index.html.twig', [
            'followings' => $followings,
            'targetUser' => $targetUser,
            'forms' => $forms,
            'posts' => $userFollowingsPostsFlattened,
            'isLiked' => $isLiked,
            'searchbar' => $searchbar,

        ]);
    }

}
