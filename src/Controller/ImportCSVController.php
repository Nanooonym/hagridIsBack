<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\ImportCSV;
use App\Entity\Participant;
use App\Form\ImportCSVType;
use App\Form\ParticipantType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin/importCSV")
 */
class ImportCSVController extends AbstractController
{
    /**
     * @Route("/", name="importCSV", methods={"GET", "POST"})
     */
    public function import(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em)
    {
        $importCSV = new ImportCSV();
        $form = $this->createForm(ImportCSVType::class, $importCSV);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            try {

            $file = $form->get('file')->getData();

            $string = str_replace('"', '', file_get_contents($file));
                $datas = explode(',', $string);


                $campusRepo = $this->getDoctrine()->getRepository(Campus::class);
                $participantRepo = $this->getDoctrine()->getRepository(Participant::class);

                $i = 0;
                $entity = 0;
                do {
                    $i = $i + 1;                                 //Blank offset
                    $participant = new Participant();

                    $campus = $campusRepo->find($datas[$i]);
                    $participant->setCampus($campus);
                    $i++;

                    $participant->setFilename($datas[$i]);
                    $i++;
                    $participant->setPseudo($datas[$i]);
                    $i++;
                    $participant->setNom($datas[$i]);
                    $i++;
                    $participant->setPrenom($datas[$i]);
                    $i++;
                    $participant->setTelephone($datas[$i]);
                    $i++;
                    $participant->setMail($datas[$i]);
                    $i++;

                    $motDePasse = $passwordEncoder->encodePassword($participant, $datas[$i]);
                    $participant->setMotDePasse($motDePasse);
                    $i++;

/*                    $role = '"' . $datas[$i] . '"';*/
                    $participant->setRoles($role);

                    $i++;

                    if ($datas[$i] == 1) {
                        $participant->setActif(true);
                        $i++;
                    } else {
                        $participant->setActif(false);
                        $i++;
                    }

                    $participant->getUpdatedAt($datas[$i]);
                    $i++;

                    $participantExist = $participantRepo->findOneBy(array('pseudo' => $participant->getPseudo()));

                    if (!$participantExist) {
                        $em->persist($participant);
                        $em->flush();
                        $entity++;
                    }

                } while (count($datas) > ($i + 12));         //Check if next entity is full (10 datas + 2 blanks)


                $this->addFlash('success', $entity . ' participants ont été ajoutés en base de données.');

            } catch (Exception $e) {
                $this->addFlash('error', 'Erreur dans la prise en charge du fichier');
            } finally {
                return $this->redirectToRoute('sortie_index');
            }
        }


        return $this->render('importCSV/importCsv.html.twig', [
            "form" => $form->createView(),
            'importCSV' => $importCSV
        ]);
    }
}