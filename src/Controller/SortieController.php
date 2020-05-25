<?php

namespace App\Controller;

use App\Entity\Etat;
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
     * @param EntityManagerInterface $em
     * @param SortieRepository $sortieRepository
     * @param Request $request
     * @return Response
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
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
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

            if ($submit == "enregistrer") {
                $etat->setLibelle("En création");
            } else if ($submit == "publier") {
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
     * @param EntityManagerInterface $em
     * @param Sortie $sortie
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function show(EntityManagerInterface $em, Sortie $sortie, $id, Request $request): Response
    {

        $sortie = new Sortie();
        $lieu = new Lieu();
        $codePostal = new Ville();
        $participant = new Participant();
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $participant->getPseudo();
        $sortie = $sortieRepo->find($id);
        $lieu->getRue();
        $codePostal->getCodePostal();
        $longitude = $lieu->getLongitude();
        $latitude = $lieu->getLatitude();
        $participants = $this->getDoctrine()->getRepository(Participant::class);
        $em->persist($sortie);
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
            'participants' => $participants,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/{id}/edit", name="sortie_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Sortie $sortie
     * @return Response
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
        if ($this->isCsrfTokenValid('delete' . $sortie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sortie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sortie_index');
    }


}
