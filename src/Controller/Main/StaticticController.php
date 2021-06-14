<?php


namespace App\Controller\Main;


use App\Entity\Child;
use App\Entity\PhotoReport;
use App\Entity\Stock;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StaticticController extends BaseController
{
    /**
     * @Route("/main/statistic/", name="main_statistic")
     * @param Request $request
     */
    public function index(Request $request)
    {
        $past_stocks = $this->getDoctrine()->getRepository(Stock::class)->findAllPastStock(new \DateTime('now'));

        $past1 = $past_stocks[1]->getChildren();

        $dateWithout15Days = (new \DateTime('now'))->modify('- 20 days');
        $photoReports = $this->getDoctrine()->getRepository(PhotoReport::class)->findNewPhotoReports($dateWithout15Days);
        $forRender['photo_reports'] = $photoReports;


        $forRender = parent::renderDefault();
        $forRender['today'] = new \DateTime('now');
        $forRender['title'] = 'Статистика работы на';
        $forRender['past_stocks'] = $past_stocks;
        $forRender['past1'] = $past1;

        return $this->render("main/statistic/index.html.twig", $forRender);
    }

    /**
     * @Route("/main/stock/{id}/children", name="main_stock_children")
     * @param int $id
     * @param Request $request
     */
    public function showChildrens(int $id, Request $request)
    {
        $stock = $this->getDoctrine()->getRepository(Stock::class)->find($id);
        $children = $stock->getChildren();
        $institution_names = $this->getDoctrine()->getRepository(Child::class)->findInstitutionNames($id);
        $group_names = $this->getDoctrine()->getRepository(Child::class)->findGroupNames($id);

        $user = $this->getUser();

        $dateWithout15Days = (new \DateTime('now'))->modify('- 20 days');
        $photoReports = $this->getDoctrine()->getRepository(PhotoReport::class)->findNewPhotoReports($dateWithout15Days);
        $forRender['photo_reports'] = $photoReports;


        $forRender = parent::renderDefault();
        $forRender['stock'] = $stock;
        $forRender['children'] = $children;
        $forRender['user'] = $user;
        $forRender['institution_names'] = $institution_names;
        $forRender['group_names'] = $group_names;
        return $this->render("main/stock/children/index.html.twig", $forRender);
    }

    /**
     * @Route("/main/stock/{id}/photoReports", name="main_stock_photo_reports")
     * @param int $id
     * @param Request $request
     */
    public function showPhotoReports(int $id, Request $request)
    {
        $stock = $this->getDoctrine()->getRepository(Stock::class)->find($id);
        $photoReports = $stock->getPhotoReports();

        $forRender = parent::renderDefault();
        $forRender['stock'] = $stock;
        $forRender['photo_reports'] = $photoReports;
        return $this->render("main/stock/photoReports/index.html.twig", $forRender);
    }

    /**
     * @Route("/main/stock/photoReports/{id}/photos", name="main_stock_photos")
     * @param int $id
     * @param Request $request
     */
    public function showPhotos(int $id, Request $request)
    {
        $photoReport = $this->getDoctrine()->getRepository(PhotoReport::class)->find($id);
        $photos = $photoReport->getPhotos();

        $referer = $request->headers->get('referer');

        $forRender = parent::renderDefault();
        $forRender['photo_report'] = $photoReport;
        $forRender['photos'] = $photos;
        $forRender['referer'] = $referer;
        return $this->render("main/stock/photoReports/photo/index.html.twig", $forRender);
    }
}