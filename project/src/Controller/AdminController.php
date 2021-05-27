<?php

namespace App\Controller;

use App\Entity\Guest;
use App\Entity\GuestPlusOne;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function adminDashboard(): Response
    {
        $users = $this->em->getRepository(User::class)->findAll();
        $guestsGroupes = $this->em->getRepository(Guest::class)->findAll();
        $guests = $this->em->getRepository(GuestPlusOne::class)->findAll();

        return $this->render('admin/index.html.twig', [
            'users' => $users,
            'guestsGroupes' => $guestsGroupes,
            'guests' => $guests
        ]);
    }
}
