<?php


namespace App\Controller\Main;


use App\Entity\Child;
use App\Entity\PhotoReport;
use App\Entity\Stock;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ChildrenController extends BaseController
{

    /**
     * @Route("/stock/{id_stock}/childrenss/{id_child}", name="main_stock_children_select")
     * @param int $id
     * @param Request $request
     */
    public function showPhotoReports(int $id_stock, Request $request, int $id_child)
    {
        $stock = $this->getDoctrine()->getRepository(Stock::class)->find($id_stock);
        $child = $this->getDoctrine()->getRepository(Child::class)->find($id_child);

        $dateWithout15Days = (new \DateTime('now'))->modify('- 20 days');
        $newPhotoReports = $this->getDoctrine()->getRepository(PhotoReport::class)->findNewPhotoReports($dateWithout15Days);

        $user = $this->getUser();
        $nickname = $user->getNickname();
        if ($child->getReservationNickname() == $nickname)
            $child->setReservationNickname(NULL);
        else
            $child->setReservationNickname($nickname);

        $em = $this->getDoctrine()->getManager();

        $em->persist($child);
        $em->flush();

        $children = $stock->getChildren();
        $institution_names = $this->getDoctrine()->getRepository(Child::class)->findInstitutionNames($id_stock);
        $group_names = $this->getDoctrine()->getRepository(Child::class)->findGroupNames($id_stock);

//        $institution_names = $this->getDoctrine()->getRepository(Child::class)->findInstitutionNames($id);
//        $group_names = $this->getDoctrine()->getRepository(Child::class)->findGroupNames($id);

        $forRender = parent::renderDefault();
        $forRender['stock'] = $stock;
        $forRender['user'] = $user;
        $forRender['children'] = $children;
        $forRender['institution_names'] = $institution_names;
        $forRender['group_names'] = $group_names;
        $forRender['new_photo_reports'] = $newPhotoReports;
        return $this->render("main/stock/children/index.html.twig", $forRender);
    }
}