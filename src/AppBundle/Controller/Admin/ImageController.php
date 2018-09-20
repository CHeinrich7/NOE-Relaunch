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

use AppBundle\Entity\Image;
use AppBundle\Form\ImageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin/image")
 * @Security("has_role('ROLE_ADMIN')")
 */
class ImageController extends Controller
{
    /**
     * @Route("/", name="admin_index")
     * @Route("/", name="admin_image_index")
     * @Method("GET")
     *
     * @return Response
     */
    public function indexAction() : Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $images = $entityManager->getRepository(Image::class)->findAll();

        return $this->render('admin/image/index.html.twig', ['images' => $images]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     *
     * @Route("/new", name="admin_image_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) : Response
    {
        $image = new Image();
        $image->setAuthorEmail($this->getUser()->getEmail());

        // See http://symfony.com/doc/current/book/forms.html#submitting-forms-with-multiple-buttons
        $form = $this->createForm(ImageType::class, $image)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See http://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($image);

            /** @var UploadedFile $file */
            $file = $image->getFile();

            if($file->getPath() === ini_get('upload_tmp_dir')) {

                $fileName = md5(uniqid()).'.'.$file->guessExtension();

                $nfs = implode(
                    DIRECTORY_SEPARATOR,
                    array(
                        $this->get('kernel')->getRootDir(),
                        '..',
                        'nfs',
                        'upload'
                    )
                );

                $file = $file->move($nfs, $fileName);
                $image->setFile($file);

            }

            $entityManager->flush();

            $this->addFlash('success', 'image.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('admin_image_new');
            }

            return $this->redirectToRoute('admin_image_index');
        }

        return $this->render('admin/image/new.html.twig', [
            'image'     => $image,
            'form'      => $form->createView(),
            'submitted' => $form->isSubmitted()
        ]);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, name="admin_image_show")
     * @Method("GET")
     *
     * @param Image $image
     *
     * @return Response
     */
    public function showAction(Image $image) : Response
    {
        if (null === $this->getUser() || !$image->isAuthor($this->getUser())) {
            throw $this->createAccessDeniedException('Images can only be shown to their authors.');
        }

        $deleteForm = $this->createDeleteForm($image);

        return $this->render('admin/image/show.html.twig', [
            'image'        => $image,
            'delete_form'  => $deleteForm->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="admin_image_edit")
     * @Method({"GET", "POST"})
     *
     * @param Image $image
     * @param Request $request
     *
     * @return Response
     */
    public function editAction(Image $image, Request $request) : Response
    {
        if (null === $this->getUser() || !$image->isAuthor($this->getUser())) {
            throw $this->createAccessDeniedException('Images can only be edited by their authors.');
        }

        $entityManager = $this->getDoctrine()->getManager();

        $editForm = $this->createForm(ImageType::class, $image);
        $deleteForm = $this->createDeleteForm($image);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $image->setSlug($this->get('slugger')->slugify($image->getTitle()));
            $entityManager->flush();

            $this->addFlash('success', 'image.updated_successfully');

            return $this->redirectToRoute('admin_image_edit', ['id' => $image->getId()]);
        }

        return $this->render('admin/image/edit.html.twig', [
            'image'        => $image,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_image_delete")
     * @Method("DELETE")
     * @Security("image.isAuthor(user)")
     *
     * @param Request $request
     * @param Image $image
     *
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, Image $image) : RedirectResponse
    {
        $form = $this->createDeleteForm($image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->remove($image);
            $entityManager->flush();

            $this->addFlash('success', 'image.deleted_successfully');
        }

        return $this->redirectToRoute('admin_image_index');
    }

    /**
     * @param Image $image The image object
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Image $image)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_image_delete', ['id' => $image->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
