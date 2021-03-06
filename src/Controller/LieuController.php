<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Filter;
use App\Entity\Lieu;
use App\Form\FilterType;
use App\Form\LieuType;
use App\Form\SortieType;
use App\Repository\LieuRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/lieu")
 */
class LieuController extends AbstractController
{
    /**
     * @Route("/admin/", name="lieu_index", methods={"GET", "POST"})
     */
    public function index(LieuRepository $lieuRepository, Request $request): Response
    {
        $lieu = new Lieu();
        $filter = new Filter();
        $form = $this->createForm(LieuType::class, $lieu);
        $formFilter = $this->createForm(FilterType::class, $filter);
        $form->handleRequest($request);
        $formFilter->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($lieu);
            $entityManager->flush();

            return $this->redirectToRoute('lieu_index');
        }

        return $this->render('lieu/index.html.twig', [
            'lieux' => $lieuRepository->findByName($filter),
            'formFilter' => $formFilter->createView(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="lieu_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $lieu = new Lieu();
        $form = $this->createForm(LieuType::class, $lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($lieu);
            $entityManager->flush();

            if($this->container->get('session')->get('user')){

                $this->container->get('session')->remove('user');
                return $this->redirectToRoute('sortie_new');
            }

            return $this->redirectToRoute('lieu_index');
        }

        return $this->render('lieu/new.html.twig', [
            'lieu' => $lieu,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="lieu_show", methods={"GET"})
     */
    public function show(Lieu $lieu): Response
    {
        return $this->render('lieu/show.html.twig', [
            'lieu' => $lieu,
        ]);
    }

    /**
     * @Route("/admin/{id}/edit", name="lieu_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Lieu $lieu): Response
    {
        $form = $this->createForm(LieuType::class, $lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('lieu_index');
        }

        return $this->render('lieu/edit.html.twig', [
            'lieu' => $lieu,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/{id}", name="lieu_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Lieu $lieu): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lieu->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            try{
                $entityManager->remove($lieu);
                $entityManager->flush();
                $this->addFlash('success', 'Bien supprimé avec succès');
            }catch (ForeignKeyConstraintViolationException $e){
                $this->addFlash('error', 'Impossible de supprimer ce lieu, il est associé à au moins une sortie');
            }
        }

        return $this->redirectToRoute('lieu_index');
    }
}
