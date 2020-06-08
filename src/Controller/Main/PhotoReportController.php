<?php


namespace App\Controller\Main;


use App\Entity\PhotoReport;
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
        $photoReports = $this->getDoctrine()->getRepository(PhotoReport::class)->findAll();

        $forRender = parent::renderDefault();
        $forRender['today'] = new \DateTime('now');
        $forRender['title'] = 'Фотоотчеты';
        $forRender['photo_reports'] = $photoReports;
        return $this->render("main/photoReports/index.html.twig", $forRender);
    }


//    /**
//     * @Route("/main/stock/{id}/photoReports", name="main_stock_photo_reports")
//     * @param int $id
//     * @param Request $request
//     */
//    public function showPhotoReports(int $id, Request $request)
//    {
//        $stock = $this->getDoctrine()->getRepository(Stock::class)->find($id);
//        $photoReports = $stock->getPhotoReports();
////        $institution_names = $this->getDoctrine()->getRepository(Child::class)->findInstitutionNames($id);
////        $group_names = $this->getDoctrine()->getRepository(Child::class)->findGroupNames($id);
//
//        $forRender = parent::renderDefault();
//        $forRender['stock'] = $stock;
//        $forRender['photo_reports'] = $photoReports;
//        return $this->render("main/stock/photoReports/index.html.twig", $forRender);
//    }
//
//    /**
//     * @Route("/main/stock/photoReports/{id}/photos", name="main_stock_photos")
//     * @param int $id
//     * @param Request $request
//     */
//    public function showPhotos(int $id, Request $request)
//    {
//        $photoReport = $this->getDoctrine()->getRepository(PhotoReport::class)->find($id);
//        $photos = $photoReport->getPhotos();
////        $institution_names = $this->getDoctrine()->getRepository(Child::class)->findInstitutionNames($id);
////        $group_names = $this->getDoctrine()->getRepository(Child::class)->findGroupNames($id);
//
//        $forRender = parent::renderDefault();
//        $forRender['photo_report'] = $photoReport;
//        $forRender['photos'] = $photos;
//        return $this->render("main/stock/photoReports/photo/index.html.twig", $forRender);
//    }
}