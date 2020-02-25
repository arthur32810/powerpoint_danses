<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class PasswordController extends AbstractController
{

    /**
     * @Route("/motDePassePerdu", name="app_forgotten_password")
     */
    public function forgottonPassword(Request $request, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator):Response
    {
        if($request->isMethod('POST'))
        {
            $email = $request->request->get('email');

            $em = $this->getDoctrine()->getManager();

            $user = $em->getRepository(User::class)->findOneByEmail($email);

            if($user === null)
            {
                $this->addFlash('danger', 'Email iconnu');
                return $this->redirectToRoute('main');
            }

            $token = $tokenGenerator->generateToken();

            try {
                $user->setResetToken($token);
                $em->flush();
            }
            catch(\Exception $e){
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('main');
            }

            $url = $this->generateUrl('app_reset_password',['token'=>$token], UrlGeneratorInterface::ABSOLUTE_URL);

            $message = (new \Swift_Message('Mot de passe perdu'))
                ->setFrom('noreply@presentationPowerpoint.cr32.fr')
                ->setTo($user->getEmail())
                ->setBody(
                    "Token pour reset mot de passe : ".$url,
                    'text/html'
                );

            $mailer->send($message);

            $this->addFlash('notice', 'Mail envoyé');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('security/forgotten_password.html.twig');
    }

    /**
     * @Route("/reinitialisationMotDePasse/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder){

        $form = $this->createFormBuilder()
            ->add('password', RepeatedType::class, [
                'type'=> PasswordType::class,
                'invalid_message'=> ' Les mots de passe ne sont pas identiques',
                'first_options'=>['label'=> 'Mot de passe'],
                'second_options'=>['label'=>'Confirmer le mot de passe']
            ])
            ->add('save', SubmitType::class, ['label'=>'Nouveau mot de passe'])
            ->getForm()
        ;

        if($request->isMethod('POST'))
        {
            $em = $this->getDoctrine()->getManager();

            $user = $em->getRepository(User::class)->findOneByResetToken($token);

            if($user === null)
            {
                $this->addFlash('danger', 'Token inconnu');
                return $this->redirectToRoute('main');
            }

            $user->setResetToken(null);
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));

            $em->flush();

            $this->addFlash('notice', 'Mot de passe mis à jour');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password.html.twig', ['token'=>$token, 'form'=>$form->createView()]);
    }
}
