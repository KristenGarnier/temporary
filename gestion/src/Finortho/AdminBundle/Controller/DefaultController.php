<?php

namespace Finortho\AdminBundle\Controller;

use Finortho\Fritage\EchangeBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $file_tree = $this->get('finortho_fritage_echange.file_tree');
        $tree = $file_tree->php_file_tree($this->get('kernel')->getRootDir() . '/../web/uploads/commandes_utilisateurs', "/uploads/commandes_utilisateurs/[link]");
        return $this->render('FinorthoAdminBundle:Default:index.html.twig', array('tree' => $tree));
    }

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

    public function listUserAction(){
        $userManager = $this->get('fos_user.user_manager');
        $users = $userManager->findUsers();

        return $this->render('FinorthoAdminBundle:Default:users.html.twig', array('users' => $users));
    }

    public function deleteUserAction($id){
        $user = $this->getDoctrine()->getRepository('FinorthoFritageEchangeBundle:User')->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        $this->get('session')->getFlashBag()->set('success', 'L’utilisateur a bien été suprimmé.');

        return $this->redirect($this->generateUrl('finortho_admin_list_users'));
    }
}
