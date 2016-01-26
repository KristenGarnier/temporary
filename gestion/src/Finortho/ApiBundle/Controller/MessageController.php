<?php

namespace Finortho\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class MessageController
 * @package Finortho\ApiBundle\Controller
 */
class MessageController extends FOSRestController
{
    /**
     * Get all the message from a user
     *
     * @param Request $request
     * @return mixed
     */
    public function getMessagesAction(Request $request)
    {
        $user = $request->headers->get('user');

        if ($user != NULL) {

            return $this->get('getOr404')->check(
                $this->get('message_handler'),
                'userMessages',
                $user
            );
        } else {
            throw new HttpException(403, "Access Forbidden");
        }
    }

    /**
     * Save a user message in the db
     *
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    public function postMessageAction(Request $request)
    {
        $user = $request->headers->get('user');
        if ($user != NULL) {
            if ($this->get('getOr404')->check(null, null, $user, false)) {
                if ($this->get('message_exist')->check($request->get('text'), $user)) {
                    $this->get('message_handler')->addUserMessage($user, $request->request->all());

                    $this->get('finortho_fritage_echange.email_admin')->sendAdminNotificationMessage(
                        $this->getDoctrine()
                            ->getRepository('FinorthoFritageEchangeBundle:User')
                            ->find($user),
                        $request->get('text')
                    );

                    $routeOptions = array(
                        '_format' => $request->get('_format')
                    );

                    return $this->view(null, Codes::HTTP_CREATED, $routeOptions);
                }
                throw new HttpException(409, "Message already exists");
            }
        } else {
            throw new HttpException(403, "Access Forbidden");
        }
    }
}
