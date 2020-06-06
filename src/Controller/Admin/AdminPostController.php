<?php


namespace App\Controller\Admin;


use App\Entity\Category;
use App\Entity\Post;
use App\Form\PostType;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
    public function create(Request $request, FileUploader $fileUploader)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);

        if (($form->isSubmitted()) && ($form->isValid()))
        {
            $post->setCreateAtValue();
            $post->setIsPublished();

            /** @var UploadedFile $image */
            $image = $form['image']->getData();
            if ($image) {
                $nameEntity = "post";
                $imageFileName = $fileUploader->upload($image, $nameEntity);
                $post->setImage($imageFileName);
            }

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

    /**
     * @Route("/admin/post/update/{id}", name="admin_post_update")
     * @param int $id
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function update(int $id, Request $request, FileUploader $fileUploader)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);
        $form = $this->createForm(PostType::class, $post);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);

        if (($form->isSubmitted()) && ($form->isValid()))
        {
            $post->setUpdateAtValue();

            /** @var UploadedFile $image */
            $image = $form['image']->getData();
            if ($image) {
                $nameEntity = "post";
                $imageFileName = $fileUploader->upload($image, $nameEntity);
                $post->setImage($imageFileName);
            }

            $em->persist($post);
            $this->addFlash('success', 'Пост обновлен');
            $em->flush();

            return $this->redirectToRoute('admin_posts');
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Обновление поста';
        $forRender['form'] = $form->createView();
        return $this->render("admin/post/form.html.twig", $forRender);
    }

    /**
     * @Route("/admin/post/delete/{id}", name="admin_post_delete")
     * @param int $id
     * @param Request $request
     */
    public function delete(int $id, Request $request)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);
        $em = $this->getDoctrine()->getManager();

        $em->remove($post);
        $this->addFlash('success', 'Пост удален');
        $em->flush();

        return $this->redirectToRoute('admin_posts');
    }
}