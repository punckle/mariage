<?php

namespace App\Controller;

use App\Entity\Guest;
use App\Entity\GuestPlusOne;
use App\Form\GuestCodeType;
use App\Form\GuestEditType;
use App\Form\GuestFormInvitationType;
use App\Form\GuestType;
use App\Repository\GuestRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GuestController extends AbstractController
{
    private $em;

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
    public function delete(Guest $guest): Response
    {
        $this->em->remove($guest);
        $this->em->flush();

        return $this->redirectToRoute('guest_index');
    }

    /**
     * @Route("/invitation", name="guest")
     */
    public function guestFormInvitation(Request $request)
    {
        return $this->render('guest/form_guest_invitation.html.twig');
    }

    /**
     * @Route("/guest/code_invitation", name="gues_code_invitation", methods={"POST"})
     */
    public function getCodeGuest(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $guest = $this->em->getRepository(Guest::class)->findOneBy(['code' => $data['code']]);

        if (!$guest->getCodeActif()) {
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
    public function saveInvitationForm(Request $request)
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
            $this->em->persist($plusOne);
        }
        $this->em->flush();
    }
}
