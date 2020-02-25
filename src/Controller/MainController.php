<?php


namespace App\Controller;


use App\Entity\Danse;
use App\Entity\PowerPoint;
use App\Form\DanseType;
use App\Form\PowerPointType;
use App\Service\OrderObject;
use App\Service\PowerPointGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    /**
     * @Route("/", name="main")
     * @param PowerPointGenerator $powerPointGenerator
     * @param Request $request
     * @return Response
     */
    public function main(PowerPointGenerator $powerPointGenerator, OrderObject $orderObject, Request $request)
    {
        //Déclaration entitée
        $powerpoint = new PowerPoint();

        //Construction formulaire
        $form = $this->createForm(PowerPointType::class, $powerpoint);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $powerpoint = $form->getData();

            //Ordonner par position_playlist
            $dansesOrdonner = $orderObject->ordrePostionPlaylist($powerpoint->getDanses());

            //Appel du service de création du fichier Powerpoint
            $powerPointGenerator->main($dansesOrdonner);

            //Récupération du tableau danses $powerpoint->getDanses();

            // Test génération powerpoint

        }

        //Retourne la vue non formulaire
        return $this->render('powerPoint/index.html.twig', [
            'form'=>$form->createView(),

        ]);

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
     * @Route("/flashes", name="app_test")
     */
    public function test()
    {
        $this->addFlash('danger', 'Email iconnu');
        return $this->redirectToRoute('app_login');
    }
}