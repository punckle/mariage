<?php

namespace App\Controller;

use App\Entity\PasswordUpdate;
use App\Entity\User;
use App\Form\EmailPasswordResetType;
use App\Form\PasswordUpdateType;
use App\Form\SettingType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin/user")
 */
class UserController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="user_delete")
     */
    public function delete(User $user): Response
    {
        $this->em->remove($user);
        $this->em->flush();

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/parametres", name="settings")
     */
    public function settings(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(SettingType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($user);
            $this->em->flush();
            $this->addFlash(
                'success',
                'Les changements ont été sauvegardés !'
            );

            return $this->redirectToRoute('settings');
        }

        return $this->render('user/settings.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/update-password", name="update_password")
     * @Security("is_granted('ROLE_USER')")
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $passwordUpdate = new PasswordUpdate();

        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $passwordUpdate->getNewPassword();
            $hash = $encoder->encodePassword($user, $newPassword);

            $user->setPassword($hash);

            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', 'Votre mot de passe a bien été modifié');

            return $this->redirectToRoute('home');
        }

        return $this->render('user/update_password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete", name="delete_account")
     * @Security("is_granted('ROLE_USER') and user === user")
     */
    public function deleteAccount(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $this->em->remove($user);
        $this->em->flush();

        $this->get('security.token_storage')->setToken(null);

        $this->addFlash('success', 'Votre compte a bien été supprimé');

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/mot_de_passe_oublié", name="forget_password")
     */
    public function forgetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder, MailerInterface $mailer) : Response
    {
        $form = $this->createForm(EmailPasswordResetType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $user = $this->em->getRepository(User::class)->findOneBy(['email' => $data['email']]);

            if (!$user) {
                $this->addFlash('warning', 'Aucun utilisateur n\'est lié à cette adresse mail');

                return $this->redirectToRoute('app_login');
            } else {
                $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz!:;,';
                $charactersLength = strlen($characters) -1;
                $password = [];
                for ($i = 0; $i < 8; $i++) {
                    $n= rand(0, $charactersLength);
                    $password[] = $characters[$n];
                }
                $user->setPassword($passwordEncoder->encodePassword($user, implode($password)));
                $this->em->persist($user);
                $this->em->flush();

                $email = (new TemplatedEmail())
                    ->from(new Address('baillet.manon@gmail.com', 'Manon Baillet'))
                    ->to(new Address($user->getEmail()))
                    ->subject('Réinitialisation de votre mot de passe')
                    ->htmlTemplate('message/reset_password.html.twig')
                    ->context(['user' => $user, 'password' => implode($password)]);

                $mailer->send($email);

                $this->addFlash('success', 'Votre nouveau mot de passe vous a été envoyé par mail');

                return $this->redirectToRoute('home');
            }
        }

        return $this->render('security/reset_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
