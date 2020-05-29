<?php


namespace App\Controller\Admin;


use App\Entity\Category;
use App\Entity\Post;
use App\Form\PostType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPostController extends AdminBaseController
{
    /**
     * @Route("/admin/posts", name="admin_posts")
     */
    public function index()
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->findAll();

        $checkCategory = $this->getDoctrine()->getRepository(Category::class)->findAll();


        $forRender = parent::renderDefault();
        $forRender['title'] = 'Посты';
        $forRender['h1'] = 'Список всех постов';
        $forRender['posts'] = $post;
        $forRender['check_category'] = $checkCategory;
        return $this->render("admin/post/index.html.twig", $forRender);
    }

    /**
     * @Route("/admin/post/create", name="admin_post_create")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function create(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);

        if (($form->isSubmitted()) && ($form->isValid()))
        {
            $post->setCreateAtValue();
            $post->setUpdateAtValue();
            $post->setIsPublished();

            $em->persist($post);
            $em->flush();

            $this->addFlash('success', 'Пост доабвлен');

            return $this->redirectToRoute('admin_posts');
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Создание поста';
        $forRender['form'] = $form->createView();
        return $this->render("admin/post/form.html.twig", $forRender);
    }
}