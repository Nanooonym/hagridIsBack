<?php

namespace App\Controller;

use App\Entity\Filter;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\FilterType;
use App\Form\SortieType;
use App\Form\VilleType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ville")
 */
class VilleController extends AbstractController
{
    /**
     * @Route("/admin", name="ville_index", methods={"GET", "POST"})
     * @param VilleRepository $villeRepository
     * @param Request $request
     * @return Response
     */
    public function index(VilleRepository $villeRepository, Request $request): Response
    {
        $ville = new Ville();
        $filter = new Filter();
        $form = $this->createForm(VilleType::class, $ville);
        $formFilter = $this->createForm(FilterType::class, $filter);
        $form->handleRequest($request);
        $formFilter->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ville);
            $entityManager->flush();

            return $this->redirectToRoute('ville_index');
        }
        return $this->render('ville/index.html.twig', [
            'villes' => $villeRepository->findByName($filter),
            'formFilter' => $formFilter->createView(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="ville_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $ville = new Ville();
        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ville);
            $entityManager->flush();


            //Création de ville en passant par la création de sortie
            if($this->container->get('session')->get('user')){
                $this->container->get('session')->remove('user');

                if($request->get('creerLieu')){
                    return $this->redirectToRoute('lieu_new');
                }
                return $this->redirectToRoute('sortie_new');
            }

            return $this->redirectToRoute('ville_index');
        }

        return $this->render('ville/new.html.twig', [
            'ville' => $ville,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ville_show", methods={"GET"})
     */
    public function show(Ville $ville): Response
    {
        return $this->render('ville/show.html.twig', [
            'ville' => $ville,
        ]);
    }

    /**
     * @Route("/admin/{id}/edit", name="ville_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Ville $ville
     * @param EntityManagerInterface $em
     * @param $id
     * @return Response
     */
    public function edit(Request $request, Ville $ville, EntityManagerInterface $em, $id): Response
    {
        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ville_index');
        }

        $ville = $em->getRepository(Ville::class)->find($id);
        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
            $this->addFlash('success', 'Bien modifié avec succès');
            return $this->redirectToRoute('ville_index');
        }

        return $this->render('ville/edit.html.twig', [
            'ville' => $ville,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/{id}", name="ville_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Ville $ville): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ville->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ville);
            $entityManager->flush();
            $this->addFlash('success', 'Bien supprimé avec succès');
        }

        return $this->redirectToRoute('ville_index');
    }
}
