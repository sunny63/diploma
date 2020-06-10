<?php


namespace App\Controller\Main;

use App\Entity\Category;
use App\Entity\PhotoReport;
use App\Entity\Post;
use App\Entity\Stock;
use App\Entity\User;
use App\Form\UserType;
use App\Service\CodeGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class HomeController extends BaseController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $stocks = $this->getDoctrine()->getRepository(Stock::class)->findAllStocksOrderByDate();
        $posts = $this->getDoctrine()->getRepository(Post::class)->findAllPostsOrderByDate();
        $photoReports = $this->getDoctrine()->getRepository(PhotoReport::class)->findAllPhotoReportsOrderByDate();

//        $news = $this->getDoctrine()->getRepository(Stock::class)->findAllOrderByDate();

        $today = new \DateTime('now');

//        $categoriesNow = $this->getDoctrine()
//            ->getRepository(Stock::class)
//            ->findAllPastStock($today);

        $forRender = parent::renderDefault();
        $forRender['h1'] = 'Новости';
        $forRender['stocks'] = $stocks;
        $forRender['posts'] = $posts;
        $forRender['photo_reports'] = $photoReports;
        $forRender['today'] = $today;

//        $forRender['categoriesNow'] = $categoriesNow;
        return $this->render("main/index.html.twig", $forRender);
    }

    /**
     * @Route("/stocks", name="main_stocks")
     */
    public function showStocks()
    {
        $stocks = $this->getDoctrine()->getRepository(Stock::class)->findAllStocksOrderByDate();




        $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        $today = new \DateTime('now');

        $categoriesNow = $this->getDoctrine()
            ->getRepository(Stock::class)
            ->findAllPastStock($today);

        $forRender = parent::renderDefault();
        $forRender['h1'] = 'Новости';
        $forRender['stocks'] = $stocks;
//        $forRender['posts'] = $posts;
        $forRender['today'] = $today;
//        $forRender['categories'] = $categories;
//        $forRender['categoriesNow'] = $categoriesNow;
        return $this->render("main/stocks/index.html.twig", $forRender);
    }

    /**
     * @Route("/user/create", name="user_create")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function create(Request $request, UserPasswordEncoderInterface $passwordEncoder) : Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);

        $transport = new GmailSmtpTransport('fondvladmama@gmail.com', 'vfzxoegfmktmbwgp');
        $mailer = new Mailer($transport);

        if (($form->isSubmitted()) && ($form->isValid()))
        {
            $email = (new Email())
                ->from('fondvladmama@gmail.com')
                ->to('hataiiia1999@mail.ru')
//                ->cc('bar@example.com')
//                ->bcc('baz@example.com')
//                ->replyTo('fabien@symfony.com')
//                ->priority(Email::PRIORITY_HIGH)
                ->subject('Important Notification')
                ->text('L222orem ipsum...')
                ->html('<h1>Lorem ipsum</h1> <p>...</p>');
            $mailer->send($email);

            $password = $passwordEncoder->encodePassword($user,  $user->getPlainPassword());
            $user->setPassword($password);
            $user->setRoles(["ROLE_USER"]);
            $em->persist($user);
            $em->flush();

//            $mailer->send();
//            $user->setConfirmationCode($codeGenerator->getConfirmationCode());

            return $this->redirectToRoute("app_login");
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Регистрация пользователя';
        $forRender['form'] = $form->createView();
        return $this->render("main/user/form.html.twig", $forRender);
    }
}