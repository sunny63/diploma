<?php


namespace App\Controller\Main;

use App\Entity\Category;
use App\Entity\PhotoReport;
use App\Entity\Post;
use App\Entity\Stock;
use App\Entity\User;
use App\Form\UserType;
use App\Service\CodeGenerator;
use Symfony\Bridge\Twig\Mime\BodyRenderer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class HomeController extends BaseController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $stocks = $this->getDoctrine()->getRepository(Stock::class)->findAllStocksOrderByDate();
        $posts = $this->getDoctrine()->getRepository(Post::class)->findAllPostsOrderByDate();

        $dateWithout15Days = (new \DateTime('now'))->modify('- 20 days');
        $newPhotoReports = $this->getDoctrine()->getRepository(PhotoReport::class)->findNewPhotoReports($dateWithout15Days);

//        $news = $this->getDoctrine()->getRepository(Stock::class)->findAllOrderByDate();
        $today = new \DateTime('now');
        $dateWithoutDays = (new \DateTime('now'))->modify('- 10 days');

        $forRender = parent::renderDefault();
        $forRender['h1'] = 'Новости';
        $forRender['stocks'] = $stocks;
        $forRender['posts'] = $posts;
        $forRender['date_without_days'] = $dateWithoutDays;
        $forRender['new_photo_reports'] = $newPhotoReports;
        $forRender['today'] = $today;
        return $this->render("main/index.html.twig", $forRender);
    }

    /**
     * @Route("/stocks", name="main_stocks")
     */
    public function showStocks()
    {
        $stocks = $this->getDoctrine()->getRepository(Stock::class)->findAllStocksOrderByDate();
        $today = new \DateTime('now');

        $dateWithout15Days = (new \DateTime('now'))->modify('- 20 days');
        $newPhotoReports = $this->getDoctrine()->getRepository(PhotoReport::class)->findNewPhotoReports($dateWithout15Days);

        $forRender = parent::renderDefault();
        $forRender['h1'] = 'Новости';
        $forRender['stocks'] = $stocks;
        $forRender['today'] = $today;
        $forRender['new_photo_reports'] = $newPhotoReports;
        return $this->render("main/stocks/index.html.twig", $forRender);
    }


    /**
     * @Route("/user/create", name="user_create")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function create(Request $request, UserPasswordEncoderInterface $passwordEncoder, CodeGenerator $codeGenerator) : Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);

        $transport = new GmailSmtpTransport('fondvladmama@gmail.com', 'vfzxoegfmktmbwgp');
        $mailer = new Mailer($transport);

        if (($form->isSubmitted()) && ($form->isValid()))
        {
            $verifyToken = $codeGenerator->getConfirmationCode();
            $user->setVerifyToken($verifyToken);

            $password = $passwordEncoder->encodePassword($user,  $user->getPlainPassword());
            $user->setPassword($password);
            $user->setStatus(false);

            $user->setRoles(["ROLE_USER"]);
            $em->persist($user);
            $em->flush();

            $email = (new TemplatedEmail())
                ->from('fondvladmama@gmail.com')
                ->to($user->getEmail())
                ->subject('Активация аккаунта на благотворительном сайте "Подари празник"')
                ->htmlTemplate('confirmation.html.twig')
                ->context([
                    'username' => $user->getNickname(),
                    'token' => $user->getVerifyToken(),
                ]);

            $loader =  new FilesystemLoader('C:\openserver\domains\ex-symfony2\templates\security');
            $twig =  new Environment($loader);
            $renderer =  new BodyRenderer($twig);
            $renderer->render($email);
            $mailer->send($email);

            return $this->redirectToRoute("app_login");
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Регистрация пользователя';
        $forRender['form'] = $form->createView();
        return $this->render("main/user/form.html.twig", $forRender);
    }

}