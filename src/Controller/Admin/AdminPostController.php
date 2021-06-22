<?php


namespace App\Controller\Admin;


use App\Entity\Post;
use App\Entity\User;
use App\Form\PostFormType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Session\Session;


class AdminPostController extends AdminBaseController
{

    /**
     * @Route ("/admin/post", name="admin_post")
     * @return Response
     */
    public function index(): Response
    {
        $session = new Session();
        $session->start();

        $post = $this->getDoctrine()->getRepository(Post::class)->findAll();

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Вакансии';
        $forRender['post'] = $post;

        return $this->render('admin/post/index.html.twig', $forRender);
    }


    /**
     * @Route ("/admin/post/single{post}", name="admin_post_single")
     * @param Post $post
     * @return Response
     */
    public function single(Post $post): Response
    {


        return $this->render('admin/post/single.html.twig', ['post'=>$post]);
    }




    /**
     * @Route ("/admin/post/create", name="admin_post_create")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function create(Request $request)
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();


        $em = $this->getDoctrine()->getManager();
        $post = new Post();
        $post = $post->setUser($user);
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($post);
            $em->flush();

            $this->addFlash('success', 'Вакансия добавлена');
            return $this->redirectToRoute('admin_post');
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Создание Вакансии';
        $forRender['form'] = $form->createView();
        return $this->render('admin/post/form.html.twig', $forRender);
    }



    /**
     * @Route("/admin/post/update/{id}", name="admin_post_update")
     * @param int $id
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function update(int $id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);

        if ($form->IsSubmitted() && $form->isValid()) {
            if ($form->get('save')->isClicked()) {
                $this->addFlash('success', 'Вакансия обновлена');
            }

            if ($form->get('delete')->isClicked()) {
                $em->remove($post);
                $this->addFlash('danger', 'Вакансия удалена');
            }
            $em->flush();

            return $this->redirectToRoute('admin_post');

        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Редактрование Вакансии';
        $forRender['form'] = $form->createView();
        return $this->render('admin/post/form.html.twig', $forRender);

    }

}


