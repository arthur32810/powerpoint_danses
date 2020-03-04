<?php


namespace App\Controller;

use App\Entity\PowerPoint;
use App\Form\PowerPointType;
use App\Service\OrderObject;
use App\Service\PowerPointGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    /**
     * @Route("/", name="main")
     * @param PowerPointGenerator $powerPointGenerator
     * @param Request $request
     * @return Response
     */
    public function main(PowerPointGenerator $powerPointGenerator, OrderObject $orderObject, SessionInterface $session, Request $request)
    {
        //Déclaration entitée
        $powerpoint = new PowerPoint();

        //Construction formulaire et modification du texte bouton en fonction du role utilisateur
        if($this->isGranted('ROLE_USER'))
        {
            $form = $this->createForm(PowerPointType::class, $powerpoint, [
                'name_button'=> 'Enregistrer et générer',
                'disabled_button_generate'=>false,
            ]);
        }
        else { $form = $this->createForm(PowerPointType::class, $powerpoint); }

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $powerpoint = $form->getData();

            //Ordonner par position_playlist
            $dansesOrdonner = $orderObject->main($powerpoint->getDanses());


            //Enregistrement dans bdd pour utilisateur connectés
            if($this->isGranted('ROLE_USER')){
                //Récupération de l'entity manager
                $em = $this->getDoctrine()->getManager();

                //Récupération de l'utilisateur + assignation dans la query
                $user = $this->getUser();
                $powerpoint->setUser($user);

                 //Ajout du powerpoint dans l'objet danse
                foreach($powerpoint->getDanses() as $danse)
                {
                    $danse->setPowerpoint($powerpoint);
                }

                //Persistance et flush
                $em->persist($powerpoint);
                $em->flush();
            }

            //Si bouton enregistrer cliqué, on redigire apres la mise en bdd et avant la génération powerpoint
            if($form->getClickedButton() && 'onlySave' === $form->getClickedButton()->getName())
            {
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
        return $this->render('powerPoint/index.html.twig', [
            'form'=>$form->createView(),
        ]);

    }

    /**
     * @Route("/downloadPowerpoint", name="powerpoint_download")
     */
    public function powerpointDowload(SessionInterface $session)
    {
        $urlPowerpoint = $session->get('urlPowerpoint');

        return $this->render('powerPoint/downloadPowerpoint.html.twig', ['urlPowerpoint'=>$urlPowerpoint]);
    }

    /**
     * @Route("/service", name="app_service")
     * @param PowerPointGenerator $powerPointGenerator
     * @return Response
     */
    public function testService(PowerPointGenerator $powerPointGenerator){
        return new Response($powerPointGenerator->main());
    }

    /**
     * @Route("/admin", name="app_admin")
     * @return Response
     */
    public function admin():Response
    {
        return new Response('<body> C\'est une page d\'admin pour tester</body>');
    }

    /**
     * @Route("/test", name="app_test")
     */
    public function test()
    {

      return new Response("<body> C'est persisté </body>");
    }
}