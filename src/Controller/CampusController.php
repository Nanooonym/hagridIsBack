<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Filter;
use App\Form\CampusType;
use App\Form\FilterType;
use App\Repository\CampusRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/campus/admin")
 */
class CampusController extends AbstractController
{
    /**
     * @Route("/", name="campus_index", methods={"GET","POST"})
     * @param CampusRepository $campusRepository
     * @param Request $request
     * @return Response
     */
    public function index(CampusRepository $campusRepository, Request $request): Response
    {
        $campu = new Campus();
        $filter = new Filter();
        $form = $this->createForm(CampusType::class, $campu);
        $formFilter = $this->createForm(FilterType::class, $filter);
        $form->handleRequest($request);
        $formFilter->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($campu);
            $entityManager->flush();

            return $this->redirectToRoute('campus_index');
        }
        return $this->render('campus/index.html.twig', [
            'campuses' => $campusRepository->findByName($filter),
            'formFilter' => $formFilter->createView(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="campus_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $campu = new Campus();
        $form = $this->createForm(CampusType::class, $campu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($campu);
            $entityManager->flush();

            return $this->redirectToRoute('campus_index');
        }

        return $this->render('campus/new.html.twig', [
            'campu' => $campu,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="campus_show", methods={"GET"})
     * @param Campus $campu
     * @return Response
     */
    public function show(Campus $campu): Response
    {
        return $this->render('campus/show.html.twig', [
            'campu' => $campu,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="campus_edit", methods={"GET","POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Campus $campu
     * @param $id
     * @return Response
     */
    public function edit(Request $request, EntityManagerInterface $em, Campus $campu, $id): Response
    {
        $campu = $em->getRepository(Campus::class)->find($id);
        $form = $this->createForm(CampusType::class, $campu);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
            $this->addFlash('success', 'Bien modifié avec succès');
            return $this->redirectToRoute('campus_index');
        }

        return $this->render('campus/edit.html.twig', [
            'campu' => $campu,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="campus_delete", methods={"DELETE"})
     * @param Request $request
     * @param Campus $campu
     * @return Response
     */
    public function delete(Request $request, Campus $campu): Response
    {
        if ($this->isCsrfTokenValid('delete'.$campu->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            try{
                $entityManager->remove($campu);
                $entityManager->flush();
                $this->addFlash('success', 'Bien supprimé avec succès');
            }catch (ForeignKeyConstraintViolationException $e){
                $this->addFlash('error', 'Impossible de supprimer ce campus, il est associé à au moins une sortie ou au moins un participant');
            }


        }

        return $this->redirectToRoute('campus_index');
    }
}
