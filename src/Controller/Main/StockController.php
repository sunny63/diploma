<?php


namespace App\Controller\Main;

use App\Entity\PhotoReport;
use App\Entity\Photo;
use App\Entity\Stock;
use App\Entity\Child;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class StockController extends BaseController
{
    /**
     * @Route("/main/stock/{id}", name="main_stock")
     * @param int $id
     * @param Request $request
     */
    public function index(int $id, Request $request)
    {
        $stock = $this->getDoctrine()->getRepository(Stock::class)->find($id);

        $forRender = parent::renderDefault();
        $forRender['today'] = new \DateTime('now');
        $forRender['stock'] = $stock;
        return $this->render("main/stock/index.html.twig", $forRender);
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

        $forRender = parent::renderDefault();
        $forRender['stock'] = $stock;
        $forRender['children'] = $children;
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
//        $institution_names = $this->getDoctrine()->getRepository(Child::class)->findInstitutionNames($id);
//        $group_names = $this->getDoctrine()->getRepository(Child::class)->findGroupNames($id);

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
//        $institution_names = $this->getDoctrine()->getRepository(Child::class)->findInstitutionNames($id);
//        $group_names = $this->getDoctrine()->getRepository(Child::class)->findGroupNames($id);

        $forRender = parent::renderDefault();
        $forRender['photo_report'] = $photoReport;
        $forRender['photos'] = $photos;
        return $this->render("main/stock/photoReports/photo/index.html.twig", $forRender);
    }


}