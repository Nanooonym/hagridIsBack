<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\SortieFilter;
use App\Entity\Ville;
use App\Form\SortieFilterType;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sortie")
 */
class SortieController extends AbstractController
{
    /**
     * @Route("/", name="sortie_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $em, SortieRepository $sortieRepository, Request $request): Response
    {

        $filter = new SortieFilter();
        $form = $this->createForm(SortieFilterType::class, $filter);
        $form->handleRequest($request);

        return $this->render('sortie/index.html.twig', [
            'sorties' => $sortieRepository->findSorties($filter),
            'form' => $form->createView()
        ]);

    }


    /**
     * @Route("/idea/list", name="idea_list")
     */
    public function list(EntityManagerInterface $em, Request $request)
    {

        //$ideaForm->handleRequest($request);

        $ideaRepo = $this->getDoctrine()->getRepository(Idea::class);
        $c = $request->get('category');

        $ideas = $ideaRepo->allPublished($c['name']);
        $category = new Category();

        $ideaForm = $this->createForm(CategoryType::class, $category);

        return $this->render('idea/list.html.twig', [
            'ideas' => $ideas,
            'ideaForm' => $ideaForm->createView(),
        ]);
    }






    /**
     * @Route("/new", name="sortie_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        dump($request);

        $sortie = new Sortie();
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            die();
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('sortie_index');
        }

        return $this->render('sortie/new.html.twig', [
            'sortie' => $sortie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sortie_show", methods={"GET"})
     */
    public function show(Sortie $sortie): Response
    {
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sortie_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Sortie $sortie): Response
    {
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sortie_index');
        }

        return $this->render('sortie/edit.html.twig', [
            'sortie' => $sortie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sortie_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Sortie $sortie): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sortie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sortie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sortie_index');
    }


}
