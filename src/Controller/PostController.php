<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Like;
use App\Entity\Post;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\PostType;
use App\Repository\CommentRepository;
use App\Repository\LikeRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class PostController extends AbstractController
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }



    //RENDER NEW POST FORM
    #[Route('/post/new', name: 'app_post_new')]
    public function new(Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_welcome');
        }

        $post = new Post();
        $post->setUser($this->getUser());

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $post->setCreatedAt(new \DateTimeImmutable('now'));
            $this->em->persist($post);
            $this->em->flush();

            return $this->redirectToRoute('app_user_profile', ['targetUser' => $this->getUser()->getId()]);
        } else {
            $this->addFlash('error', 'erreur test');
        }


        return $this->render('post/new.html.twig', [
            'form' => $form,
        ]);
    }




    #[Route('/post/{post}/{targetUser}', name: 'app_post_show')]
    public function show(Post $post, User $targetUser): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_welcome');
        }

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

//        $isLiked = ($likeRepository->findOneBy(['user' => $this->getUser(), 'post' => $post]) !== null);

        return $this->render('post/index-single.html.twig', [
            'post' => $post,
            'targetUser' => $targetUser,
            'form' => $form->createView(),
//            'isLiked' => $isLiked,
        ]);
    }




    //HANDLE LIKES
    #[Route('/post/{post}/like/{targetUser}', name: 'app_post_like')]
    public function like(Post $post, User $targetUser, LikeRepository $likeRepository, Request $request): Response
    {
//        dd('test');
        $existingLike = $likeRepository->findOneBy(['user' => $this->getUser(), 'post' => $post]);

        if(!$existingLike) {
            $like = new Like();
            $like->setUser($this->getUser());
            $like->setPost($post);

            $post->addLike($like);

            $this->em->persist($like);
            $this->em->flush();
        }

        $route = $request->headers->get('referer');

        return $this->redirect($route);
    }


    #[Route('/post/{post}/unlike/{targetUser}', name: 'app_post_unlike')]
    public function unlike(Post $post, User $targetUser, LikeRepository $likeRepository, Request $request): Response
    {

        $existingLike = $likeRepository->findOneBy(['user' => $this->getUser(), 'post' => $post]);

        if($existingLike) {
            $this->em->remove($existingLike);
            $this->em->flush();
        }

        $route = $request->headers->get('referer');

        return $this->redirect($route);
    }


    #[Route('/comment/{post}/{targetUser}', name: 'app_post_comment', methods: ['GET', 'POST'])]
    public function Comment(Post $post, User $targetUser, Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_welcome');
        }

        $comment = new Comment();
        $comment->setUser($this->getUser());
        $comment->setPost($post);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setCreatedAt(new \DateTimeImmutable('now'));
            $this->em->persist($comment);
            $this->em->flush();

            $route = $request->headers->get('referer');

            return $this->redirect($route);        }

        return $this->render('post/comment.html.twig', [
            'form' => $form,
            'post' => $post,
        ]);
    }




    #[Route('/comments/get/{post}', name: 'app_get_post_comments', methods: ["GET"])]
    public function getPostComments (Post $post, CommentRepository $commentRepository, Request $request) : JsonResponse
    {
        $DBComments = $commentRepository->findBy(['post' => $post->getId()]);

        $comments = [];
        foreach($DBComments as $comment) {
            $comments[] = [
                'id' => $comment->getId(),
                'content' => $comment->getContent(),
                'createdAt' => $comment->getCreatedAt(),
                'user' => [
                    'id' => $comment->getUser()->getId(),
                    'username' => $comment->getUser()->getUsername(),
                ]
            ];
    }

        return $this->json( $comments, 200, [], ['groups' => ['comments:read']] );
    }



}
