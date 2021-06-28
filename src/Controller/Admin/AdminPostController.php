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
use Knp\Component\Pager\PaginatorInterface;


class AdminPostController extends AdminBaseController
{


    /**
     * @Route ("/admin/post{page}", name="admin_post", defaults={"page":1}, requirements={"page":"\d+"})
     * @return Response
     */
    public function index(int $page): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $post = $this->getDoctrine()->getRepository(Post::class)->findBy([], null, 3, $page * 3 - 3);
        $lastPage = $entityManager->getRepository(Post::class)
            ->countPages(3);
        $users = $entityManager->getRepository(User::class)
            ->findBy(['id'=>$post], null, 1, $page * 3 - 3);

        return $this->render('admin/post/index.html.twig',[

            'post'=>$post,
            'page'=>$page,
            'user'=>$users,
            'title'=>'Вакансии',
            'lastPage'=>$lastPage

    ]);
    }

    /**
     * @Route ("/admin/post/search", name="admin_post_search")
     * @param Request $request
     * @return Response
     */
    public function search(Request $request):Response
    {
        $em = $this->getDoctrine()->getManager();
        $searchData = $request->query->all();
        $page=$request->get('page',1);

        $post = $em->getRepository(Post::class)
            ->searchBy($searchData,3,$page * 3 - 3);


        $lastPage = $em->getRepository(Post::class)
            ->countBy($searchData,3);

        return $this->render('admin/post/search.html.twig',[

            'post'=>$post,
            'page'=>$page,
            'title'=>'Вакансии',
            'lastPage'=>$lastPage

        ]);
    }

    /**
     * @Route ("/admin/madepost{page}", name="admin_madepost", defaults={"page":1})
     * @return Response
     */
    public function findAllUserPosts(int $page): Response

    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $id = $user->getId();
        $post = $this->getDoctrine()->getRepository(Post::class)->findBy(['user'=>$id], null, 3, $page * 3 - 3);


        $lastPage = $entityManager->getRepository(Post::class)
            ->countPages(4);

        return $this->render('admin/demand/index.html.twig',[

            'posts'=>$post,
            'page'=>$page,
            'lastPage'=>$lastPage

        ]);

    }


    /**
     * @Route ("/admin/post/single{post}", name="admin_post_single")
     * @param Post $post
     * @return Response
     */
    public function single(Post $post ): Response

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


