<?php


namespace App\Controller\Main;


use App\Entity\Feedback;
use App\Entity\Post;
use App\Form\FeedbackType;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeedbackController extends BaseController
{
    /**
     * @Route("/feedbacks", name="main_feedbacks")
     * @return Response
     */
    public function index()
    {
        $feedbacks = $this->getDoctrine()->getRepository(Feedback::class)->findAllFeedbacksOrderByDate();

        $dateWithoutDays = (new \DateTime('now'))->modify('- 20 days');
        $newPosts = $this->getDoctrine()->getRepository(Post::class)->findNewPosts($dateWithoutDays);

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Ваши отзывы о нас!';
        $forRender['h1'] = 'Отзывы';
        $forRender['feedbacks'] = $feedbacks;
        $forRender['new_posts'] = $newPosts;
        return $this->render("main/feedback/index.html.twig", $forRender);
    }

    /**
     * @Route("/feedback/create", name="main_feedback_create")
     * @param Request $request
     * @return RedirectResponse|Response
     *
     */
    public function create(Request $request, FileUploader $fileUploader)
    {
        $feedback = new Feedback();
        $form = $this->createForm(FeedbackType::class, $feedback);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);

        $dateWithoutDays = (new \DateTime('now'))->modify('- 20 days');
        $newPosts = $this->getDoctrine()->getRepository(Post::class)->findNewPosts($dateWithoutDays);

        if (($form->isSubmitted()) && ($form->isValid()))
        {
            $feedback->setIsPublished();
            $feedback->setCreateAtValue();

            $user = $this->getUser();
            if ($user != NULL)
            {
                $nickname = $user->getNickname();
                $feedback->setNickname($nickname);
            }

            $em->persist($feedback);
            $em->flush();

            $this->addFlash('success', 'Отзыв успешно создан!');

            return $this->redirectToRoute("main_feedbacks");
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Создание отзыва';
        $forRender['form'] = $form->createView();
        $forRender['new_posts'] = $newPosts;
        return $this->render("main/feedback/form.html.twig", $forRender);
    }
}