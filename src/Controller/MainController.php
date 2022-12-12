<?php


namespace App\Controller;

use App\Entity\Danse;
use App\Entity\PowerPoint;
use App\Form\PowerPointType;
use App\Service\OrderObject;
use App\Service\PowerPointGenerator;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    public function __construct(private RequestStack $requestStack, private $session = null)
    {
        $this->session = $this->requestStack->getSession();
    }

    /**
     * @param PowerPointGenerator $powerPointGenerator
     * @param Request $request
     * @return Response
     */
    #[Route("/", name: "main")]
    public function main(PowerPointGenerator $powerPointGenerator, OrderObject $orderObject, SessionInterface $session, Request $request)
    {
        //On regarde si un powerpoint existe dans la session
        $powerpoint = $this->session->get('powerpointDanse');
        $powerpoint = $powerpoint ? $powerpoint : new PowerPoint();

        //Construction formulaire et modification du texte bouton en fonction du role utilisateur
        if ($this->isGranted('ROLE_USER')) {
            $form = $this->createForm(PowerPointType::class, $powerpoint, [
                'name_button' => 'Enregistrer et générer',
                'disabled_button_generate' => false,
            ]);
        } else {
            $form = $this->createForm(PowerPointType::class, $powerpoint);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $powerpoint = $form->getData();

            //Ordonner par position_playlist
            $dansesOrdonner = $orderObject->main($powerpoint->getDanses());


            //si Suppression de toutes les danses
            if ($form->getClickedButton() && 'resetDanse' === $form->getClickedButton()->getName()) {
                $this->session->remove('powerpointDanse');
                return $this->redirectToRoute('main');
            }


            //Débugage 
            dump($powerpoint->getBackgroundSlides());
            dump($powerpoint->getBackgroundSlidesImageFile());

            throw new Exception('Débogage volontaire');


            //Enregistrement dans bdd pour utilisateur connectés
            if ($this->isGranted('ROLE_USER')) {
                //Récupération de l'entity manager
                $em = $this->getDoctrine()->getManager();

                //Récupération de l'utilisateur + assignation dans la query
                $user = $this->getUser();
                $powerpoint->setUser($user);

                //Ajout du powerpoint dans l'objet danse
                foreach ($powerpoint->getDanses() as $danse) {
                    $danse->setPowerpoint($powerpoint);
                }

                $powerpoint->setUpdatedAt(new \DateTime());

                //Persistance et flush
                $em->persist($powerpoint);
                $em->flush();
            }

            //Si bouton enregistrer cliqué, on redigire apres la mise en bdd et avant la génération powerpoint
            if ($form->getClickedButton() && 'onlySave' === $form->getClickedButton()->getName()) {
                $this->addFlash('success', 'Votre powerpoint a bien été enregistré');

                return $this->redirectToRoute('all_powerpoint_user');
            }

            //Appel du service de création du fichier Powerpoint
            $urlPPTX = $powerPointGenerator->main($dansesOrdonner, $powerpoint);

            $this->addFlash('success', 'Le fichier a bien été généré !');

            $session->set('urlPowerpoint', $urlPPTX);

            return $this->redirectToRoute('powerpoint_download');
        }

        //Retourne la vue non formulaire
        return $this->render('powerPoint/indexPowerpoint.html.twig', [
            'form' => $form->createView(),
            'page' => 'home',
        ]);
    }

    #[Route("/recuperation-danse", name: "recuperation_danse")]
    public function recuperationDanse(Request $request)
    {
        // 1 - Génére form textArea
        $defaultData = ['message' => 'Mon message'];
        $form = $this->createFormBuilder($defaultData)
            ->add('listeDanse', TextareaType::class, ['attr' => ['rows' => 16]])
            ->add('save', SubmitType::class, ['label' => 'Générer'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // 2 - Récupére les données dans un tableau
            // Explode, découpe la chaîne de caractére en tableau au retour à la ligne
            // Trim enleve les caractére du type \r du tableau
            $danses = array_map('trim', explode("\n", $data['listeDanse']));

            // 3 - On génére l'objet powerpoint
            $powerpoint = new PowerPoint();

            foreach ($danses as $position => $danse) {

                //On crée une nouvelle danse
                $dansePowerpoint = new Danse();
                $dansePowerpoint->setName($danse);
                $dansePowerpoint->setPositionPlaylist($position + 1);

                //On ajoute la danse au powerpoint
                $powerpoint->addDanse($dansePowerpoint);
            }


            // 4 - On enregistre le powerpoint dans la session
            $this->session->set('powerpointDanse', $powerpoint);

            // 5 - On redirige sur la page d'accueil
            return $this->redirectToRoute('main');
        }

        return $this->renderForm('powerPoint/recuperationDanse.html.twig', [
            'form' => $form
        ]);
    }


    #[Route("/downloadPowerpoint", name: "powerpoint_download")]
    public function powerpointDowload(SessionInterface $session)
    {
        $urlPowerpoint = $session->get('urlPowerpoint');

        return $this->render('powerPoint/downloadPowerpoint.html.twig', ['urlPowerpoint' => $urlPowerpoint]);
    }

    #[Route("/mentions-legales", name: "app_mentions_legales")]
    public function mentionsLegales()
    {
        return $this->render('powerPoint/mentionsLegales.html.twig');
    }
}