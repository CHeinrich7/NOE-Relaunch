<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use UserBundle\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use UserBundle\Entity\User;
use UserBundle\Form\UserType;

/**
 * Class RegisterController
 * @package UserBundle\Controller
 */
class RegisterController extends Controller
{
    const LOGIN_TEMPLATE = 'UserBundle:Register:login.html.php';

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function loginAction(Request $request)
    {
        $admin = (new User())
            ->setUsername('admin')
            ->setNewPassword('admin8888')
        ;

        $form = $this->createForm(UserType::class, $admin);

        $userRepo = $this->get('user_repository');
        $userRepo->updateUserFromForm($form, $this->get('security.encoder_factory'));
        die('save');

        /* @var $authenticationUtils AuthenticationUtils */
        $authenticationUtils = $this->get('security.authentication_utils');

        $user = $this->getUser(); /* @var $user User */

        $username = false;

        if($user instanceof User) {
            $username = $user->getUsername();
            $this->logout($request);
        }

        $loginForm = $this->createForm(LoginType::class, null, [
            'method' => 'POST',
            'action' => $this->generateUrl('user_check')
            // 'csrf_protection' => false
        ]);

        return $this->render(self::LOGIN_TEMPLATE, array(
            // last username entered by the user
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'         => $authenticationUtils->getLastAuthenticationError(),
            'username'      => $username,
            'loginForm'     => $loginForm
        ));
    }

    /**
     * @param Request $request
     */
    public function logout(Request $request)
    {
        $this->container->get('security.token_storage')->setToken(null);

        $session = $request->getSession();
        $session->invalidate(0);
        $session->clear();
    }
}