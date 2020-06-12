<?php


namespace App\Controller\Main;


use App\Entity\PhotoReport;
use App\Entity\Post;
use App\Entity\Stock;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PhotoReportController extends BaseController
{
    /**
     * @Route("/main/photoReports/", name="main_photo_reports")
     * @param int $id
     * @param Request $request
     */
    public function index( Request $request)
    {
        $photoReports = $this->getDoctrine()->getRepository(PhotoReport::class)->findAllPhotoReportsOrderByDate();

        $dateWithoutDays = (new \DateTime('now'))->modify('- 20 days');
        $newPosts = $this->getDoctrine()->getRepository(Post::class)->findNewPosts($dateWithoutDays);

        $forRender = parent::renderDefault();
        $forRender['today'] = new \DateTime('now');
        $forRender['title'] = 'Фотоотчеты';
        $forRender['photo_reports'] = $photoReports;
        $forRender['new_posts'] = $newPosts;
        return $this->render("main/photoReports/index.html.twig", $forRender);
    }
}