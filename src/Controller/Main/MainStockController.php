<?php

namespace App\Controller\Main;


use App\Entity\Stock;
use App\Form\StockType;
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
        $form = $this->createForm(StockType::class, $stock);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);

        $date = new \DateTime('now');

        $categoriesNow = $this->getDoctrine()
            ->getRepository(Stock::class)
            ->findAllPastStock($date);

        $forRender = parent::renderDefault();
        $forRender['h1'] = 'Новости';
//        $forRender['stocks'] = $stocks;
//        $forRender['posts'] = $posts;
//        $forRender['categories'] = $categories;
        $forRender['categoriesNow'] = $categoriesNow;
        return $this->render("main/index.html.twig", $forRender);
    }
}