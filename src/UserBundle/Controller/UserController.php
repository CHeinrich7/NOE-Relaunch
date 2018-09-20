<?php

namespace UserBundle\Controller;

use UserBundle\Entity\Role;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;
use UserBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    const INDEX_TEMPLATE = 'UserBundle:User:index.html.php';
    const EDIT_TEMPLATE = 'UserBundle:User:edit.html.php';

    /**
     * @return Response
     */
    public function indexAction()
    {
        if(!$this->isGranted(Role::ROLE_ADMIN)) {
            return $this->redirectToRoute('user_edit', ['user' => $this->getUser()->getId()]);
        }

        $users = $this->get('user_repository')->findAllByRoles(array());

        return $this->render(self::INDEX_TEMPLATE, array(
            'currentUser'   => $this->getUser(),
            'users'         => $users
        ));
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse|Response
     *
     * @throws \Exception
     */
    public function newAction(Request $request)
    {
        $user = new User();

        return $this->editUser($request, $user, $this->getUser(), 'user_create', true, false);
    }


    /**
     * @param Request $request
     * @param User    $user
     *
     * @return JsonResponse|Response
     *
     * @throws \Exception
     */
    public function editAction(Request $request, User $user, $save)
    {
        $currentUser = $this->getUser();
        return $this->editUser($request, $user, $currentUser, 'user_edit', false, ($save == 1));
    }

    /**
     * @param Request   $request
     * @param User      $user
     * @param User      $currentUser
     * @param string    $action
     * @param boolean   $newUser
     * @param boolean   $save
     *
     * @return JsonResponse|Response
     *
     * @throws \Exception
     */
    protected function editUser(Request $request, User $user, $currentUser, $action, $newUser, $save)
    {
        /*$entitySecurityService = $this->get('entity.security.service');

        if(!$entitySecurityService->isEntityGrantedWithCurrentRights($user)) {
            throw new \Exception('Unerlaubtes Territorium');
        }*/


        $form = $this->createForm(UserType::class, $user, array(
            'method' => 'POST',
            'action' => $this->generateUrl($action, ['user' => $user->getId()]),
        ));

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $userRepo = $this->get('user_repository');
            $userRepo->updateUserFromForm($form, $this->get('security.encoder_factory'));

            return $this->redirectToRoute('user_edit', ['user' => $user->getId(), 'save' => 1]);
        }

        // $errors  = $form->getErrors(true);
        // $message = implode(',', explode('ERROR:', (string)$errors));

        $data = array(
            'userForm'          => $form,
            // 'hasError'          => strlen($message),
            'user'              => $user,
            'currentUser'       => $currentUser,
            'newUser'           => $newUser,
//            'insertOldPassword' => $entitySecurityService->isEntityGranted($user),
            'save'              => $save
        );

        return $this->returnResponse($request, self::EDIT_TEMPLATE, $data);
    }

    /**
     * @param Request $request
     * @param string  $template
     * @param array   $data
     *
     * @return JsonResponse|Response
     */
    private function returnResponse(Request $request, $template, $data = array())
    {
        $response = $this->render($template, $data);

        if($request->isXmlHttpRequest()) {
            return new JsonResponse(
                array(
                    'success'   => true,
                    'data'      => $response
                )
            );
        }

        return $response;
    }
}