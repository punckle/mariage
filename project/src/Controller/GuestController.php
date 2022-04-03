<?php

namespace App\Controller;

use App\Entity\Guest;
use App\Entity\GuestPlusOne;
use App\Form\GuestEditType;
use App\Form\GuestFormInvitationType;
use App\Form\GuestType;
use App\Repository\GuestRepository;
use App\Service\ExportGuestService;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class GuestController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    /**
     * @Route("/invites", name="guest_index", methods={"GET"})
     */
    public function index(GuestRepository $guestRepository): Response
    {
        return $this->render('guest/index.html.twig', [
            'guests' => $guestRepository->findAll(),
        ]);
    }

    /**
     * @Route("/nouvel_invite", name="guest_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $guest = new Guest();
        $form = $this->createForm(GuestType::class, $guest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $guest->setCodeActif(false);
            $this->em->persist($guest);
            $this->em->flush();

            return $this->redirectToRoute('guest_index');
        }

        return $this->render('guest/new.html.twig', [
            'guest' => $guest,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/invite/{id}/edit", name="guest_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Guest $guest): Response
    {
        $form = $this->createForm(GuestEditType::class, $guest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('guest_index');
        }

        return $this->render('guest/edit.html.twig', [
            'guest' => $guest,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/invite/{id}/delete", name="guest_delete")
     */
    public function deleteGuest(Guest $guest): Response
    {
        $this->em->remove($guest);
        $this->em->flush();

        return $this->redirectToRoute('guest_index');
    }

    public function deleteGuestPlusOne(GuestPlusOne $guestPlusOne): Response
    {
        $this->em->remove($guestPlusOne);
        $this->em->flush();

        return $this->redirectToRoute('all_guest');
    }

    /**
     * @Route("/invitation", name="guest")
     */
    public function guestFormInvitation(Request $request): Response
    {
        return $this->render('guest/form_guest_invitation.html.twig');
    }

    /**
     * @Route("/guest/lost_code", name="guest_lost_code", methods={"POST"})
     */
    public function lostCode(Request $request, MailerInterface $mailer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($data['name']) {
            $email = (new TemplatedEmail())
                ->from(new Address('baillet.manon@gmail.com'))
                ->to(new Address('baillet.manon@gmail.com'))
                ->subject('Code invitation au mariage perdu')
                ->htmlTemplate('message/code_perdu.html.twig')
                ->context([
                    'nom' => $data['name']
                ]);

            $mailer->send($email);

            return new JsonResponse([
                'status' => 'ok'
            ]);
        }

        return new JsonResponse([
            'status' => 'ko'
        ]);

    }

    /**
     * @Route("/guest/code_invitation", name="gues_code_invitation", methods={"POST"})
     */
    public function getCodeGuest(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $guest = $this->em->getRepository(Guest::class)->findOneBy(['code' => $data['code']]);

        if ($guest && !$guest->getCodeActif()) {
            return new JsonResponse([
                'status' => 'ok',
                'guest' => $guest->toJson()
            ]);
        } else {
            return new JsonResponse([
                'status' => 'ko'
            ]);
        }
    }

    /**
     * @Route("/guest/save_invitation", name="save_invitation_form")
     */
    public function saveInvitationForm(Request $request): JsonResponse
    {
        $form = json_decode($request->getContent(), true);
        $guest = $this->em->getRepository(Guest::class)->findOneBy(['id' => $form['guest']['id']]);
        $data = $form['form'];

        $guest->setCome($data['isPresent']);
        $guest->setCodeActif(true);

        if ($data['message']) {
            $guest->setMessage($data['message']);
        }

        $this->em->persist($guest);

        if ($data['number'] > 0) {
            $guest->setFinalNbPeople($data['number']);
            $this->createPlusOnes($data, $guest);
        } else {
            $guest->setFinalNbPeople(0);
        }
        $this->em->flush();

        $this->addFlash(
            'success',
            'Merci pour votre réponse. Les informations ont bien été prises en compte. Nous avons hâte de fêter ça avec vous !'
        );

        return new JsonResponse([
            'status' => 'ok',
            'path' => $this->generateUrl('home')
        ]);
    }

    private function createPlusOnes($data, Guest $guest)
    {
        foreach ($data['guests'] as $dataPlusOne) {
            $plusOne = new GuestPlusOne();
            $plusOne->setFirstName($dataPlusOne['firstName']);
            $plusOne->setLastName($dataPlusOne['lastName']);
            $plusOne->setApero($dataPlusOne['apero']);
            $plusOne->setDinner($dataPlusOne['dinner']);
            $plusOne->setComment($dataPlusOne['comment']);
            $plusOne->setGuest($guest);
            $plusOne->setKid($dataPlusOne['kid']);
            $this->em->persist($plusOne);
        }
        $this->em->flush();
    }

    /**
     * @Route("invite/plus_ones/{id}", name="show_plus_ones")
     */
    public function showPlusOnes(Guest $guest): Response
    {
        $plusOnes = $this->em->getRepository(GuestPlusOne::class)->findBy(['guest' => $guest->getId()]);

        return $this->render('guest/plus_ones.html.twig', [
            'guest' => $guest,
            'plusOnes' => $plusOnes
        ]);
    }

    /**
     * @Route("invite/new/plus_one/{id}", name="plus_one_new")
     */
    public function newPlusOne(Guest $guest, Request $request): Response
    {
        $plusOne = new GuestPlusOne();
        $form = $this->createForm(GuestFormInvitationType::class, $plusOne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plusOne->setGuest($guest);
            $guest->setFinalNbPeople($guest->getFinalNbPeople() + 1);
            $this->em->persist($guest);
            $this->em->persist($plusOne);
            $this->em->flush();

            $this->addFlash(
                'success',
                'L\'invité a été créé avec succès'
            );

            return $this->redirectToRoute('show_plus_ones', [
                'id' => $guest->getId()
            ]);
        }

        return $this->render('guest/new_plus_one.html.twig', [
            'form' => $form->createView(),
            'guest' => $guest
        ]);
    }

    /**
     * @Route("edit/plus_one/{id}", name="edit_plus_one")
     */
    public function editPlusOne(GuestPlusOne $guestPlusOne, Request $request): Response
    {
        $form = $this->createForm(GuestFormInvitationType::class, $guestPlusOne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('show_plus_ones', [
                'id' => $guestPlusOne->getGuest()->getId()
            ]);
        }

        return $this->render('guest/edit_plus_one.html.twig', [
            'guestPlusOne' => $guestPlusOne,
            'guest' => $guestPlusOne->getGuest(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("delete/plus_one/{id}", name="delete_plus_one")
     */
    public function deletePlusOne(GuestPlusOne $guestPlusOne): Response
    {
        $id = $guestPlusOne->getGuest()->getId();
        $this->em->remove($guestPlusOne);
        $this->em->flush();

        return $this->redirectToRoute('show_plus_ones', [
            'id' => $id
        ]);
    }

    /**
     * @Route("/all_guests", name="all_guest")
     */
    public function allInvites(): Response
    {
        $allGuests = $this->em->getRepository(GuestPlusOne::class)->findBy([], ['lastName' => 'ASC']);

        return $this->render('guest/all_guests.html.twig', [
            'allGuests' => $allGuests
        ]);
    }

    /**
     * @Route("/generer_code/{id}", name="generate_code")
     */
    public function generateCode(Guest $guest): Response
    {
        $possible = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
        $code = '';

        for ($i = 0 ; $i < 5; $i++) {
            $code .= $possible[rand(0, strlen($possible) - 1)];
        }

        $guest->setCode($code);
        $this->em->persist($guest);
        $this->em->flush();

        $this->addFlash(
            'success',
            'Le code pour ' . $guest->getFirstName() . ' ' . $guest->getLastName() . ' a bien été généré'
        );

        return $this->redirectToRoute('guest_index');
    }

    /**
     * @Route("/export_guests", name="export_guests")
     */
    public function exportGuests(ExportGuestService $exportGuestService): Response
    {
        $guests = $this->em->getRepository(GuestPlusOne::class)->findAll();
        $spreadsheet = $exportGuestService->export($guests);

        $writer = new Xlsx($spreadsheet);
        $today = new \DateTime('now');
        $fileName = 'export_invites_' . $today->format('Ymd') . '.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        if ($temp_file !== false) {
            $writer->save($temp_file);
            return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
        }

        $this->addFlash(
            'error',
            'Une erreur est survenue'
        );

        return $this->redirectToRoute('all_guest');
    }
}
