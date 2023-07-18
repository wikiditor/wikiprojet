<?php

namespace App\Controller;

// Importation des différentes classes utilisées dans le contrôleur.
use App\Document\User;
use App\Repository\UserRepository;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ODM\MongoDB\DocumentManager as DocumentManager;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    // Injection de l'instance de EmailVerifier dans le contrôleur.
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    // Route pour l'inscription d'un nouvel utilisateur.
    #[Route('/inscription', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, DocumentManager $dm, UserRepository $userRepository): Response
    {
        // Création d'une nouvelle instance d'User et de son formulaire associé.
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Si le formulaire est soumis et valide, le mot de passe de l'utilisateur est encodé et persisté dans la base de données.
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Ajout de l'utilisateur à la base de données.
            $userRepository->addUser($user);
           

            // Génération d'un URL signé et envoi d'un email de confirmation à l'utilisateur.
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('wikiditor.lapasserelle@gmail.com', 'Wikiditor'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            
            // Redirection vers la page de connexion.
            return $this->redirectToRoute('app_login');
        }

        // Affichage du formulaire d'inscription.
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    // Route pour la vérification de l'email de l'utilisateur.
    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        // Vérification que l'utilisateur est bien authentifié.
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Validation du lien de confirmation d'email. En cas d'erreur, une exception est levée.
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            // Si une exception est levée, affichage d'un message d'erreur.
            $this->addFlash('verify_email_error', $exception->getReason());

            // Redirection vers la page d'inscription.
            return $this->redirectToRoute('app_register');
        }

        // Si tout se passe bien, affichage d'un message de succès.
        $this->addFlash('success', 'Ton adresse email a été vérifiée.');

        // Redirection vers la page d'inscription.
        return $this->redirectToRoute('app_register');
    }
}
