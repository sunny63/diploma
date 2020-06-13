<?php


namespace App\Controller\Admin;


use App\Entity\Category;
use App\Entity\Feedback;
use App\Entity\Post;
use App\Form\CategoryType;
use App\Form\FeedbackType;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminFeedbackController extends AdminBaseController
{
    /**
     * @Route("/admin/feedbacks", name="admin_feedbacks")
     */
    public function index()
    {
        $feedbacks = $this->getDoctrine()->getRepository(Feedback::class)->findAll();

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Отзывы';
        $forRender['h1'] = 'Список всех отзывов';
        $forRender['feedbacks'] = $feedbacks;
        return $this->render("admin/feedback/index.html.twig", $forRender);
    }

    /**
     * @Route("/admin/feedback/create", name="admin_feedback_create")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function create(Request $request, FileUploader $fileUploader)
    {
        $feedback = new Feedback();
        $form = $this->createForm(FeedbackType::class, $feedback);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);

        if (($form->isSubmitted()) && ($form->isValid()))
        {
            $feedback->setIsPublished();
            $feedback->setCreateAtValue();

            $em->persist($feedback);
            $em->flush();

            $this->addFlash('success', 'Отзыв успешно создан!');

            return $this->redirectToRoute("admin_feedbacks");
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Создание отзыва';
        $forRender['form'] = $form->createView();
        return $this->render("admin/feedback/form.html.twig", $forRender);
    }

    /**
     * @Route("/admin/feedback/doPublished/{id}", name="admin_feedback_do_published")
     * @param int $id
     * @param Request $request
     */
    public function doPublished(int $id, Request $request)
    {
        $feedback = $this->getDoctrine()->getRepository(Feedback::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $feedback->setIsPublished();

        $this->addFlash('success', 'Отзыв опубликован!');
        $em->flush();

        return $this->redirectToRoute('admin_feedbacks');
    }


    /**
     * @Route("/admin/feedback/doDraft/{id}", name="admin_feedback_do_draft")
     * @param int $id
     * @param Request $request
     */
    public function doDraft(int $id, Request $request)
    {
        $feedback = $this->getDoctrine()->getRepository(Feedback::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $feedback->setIsDraft();

        $this->addFlash('success', 'Отзыв скрыт!');
        $em->flush();

        return $this->redirectToRoute('admin_feedbacks');
    }

    /**
     * @Route("/admin/feedback/update/{id}", name="admin_feedback_update")
     * @param int $id
     * @param Request $request
     */
    public function update(int $id, Request $request, FileUploader $fileUploader)
    {
        $feedback = $this->getDoctrine()->getRepository(Feedback::class)->find($id);
        $form = $this->createForm(FeedbackType::class, $feedback);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);

        if (($form->isSubmitted()) && ($form->isValid()))
        {
//            /** @var UploadedFile $image */
//            $image = $form['image']->getData();
//            if ($image) {
//                $nameEntity = "category";
//                $imageFileName = $fileUploader->upload($image, $nameEntity);
//                $feedback->setImage($imageFileName);
//            }

            $em->persist($feedback);
            $this->addFlash('success', 'Отзыв обновлен');
            $em->flush();

            return $this->redirectToRoute('admin_feedbacks');
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Обновление отзыва';
        $forRender['form'] = $form->createView();
        return $this->render("admin/feedback/form.html.twig", $forRender);
    }


    /**
     * @Route("/admin/feedback/delete/{id}", name="admin_feedback_delete")
     * @param int $id
     * @param Request $request
     */
    public function delete(int $id, Request $request)
    {
        $feedback = $this->getDoctrine()->getRepository(Feedback::class)->find($id);
        $em = $this->getDoctrine()->getManager();

        $em->remove($feedback);
        $this->addFlash('success', 'Отзыв удален');
        $em->flush();

        return $this->redirectToRoute('admin_feedbacks');
    }
}