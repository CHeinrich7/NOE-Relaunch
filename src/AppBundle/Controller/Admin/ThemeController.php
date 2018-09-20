<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Theme;
use AppBundle\Form\ThemeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin/theme")
 * @Security("has_role('ROLE_ADMIN')")
 */
class ThemeController extends Controller
{
    /**
     * @Route("/", name="admin_index")
     * @Route("/", name="admin_theme_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $themes = $entityManager->getRepository(Theme::class)->findAll();

        return $this->render('admin/theme/index.html.twig', ['themes' => $themes]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     *
     * @Route("/new", name="admin_theme_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $theme = new Theme();
        $theme->setAuthorEmail($this->getUser()->getEmail());

        // See http://symfony.com/doc/current/book/forms.html#submitting-forms-with-multiple-buttons
        $form = $this->createForm(ThemeType::class, $theme)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See http://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($theme);

            $entityManager->flush();

            $this->addFlash('success', 'theme.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('admin_theme_new');
            }

            return $this->redirectToRoute('admin_theme_index');
        }

        return $this->render('admin/theme/new.html.twig', [
            'theme' => $theme,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, name="admin_theme_show")
     * @Method("GET")
     */
    public function showAction(Theme $theme)
    {
        if (null === $this->getUser() || !$theme->isAuthor($this->getUser())) {
            throw $this->createAccessDeniedException('Theme can only be shown to their authors.');
        }

        $deleteForm = $this->createDeleteForm($theme);

        return $this->render('admin/theme/show.html.twig', [
            'theme'        => $theme,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="admin_theme_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Theme $theme, Request $request)
    {
        if (null === $this->getUser() || !$theme->isAuthor($this->getUser())) {
            throw $this->createAccessDeniedException('Themes can only be edited by their authors.');
        }

        $entityManager = $this->getDoctrine()->getManager();

        $editForm = $this->createForm(ThemeType::class, $theme);
        $deleteForm = $this->createDeleteForm($theme);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'theme.updated_successfully');

            return $this->redirectToRoute('admin_theme_edit', ['id' => $theme->getId()]);
        }

        return $this->render('admin/theme/edit.html.twig', [
            'theme'        => $theme,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_theme_delete")
     * @Method("DELETE")
     * @Security("theme.isAuthor(user)")
     */
    public function deleteAction(Request $request, Theme $theme)
    {
        $form = $this->createDeleteForm($theme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->remove($theme);
            $entityManager->flush();

            $this->addFlash('success', 'theme.deleted_successfully');
        }

        return $this->redirectToRoute('admin_theme_index');
    }

    /**
     * @param Theme $theme The theme object
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Theme $theme)
    {
        if($theme->getInUse()) {
            throw new \Exception('Can not delete active Theme');
        }
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_theme_delete', ['id' => $theme->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
