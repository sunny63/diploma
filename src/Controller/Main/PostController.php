<?php


namespace App\Controller\Main;


use App\Entity\Post;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends BaseController
{
    /**
     * @Route("/main/posts/", name="main_posts")
     * @param Request $request
     */
    public function index( Request $request)
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)->findAllPostsOrderByDate();
        $dateWithoutDays = (new \DateTime('now'))->modify('- 10 days');

        $forRender = parent::renderDefault();
        $forRender['date_without_days'] = $dateWithoutDays;
        $forRender['title'] = 'Посты';
        $forRender['posts'] = $posts;
        return $this->render("main/posts/index.html.twig", $forRender);
    }

    /**
     * @Route("/main/post/{id}", name="main_post")
     * @param int $id
     * @param Request $request
     */
    public function showPost(int $id, Request $request)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);

        $forRender = parent::renderDefault();
        $forRender['today'] = new \DateTime('now');
        $forRender['post'] = $post;
        return $this->render("main/posts/post/index.html.twig", $forRender);
    }
}