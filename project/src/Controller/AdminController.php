<?php

namespace App\Controller;

use App\Entity\Guest;
use App\Entity\GuestPlusOne;
use App\Entity\User;
use App\Form\RechercheRapideType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AdminController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/admin", name="admin")
     * @IsGranted("ROLE_ADMIN")
     */
    public function adminDashboard(Request $request): Response
    {
        $users = $this->em->getRepository(User::class)->findAll();
        $guestsGroupes = $this->em->getRepository(Guest::class)->findAll();
        $guests = $this->em->getRepository(GuestPlusOne::class)->findAll();

        $form = $this->createForm(RechercheRapideType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            return $this->redirectToRoute('result_search', ['data' => $data['recherche']]);
        }

        return $this->render('admin/index.html.twig', [
            'users' => $users,
            'guestsGroupes' => $guestsGroupes,
            'guests' => $guests,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/recherche/{data}", name="result_search")
     * @IsGranted("ROLE_ADMIN")
     */
    public function resultSearch(Request $request)
    {
        $query = $request->get('data');
        $guests = $this->em->getRepository(Guest::class)->getGuestsFromResearch($query);
        $guestsPlusOne = $this->em->getRepository(GuestPlusOne::class)->getGuestsPlusOneFromResearch($query);

        return $this->render('guest/result_search.html.twig', [
            'guests' => $guests,
            'guestsPlusOne' => $guestsPlusOne,
            'query' => $query
        ]);
    }
}
