<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Entity\SortieFilter;
use App\Form\SortieFilterType;
use App\Form\SortieType;
use App\Form\AnnulerSortieType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


/**
 * @Route("/sortie")
 */
class SortieController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

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
     * @Route("/new", name="sortie_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {

        $sortie = new Sortie();
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $sortie->setOrganisateur($this->security->getUser());
            $sortie->addParticipant($this->security->getUser());


            $etat = new Etat();
            $submit = $_POST['button'];

            if($submit == "enregistrer") {
                $etat->setLibelle("En création");
            } else if($submit == "publier"){
                $etat->setLibelle("Ouvert");
            }

            $entityManager->persist($etat);
            $sortie->setEtat($etat);

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

    /**
     * @Route("/{id}/annuler", name="annuler", methods={"GET","POST"})
     *
     */
    public function annuler(Sortie $sortie, EntityManagerInterface $em, Request $request):Response
    {
        $form = $this->createForm(AnnulerSortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sortie->getEtat()->setLibelle("Annulée");
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Votre sortie "' . $sortie->getNom() . '" est maintenant annulée');

            return $this->redirectToRoute('sortie_index');
        }


        return $this->render('sortie/annulerSortie', ["sortie" => $sortie, "form"=> $form->createView()]);
    }
}