<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Following;
use App\Entity\User;
use App\Form\CommentType;
use App\Repository\FollowingRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    //RENDER USER PROFILE
    #[Route('/user/{targetUser}', name: 'app_user_profile')]
    public function index(User $targetUser, UserRepository $userRepository, PostRepository $postRepository, FollowingRepository $followingRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_welcome');
        }

        //get target user posts
        $posts = $postRepository->findBy(['user' => $targetUser->getId()]);

        //get target user followers
        $followers = $userRepository->getFollowers($targetUser->getId());

        //get target user followings
        $followingIds = $userRepository->getFollowings($targetUser->getId());

        foreach($followingIds as $followingId) {
            $userId = $followingId[1];
            $followingIds[] = $userId;
        }

        $followings = $userRepository->findBy(['id' => $followingIds]);

        //check if the user is already following the target user or not
        $isFollowing = $followingRepository->isFollowing($this->getUser()->getId(), $targetUser->getId());

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

//        dd($forms);

//        dd($posts);

        return $this->render('user/index.html.twig', [
            'posts' => $posts,
            'targetUser' => $targetUser,
            'followers' => $followers,
            'followings' => $followings,
            'isFollowing' => $isFollowing,
            'forms' => $forms,
        ]);
    }


    #[Route('/user/follow/{targetUser}', name: 'app_follow')]
    public function follow(User $targetUser, FollowingRepository $followingRepository): Response
    {
        //check if the user is trying to follow themselves
        if($targetUser->getId() !== $this->getUser()->getId()) {

            $isFollowing = $followingRepository->isFollowing($this->getUser()->getId(), $targetUser->getId());
            //$this->service->persist($isFollowing)

            //check if the user is already following the target_user
            if(!$isFollowing) {

                $follow = new Following();
                $follow->setUser($this->getUser());
                $follow->setTargetUser($targetUser);
                $follow->setCreatedAt(new \DateTimeImmutable('now'));

                $this->em->persist($follow);
                $this->em->flush();
            }
        }

        return $this->redirectToRoute('app_user_profile', ['targetUser' => $targetUser->getId()]);

    }

    #[Route('/user/unfollow/{targetUser}', name: 'app_unfollow')]
    public function unfollow(User $targetUser, FollowingRepository $followingRepository): Response
    {

        $follow = $followingRepository->findOneBy(['user' => $this->getUser()->getId(), 'target_user' => $targetUser->getId()]);
        $isFollowing = $followingRepository->isFollowing($this->getUser()->getId(), $targetUser->getId());


        if($isFollowing) {
            $this->em->remove($follow);
            $this->em->flush();
        }

        return $this->redirectToRoute('app_user_profile', ['targetUser' => $targetUser->getId()]);

    }


    #[Route('/user/followers/{targetUser}', name: 'app_followers')]
    public function followers(User $targetUser, UserRepository $userRepository): Response
    {
        //get target user followers
        $followers = $userRepository->getFollowers($targetUser->getId());

        return $this->render('user/followers.html.twig', [
            'followers' => $followers,
            'targetUser' => $targetUser,
        ]);
    }


    #[Route('/user/followings/{targetUser}', name: 'app_followings')]
    public function followings(User $targetUser, UserRepository $userRepository, PostRepository $postRepository): Response
    {

        //get target user followings
        $followingIds = $userRepository->getFollowings($targetUser->getId());

        foreach($followingIds as $followingId) {
            $userId = $followingId[1];
            $followingIds[] = $userId;
        }

//        $followings = $userRepository->findBy(['id' => $followingIds]);
        $posts = $postRepository->findBy(['user' => $targetUser->getId()]);
//        dd($posts);

        return $this->render('user/followings.html.twig', [
//            'followings' => $followings,
            'targetUser' => $targetUser,
        ]);
    }
}
