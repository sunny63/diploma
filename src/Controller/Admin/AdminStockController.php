<?php


namespace App\Controller\Admin;


use App\Entity\Stock;
use App\Form\StockType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminStockController extends AdminBaseController
{
    /**
     * @Route("/admin/stocks", name="admin_stocks")
     * @return Response
     */

    public function index()
    {
        $stocks = $this->getDoctrine()->getRepository(Stock::class)->findAll();

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Акции';
        $forRender['stocks'] = $stocks;
        return $this->render("admin/stock/index.html.twig", $forRender);
    }

    /**
     * @Route("/admin/stock/create", name="admin_stock_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     */

    public function create(Request $request)
    {
        $stock = new Stock();
        $form = $this->createForm(StockType::class, $stock);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);


        if (($form->isSubmitted()) && ($form->isValid()))
        {

            $stock->setCreateAt(new \DateTime('today'));
            $em->persist($stock);
            $em->flush();

            return $this->redirectToRoute("admin_stocks");
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Создание новой акции';
        $forRender['form'] = $form->createView();
        return $this->render("admin/stock/form.html.twig", $forRender);
    }

    /**
     * @Route("/admin/stock/update", name="admin_stock_update")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     */

    public function update(Request $request)
    {
        $stock = new Stock();
        $form = $this->createForm(StockType::class, $stock);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);


        if (($form->isSubmitted()) && ($form->isValid()))
        {

            $stock->setCreateAt(new \DateTime('today'));
//            $form->get('update_at')->setData(new \DateTime('today'));
//            $stock->setUpdateAt(new \DateTime('today'));

            $em->persist($stock);
            $em->flush();

            return $this->redirectToRoute("admin_stocks");
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Создание новой акции';
        $forRender['form'] = $form->createView();
        return $this->render("admin/stock/form.html.twig", $forRender);
    }
}