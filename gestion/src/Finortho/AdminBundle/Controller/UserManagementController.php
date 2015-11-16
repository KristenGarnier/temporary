<?php

namespace Finortho\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class UserManagementController
 *
 * Classe permettant la gestion des utilisateurs
 *
 * @package Finortho\AdminBundle\Controller
 */
class UserManagementController extends Controller
{
    /**
     * Méthode permettant d'ajouter un utilisateur à l'interface de multi upload
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \HttpException
     */
    public function addUserAction(Request $request)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->createUser();
        if ($this->get('request')->getMethod() == 'POST') {

            $user->setUsername($request->get('username'));
            $user->setEmail($request->get('email'));
            $user->setPlainPassword($request->get('password'));
            $user->setEnabled(true);

            $exists = $userManager->findUserBy(array('email' => $user->getEmail()));
            if ($exists instanceof User) {
                throw new \HttpException(409, 'Email already taken');
            }

            $existsUsername = $userManager->findUserBy(array('username' => $user->getUsername()));
            if ($existsUsername instanceof User) {
                throw new \HttpException(409, 'Username already taken');
            }

            $userManager->updateUser($user);
            return $this->redirect($this->generateUrl('finortho_admin_list_users'));
        }

        return $this->render('FinorthoAdminBundle:Default:newuser.html.twig');
    }

    /**
     * Méthode permettant de lester les utilisateurs
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listUserAction()
    {
        $userManager = $this->get('fos_user.user_manager');
        $users = $userManager->findUsers();

        return $this->render('FinorthoAdminBundle:Default:users.html.twig', array('users' => $users));
    }

    /**
     * Méthode permettant de suprimmer un utilisateur
     *
     * @param int $id id de l'utilisateur à suprimmer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteUserAction($id)
    {
        $user = $this->getDoctrine()->getRepository('FinorthoFritageEchangeBundle:User')->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        $this->get('session')->getFlashBag()->set('success', 'L’utilisateur a bien été suprimmé.');

        return $this->redirect($this->generateUrl('finortho_admin_list_users'));
    }
}
