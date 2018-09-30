<?php
namespace ToolboxBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use ToolboxBundle\Interfaces\EntityInterface;

abstract class CrudController extends Controller
{
    const ENTITY_CLASS = null;
    const FORM_TYPE    = null;

    const ROUTE_INDEX  = null;
    const ROUTE_CREATE = null;
    const ROUTE_EDIT   = null;
    const ROUTE_DELETE = null;

    const TEMPLATE_INDEX = null;
    const TEMPLATE_EDIT  = null;
    const TEMPLATE_LIST  = null;

    public function indexAction()
    {
        $this->render(static::TEMPLATE_INDEX);
    }

    protected function getCustomFormParameters(array $formOptions = array())
    {
        return array_merge($formOptions, array());
    }

    /**
     * @param $id
     *
     * @return EntityInterface|object
     */
    protected function getEntity($id)
    {
        return $this->getRepository()->find($id);
    }

    protected function getForm($id, array $formParameters = array())
    {
        $entity = null;

        if($id) {
            $entity = $this->getEntity($id);
        }

        return $this->createForm(
            static::FORM_TYPE,
            $entity,
            $this->getCustomFormParameters($formParameters)
        );
    }

    /**
     * @param FormInterface $form
     *
     * @return RedirectResponse|Response
     */
    protected function saveForm(FormInterface $form)
    {
        /** @var EntityInterface $entity */
        $entity       = $form->getData();
        $errorMessage = null;

        try {
            if($form->isSubmitted() && $form->isValid()) {

                $this->beforeUpdateEntity($entity);

                $em = $this->getEntityManager();
                $em->persist($entity);
                $em->flush();

                return new RedirectResponse(
                    $this->generateUrl(static::ROUTE_INDEX),
                    Response::HTTP_FOUND,
                    array('crud' => 'saved')
                );
            }
        } catch (\Throwable $e) {
            $errorMessage = $e->getMessage();
        }

        return new Response($this->render(static::TEMPLATE_EDIT, array(
            'formView'      => $form->createView(),
            'entityCaption' => $entity->getCaption(),
            'hasErrors'     => (boolean)$form->getErrors(true)->count(),
            'isSubmitted'   => $form->isSubmitted(),
            'routes'        => $this->getRoutes(),
            'errorMessage'  => $errorMessage
        )));
    }

    protected function beforeUpdateEntity(EntityInterface $entity) {}

    public function createAction()
    {
        $this->editAction(null);
    }

    public function editAction($id)
    {
        $form = $this->getForm($id);

        return $this->saveForm($form);
    }

    public function deleteAction($id)
    {
        $entity  = $this->getEntity($id);
        $em      = $this->getEntityManager();
        $success = true;
        try {
            $em->remove($entity);
            $em->flush();
        } catch (\Throwable $e) {
            $success = false;
        }

        return new JsonResponse(array('success' => $success));
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->get('doctrine.orm.entity_manager');
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this
            ->getEntityManager()
            ->getRepository(static::ENTITY_CLASS)
        ;
    }

    protected function getRoutes()
    {
        return array(
            'index'  => static::ROUTE_INDEX,
            'edit'   => static::ROUTE_EDIT,
            'create' => static::ROUTE_CREATE,
            'delete' => static::ROUTE_DELETE
        );
    }
}