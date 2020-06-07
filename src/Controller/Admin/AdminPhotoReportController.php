<?php


namespace App\Controller\Admin;

use App\Entity\Child;
use App\Entity\PhotoReport;
use App\Entity\Stock;
use App\Form\ChildType;
use App\Form\PhotoReportType;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPhotoReportController extends AdminBaseController
{
    /**
     * @Route("/admin/photoReports", name="admin_photo_reports")
     */
    public function index()
    {
        $photoReport = $this->getDoctrine()->getRepository(PhotoReport::class)->findAll();
        $checkStock = $this->getDoctrine()->getRepository(Stock::class)->findAll();


        $forRender = parent::renderDefault();
        $forRender['title'] = 'Фотоотчеты';
        $forRender['h1'] = 'Список всех фотоотчетов';
        $forRender['photo_reports'] = $photoReport;
        $forRender['check_stock'] = $checkStock;
        return $this->render("admin/photoReport/index.html.twig", $forRender);
    }

    /**
     * @Route("/admin/photoReport/create", name="admin_photo_report_create")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function create(Request $request, FileUploader $fileUploader)
    {
        $photoReport = new PhotoReport();
        $form = $this->createForm(PhotoReportType::class, $photoReport);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);

        if (($form->isSubmitted()) && ($form->isValid()))
        {
            $photoReport->setCreateAtValue();

            /** @var UploadedFile $image */
            $image = $form['image']->getData();
            if ($image) {
                $nameEntity = "photoReport";
                $imageFileName = $fileUploader->upload($image, $nameEntity);
                $photoReport->setImage($imageFileName);
            }

            $em->persist($photoReport);
            $em->flush();

            $this->addFlash('success', 'Фотоотчет создан');

            return $this->redirectToRoute('admin_photo_reports');
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Создание фотоотчета';
        $forRender['form'] = $form->createView();
        return $this->render("admin/photoReport/form.html.twig", $forRender);
    }

    /**
     * @Route("/admin/photoReport/update/{id}", name="admin_photo_report_update")
     * @param int $id
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function update(int $id, Request $request, FileUploader $fileUploader)
    {
        $photoReport = $this->getDoctrine()->getRepository(PhotoReport::class)->find($id);
        $form = $this->createForm(PhotoReportType::class, $photoReport);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);

        if (($form->isSubmitted()) && ($form->isValid()))
        {
            /** @var UploadedFile $image */
            $image = $form['image']->getData();
            if ($image) {
                $nameEntity = "photoReport";
                $imageFileName = $fileUploader->upload($image, $nameEntity);
                $photoReport->setImage($imageFileName);
            }

            $em->persist($photoReport);
            $this->addFlash('success', 'Фотоотчет обновлен');
            $em->flush();

            return $this->redirectToRoute('admin_photo_reports');
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Обновление фотоотчета';
        $forRender['form'] = $form->createView();
        return $this->render("admin/photoReport/form.html.twig", $forRender);
    }

    /**
     * @Route("/admin/photoReport/delete/{id}", name="admin_photo_report_delete")
     * @param int $id
     * @param Request $request
     */
    public function delete(int $id, Request $request)
    {
        $photoReport = $this->getDoctrine()->getRepository(PhotoReport::class)->find($id);
        $em = $this->getDoctrine()->getManager();

        $em->remove($photoReport);
        $this->addFlash('success', 'Фотоотчет удален');
        $em->flush();

        return $this->redirectToRoute('admin_photo_reports');
    }

    /**
     * @Route("/admin/photoReport/photos/{id}", name="admin_photo_report_photos")
     * @param int $id
     * @param Request $request
     */
    public function showPhotos(int $id, Request $request)
    {
        $photoReport = $this->getDoctrine()->getRepository(PhotoReport::class)->find($id);
        $photos = $photoReport->getPhotos();
        $em = $this->getDoctrine()->getManager();

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Список фотографий';
        $forRender['h1'] = 'Все фотографии для данного фотоотчета';
        $forRender['photos'] = $photos;
        $forRender['id_photo_report'] = $id;
        return $this->render("admin/photoReport/photo/index.html.twig", $forRender);
    }
}