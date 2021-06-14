<?php


namespace App\Controller\Main;


use App\Entity\PhotoReport;
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

        $dateWithout15Days = (new \DateTime('now'))->modify('- 20 days');
        $newPhotoReports = $this->getDoctrine()->getRepository(PhotoReport::class)->findNewPhotoReports($dateWithout15Days);

        $forRender = parent::renderDefault();
        $forRender['date_without_days'] = $dateWithoutDays;
        $forRender['title'] = 'Посты';
        $forRender['posts'] = $posts;
        $forRender['new_photo_reports'] = $newPhotoReports;
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

        $dateWithout15Days = (new \DateTime('now'))->modify('- 20 days');
        $newPhotoReports = $this->getDoctrine()->getRepository(PhotoReport::class)->findNewPhotoReports($dateWithout15Days);

        $forRender = parent::renderDefault();
        $forRender['today'] = new \DateTime('now');
        $forRender['post'] = $post;
        $forRender['new_photo_reports'] = $newPhotoReports;
        return $this->render("main/posts/post/index.html.twig", $forRender);
    }
}