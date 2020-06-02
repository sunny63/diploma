<?php


namespace App\Controller\Main;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Stock;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        $stocks = $this->getDoctrine()->getRepository(Stock::class)->findAll();
        $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        $date = new \DateTime('now');

        $categoriesNow = $this->getDoctrine()
            ->getRepository(Stock::class)
            ->findAllPastStock($date);

        $forRender = parent::renderDefault();
        $forRender['h1'] = 'Новости';
        $forRender['stocks'] = $stocks;
        $forRender['posts'] = $posts;
        $forRender['categories'] = $categories;
        $forRender['categoriesNow'] = $categoriesNow;
        return $this->render("main/index.html.twig", $forRender);
    }

    /**
     * @Route("/user/create", name="user_create")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     */

    public function create(Request $request, UserPasswordEncoderInterface $passwordEncoder, ValidatorInterface $validator) : Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);

        if (($form->isSubmitted()) && ($form->isValid()))
        {
            $password = $passwordEncoder->encodePassword($user,  $user->getPlainPassword());
            $user->setPassword($password);
            $user->setRoles(["ROLE_USER"]);
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute("app_login");
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Регистрация пользователя';
        $forRender['form'] = $form->createView();
        return $this->render("main/user/form.html.twig", $forRender);
    }
}