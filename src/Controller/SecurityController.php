<?php

namespace App\Controller;

use App\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\User;

class SecurityController extends Controller
{
    /**
     * @Route(
     *     path="/login",
     *     name="login"
     * )
     */
    public function loginAction(AuthenticationUtils $authUtils)
    {
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('Security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * @Route(
     *     path="/register",
     *     name="register"
     * )
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // FIXME: Instancier le formulaire et à la soumission enregistrer le user.
        // La vue à rendre : Security/register.html.twig
        // create a task and give it some dummy data for this example
        $em = $this->getDoctrine()->getManager();
        $user = new User();
        $form = $this->createFormBuilder($user)
            ->add('firstname')
            ->add('lastname')
            ->add("email")
            ->add('birthday')
            ->add('password')
            ->add('save', SubmitType::class, array('label' => 'Create User'))
            ->getForm();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //$user->setCreatedAt(new \Datetime('now'));
            $em->persist($user);
            $em->flush();
        }

        return $this->render("Security/register.html.twig", array('form' => $form->createView()));
    }
}
