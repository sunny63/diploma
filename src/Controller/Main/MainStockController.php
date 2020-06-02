<?php


namespace App\Controller\Main;


use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Stock;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MainStockController extends BaseController
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
        $forRender['h1'] = 'Новости';
        $forRender['stock'] = $stock;
//        $forRender['categoriesNow'] = $categoriesNow;
        return $this->render("main/stock/index.html.twig", $forRender);
    }
}