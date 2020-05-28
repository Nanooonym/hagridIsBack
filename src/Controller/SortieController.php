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

        if ($form->get('newVille')->isClicked()) {

            $this->container->get('session')->set('user', true);
/*            $this->container->get('session')->set('sortie', $sortie);*/
            return $this->redirectToRoute('ville_new');

        } elseif ($form->get('newLieu')->isClicked()) {

            $this->container->get('session')->set('user', true);
/*            $this->container->get('session')->set('sortie', $sortie);*/
            return $this->redirectToRoute('lieu_new');
        }

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

            if($sortie->getDuree() == null){
                $sortie->setDuree(0);
            }

            $entityManager->persist($etat);
            $sortie->setEtat($etat);

            $entityManager->persist($sortie);
            $entityManager->flush();

            if($submit == "enregistrer"){
                $this->addFlash('success', 'Votre sortie "' . $sortie->getNom() . '" est maintenant enregistrée');
            }else if($submit == "publier"){
                $this->addFlash('success', 'Votre sortie "' . $sortie->getNom() . '" est maintenant publiée');
            }

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

        if ($form->get('newVille')->isClicked()) {

               $this->getDoctrine()->getManager()->flush();
               $this->container->get('session')->set('sortie', $sortie);
               return $this->redirectToRoute('ville_new');

           } elseif ($form->get('newLieu')->isClicked()) {

               $this->container->get('session')->set('sortie', $sortie);
               return $this->redirectToRoute('lieu_new');
           }

        if ($form->isSubmitted() && $form->isValid()) {

            $etat = $sortie->getEtat()->getLibelle();

            if($request->get('button') == "publier" && $etat == 'En création'){
                $etat = "Ouvert";
                $sortie->getEtat()->setLibelle($etat);
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Votre sortie "' . $sortie->getNom() . '" est maintenant publiée');
            }else if($request->get('button') == "enregistrer"){
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Votre sortie "' . $sortie->getNom() . '" a été modifiée');
            }else if($request->get('button' == "annuler")){
                $sortie->getEtat()->setLibelle("Annulée");
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Votre sortie "' . $sortie->getNom() . '" est maintenant annulée');
            }

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
            $this->addFlash('success', 'Votre sortie "' . $sortie->getNom() . '" est a été supprimée');
        }

        return $this->redirectToRoute('sortie_index');
    }

    /**
     * @Route("/{id}/inscrire", name="sortie_inscrire", methods={"GET","POST"})
     */
    public function inscrire(EntityManagerInterface $em, Request $request, Sortie $sortie, $id): Response
    {

        $date = new \DateTime("now");
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);
        $user = $this->getUser();

        if ($sortie->getDateCloture() > $date
            && count($sortie->getParticipants()) < $sortie->getNbInscriptionsMax()
            && $sortie->getOrganisateur() != $user) {

            $sortie->addParticipant($user);
            $em->flush();
            $this->addFlash('success', 'Vous êtes maintenant inscrit(e) à la sortie "' . $sortie->getNom() . '"');
        }
        return $this->redirectToRoute('sortie_index');

    }


    /**
     * @Route("/{id}/desister", name="sortie_desister", methods={"GET","POST"})
     */
    public function desister(EntityManagerInterface $em, Request $request, Sortie $sortie, $id): Response
    {

        $date = new \DateTime("now");
        $sortieRepo = $this->getDoctrine()->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);
        $user = $this->getUser();

        if ($sortie->getDateCloture() > $date
            && count($sortie->getParticipants()) < $sortie->getNbInscriptionsMax()
            && $sortie->getOrganisateur() != $user) {

            $sortie->removeParticipant($user);
            $em->flush();
            $this->addFlash('success', 'Vous n\'êtes plus inscrit(e) à la sortie "' . $sortie->getNom() . '"');
        }
        return $this->redirectToRoute('sortie_index');

    }

    /**
     * @Route("/{id}/publier", name="sortie_publier", methods={"GET","POST"})
     */
    public function publier(Request $request, Sortie $sortie): Response
    {
        $etat = $sortie->getEtat();
        if($sortie->getEtat()->getLibelle() == 'En création'){
            $etat->setLibelle('Ouverte');
            $sortie->setEtat($etat);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Votre sortie "' . $sortie->getNom() . '" est maintenant publiée');
        }
            return $this->redirectToRoute('sortie_index');
    }

    public function updateEtat (EntityManagerInterface $em, Sortie $sortie) {
        $etat = $sortie->getEtat();
        $dateDebut = new \DateTime($sortie->getDateDebut());
        $dateCloture = $sortie->getDateCloture();
        $dateNow = new \DateTime("now");

        $duree = 0;
        if($sortie->getDuree()){
            $duree = $sortie->getDuree();
        }
        $dateFin = $dateDebut->add(new \DateInterval('PT' . $duree . 'M'));

        if($dateCloture > $dateNow && $dateDebut < $dateNow){
            $this->etat = "Clôturée";
            $sortie->setEtat($etat);
            $em->flush();
        }
        if($dateDebut > $dateNow){
            $this->etat = "Activité en cours";
            $sortie->setEtat($etat);
            $em->flush();
        }
        if($dateFin > $dateNow){
            $this->etat = "Passée";
            $sortie->setEtat($etat);
            $em->flush();
        }
    }

    /**
     * @Route("/{id}/annuler", name="annuler", methods={"GET","POST"})
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


        return $this->render('sortie/annulerSortie.html.twig', ["sortie" => $sortie, "form"=> $form->createView()]);
    }
}