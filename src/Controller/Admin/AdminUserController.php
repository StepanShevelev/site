<?php


namespace App\Controller\Admin;


use App\Entity\Post;
use App\Entity\User;
use App\Form\PostFormType;
use App\Form\UserFormType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminUserController extends AdminBaseController
{
    /**
     * @Route ("/admin/user", name="admin_user")
     * @return Response
     */
    public function index()
    {
        $users= $this->getDoctrine()->getRepository(User::class)->findAll();


        $forRender = $this->renderDefault();
        $forRender['title'] = 'Пользователи';
        $forRender['users'] = $users;
        return $this->render('admin/user/index.html.twig',$forRender);

    }

    /**
     * @Route ("/admin/user/create", name="admin_user_create")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return RedirectResponse|Response
     */
    public function create(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserFormType::class, $user);
        $em =$this->getDoctrine()->getManager();
        $form->handleRequest($request);

        if(($form->isSubmitted()) && ($form-> isValid()))
        {
            $password = $passwordEncoder->encodePassword($user, $user->GetPassword());
            $user-> setPassword($password);
            $user->setRoles(["ROLE_USER"]);
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('admin_user');

        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Форма создания пользователей';
        $forRender['form'] = $form->createView();
        return $this->render('admin/user/form.html.twig',$forRender);
    }


    /**
     * @Route("/admin/user/update/{id}", name="admin_user_update")
     * @param int $id
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function update(int $id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->IsSubmitted() && $form->isValid()) {
            if ($form->get('save')->isClicked()) {
                $this->addFlash('success', 'Данные обновлены');
            }

            $em->flush();

            return $this->redirectToRoute('profile');

        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Редактрование Пользователя';
        $forRender['form'] = $form->createView();
        return $this->render('admin/user/form.html.twig', $forRender);

    }

}