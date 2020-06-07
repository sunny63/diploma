<?php


namespace App\Controller\Admin;


use App\Entity\Photo;
use App\Entity\PhotoReport;
use App\Entity\Stock;
use App\Form\PhotoReportType;
use App\Form\PhotoType;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPhotoController extends AdminBaseController
{
    /**
     * @Route("/admin/photos", name="admin_photos")
     */
    public function index()
    {
        $photos = $this->getDoctrine()->getRepository(Photo::class)->findAll();
        $checkPhotoReport = $this->getDoctrine()->getRepository(PhotoReport::class)->findAll();


        $forRender = parent::renderDefault();
        $forRender['title'] = 'Фотографии';
        $forRender['h1'] = 'Список всех фотографий';
        $forRender['photos'] = $photos;
        $forRender['check_photo_report'] = $checkPhotoReport;
        $forRender['id_photo_report'] = 0;
        return $this->render("admin/photoReport/photo/index.html.twig", $forRender);
    }

    /**
     * @Route("/admin/photo/create/{id_photo_report}", name="admin_photo_create", defaults = {"id_photo_report" = 0})
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function create(Request $request, FileUploader $fileUploader, int $id_photo_report)
    {
        $photo = new Photo();

        if ($id_photo_report)
        {
            $photoReport = $this->getDoctrine()->getRepository(PhotoReport::class)->find($id_photo_report);
            $photo->setPhotoReport($photoReport);
            $form = $this->createForm(PhotoType::class, $photo, array('is_photo_report_photos' => true));
        }
        else
            $form = $this->createForm(PhotoType::class, $photo);

        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);

        if (($form->isSubmitted()) && ($form->isValid()))
        {

            /** @var UploadedFile $image */
            $image = $form['image']->getData();
            if ($image) {
                $nameEntity = "photo";
                $imageFileName = $fileUploader->upload($image, $nameEntity);
                $photo->setImage($imageFileName);
            }

            $em->persist($photo);
            $em->flush();
            $this->addFlash('success', 'Фотография создана');

            if ($id_photo_report)
                return $this->redirectToRoute('admin_photo_report_photos', array('id' => $id_photo_report));
            else
                return $this->redirectToRoute('admin_photos');
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Добавление фотографии';
        $forRender['form'] = $form->createView();
        return $this->render("admin/photoReport/photo/form.html.twig", $forRender);
    }

    /**
     * @Route("/admin/photo/update/{id},{id_photo_report}", name="admin_photo_update", defaults = {"id_photo_report" = 0})
     * @param int $id
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function update(int $id, Request $request, FileUploader $fileUploader, int $id_photo_report)
    {
        $photo = $this->getDoctrine()->getRepository(Photo::class)->find($id);
        $form = $this->createForm(PhotoType::class, $photo);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);

        if (($form->isSubmitted()) && ($form->isValid()))
        {
            /** @var UploadedFile $image */
            $image = $form['image']->getData();
            if ($image) {
                $nameEntity = "photo";
                $imageFileName = $fileUploader->upload($image, $nameEntity);
                $photo->setImage($imageFileName);
            }

            $em->persist($photo);
            $this->addFlash('success', 'Фотография обновлена');
            $em->flush();

            if ($id_photo_report)
                return $this->redirectToRoute('admin_photo_report_photos', array('id' => $id_photo_report));
            else
                return $this->redirectToRoute('admin_photos');
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Обновление фотографии';
        $forRender['form'] = $form->createView();
        return $this->render("admin/photoReport/photo/form.html.twig", $forRender);
    }

    /**
     * @Route("/admin/photo/delete/{id},{id_photo_report}", name="admin_photo_delete", defaults = {"id_photo_report" = 0})
     * @param int $id
     * @param Request $request
     */
    public function delete(int $id, Request $request, int $id_photo_report)
    {
        $photo = $this->getDoctrine()->getRepository(Photo::class)->find($id);
        $em = $this->getDoctrine()->getManager();

        $em->remove($photo);
        $this->addFlash('success', 'Фотография удалена');
        $em->flush();

        if ($id_photo_report)
            return $this->redirectToRoute('admin_photo_report_photos', array('id' => $id_photo_report));
        else
            return $this->redirectToRoute('admin_photos');
    }
}