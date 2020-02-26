<?php


namespace App\Controller;


use App\Entity\Danse;
use App\Entity\PowerPoint;
use App\Form\DanseType;
use App\Form\PowerPointType;
use App\Service\OrderObject;
use App\Service\PowerPointGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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

        //Construction formulaire et modification du texte bouton en fonction du role utilisateur
        if($this->isGranted('ROLE_USER'))
        {
            $form = $this->createForm(PowerPointType::class, $powerpoint, [
                'name_button'=> 'Enregistrer et générer'
            ]);
        }
        else { $form = $this->createForm(PowerPointType::class, $powerpoint); }

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $powerpoint = $form->getData();

            //Ordonner par position_playlist
            $dansesOrdonner = $orderObject->ordrePostionPlaylist($powerpoint->getDanses());

            //Enregistrement dans bdd pour utilisateur connectés
           /* if($this->isGranted('ROLE_USER')){
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
            }*/



            //Appel du service de création du fichier Powerpoint
            $powerPointGenerator->main($dansesOrdonner);

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
     * @Route("/test", name="app_test")
     */
    public function test()
    {

      return new Response("<body> C'est persisté </body>");
    }
}