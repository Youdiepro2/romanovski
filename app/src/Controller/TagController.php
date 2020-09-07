<?php
/**
 * Tag controller.
 */

namespace App\Controller;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TagController.
 *
 * @Route("/Tag")
 */
class TagController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request       HTTP request
     * @param \App\Repository\TagRepository             $TagRepository Tag repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator     Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="Tag_index",
     * )
     */
    public function index(Request $request, TagRepository $TagRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $TagRepository->queryAll(),
            $request->query->getInt('page', 1),
            TagRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'Tag/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param \App\Entity\Tag $Tag Tag entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="Tag_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(Tag $Tag): Response
    {
        return $this->render(
            'Tag/show.html.twig',
            ['Tag' => $Tag]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request       HTTP request
     * @param \App\Entity\Tag                           $Tag           Tag entity
     * @param \App\Repository\TagRepository             $TagRepository Tag repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="Tag_edit",
     * )
     */
    public function edit(Request $request, Tag $Tag, TagRepository $TagRepository): Response
    {
        $form = $this->createForm(TagType::class, $Tag, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $Tag->setUpdatedAt(new \DateTime());
            $TagRepository->save($Tag);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('Tag_index');
        }

        return $this->render(
            'Tag/edit.html.twig',
            [
                'form' => $form->createView(),
                'Tag' => $Tag,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request       HTTP request
     * @param \App\Entity\Tag                           $Tag           Tag entity
     * @param \App\Repository\TagRepository             $TagRepository Tag repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="Tag_delete",
     * )
     */
    public function delete(Request $request, Tag $Tag, TagRepository $TagRepository): Response
    {
        $form = $this->createForm(FormType::class, $Tag, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $TagRepository->delete($Tag);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('Tag_index');
        }

        return $this->render(
            'Tag/delete.html.twig',
            [
                'form' => $form->createView(),
                'Tag' => $Tag,
            ]
        );
    }

    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request       HTTP request
     * @param \App\Repository\TagRepository             $TagRepository Tag repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="Tag_create",
     * )
     */
    public function create(Request $request, TagRepository $TagRepository): Response
    {
        $Tag = new Tag();
        $form = $this->createForm(TagType::class, $Tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $Tag->setCreatedAt(new \DateTime());
            $Tag->setUpdatedAt(new \DateTime());
            $TagRepository->save($Tag);

            return $this->redirectToRoute('Tag_index');
        }

        return $this->render(
            'Tag/create.html.twig',
            ['form' => $form->createView()]
        );
    }
}
