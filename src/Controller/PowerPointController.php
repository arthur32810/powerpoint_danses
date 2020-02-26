<?php


namespace App\Controller;


use App\Entity\PowerPoint;
use App\Form\PowerPointType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PowerPointController extends AbstractController
{
    /**
     * @Route("/powerpoints", name="all_powerpoint_user")
     * @return Response
     */
    public function listPowerpointUser(){
        $repository = $this->getDoctrine()->getRepository(PowerPoint::class);

        //Récupération des powerpoint d'un utilisateur
        $powerpoints = $repository->findByUser($this->getUser());

        return $this->render('powerPoint/listPowerpointsUser.html.twig', [
            'powerpoints'=>$powerpoints,
        ]);
    }

    /**
     * @Route("/powerpoints/{id}", name="powerpoint_edit")
     */
    public function editPowerpoint(PowerPoint $powerPoint, Request $request)
    {
        if($powerPoint->getUser() == $this->getUser()){

            $newPowerpoint = new PowerPoint();

            /*foreach ($powerPoint->getDanses() as $danse){
                $newPowerpoint->
            }*/

            $form = $this->createForm(PowerPointType::class, $powerPoint, [
                'name_button'=> 'Enregistrer et générer'
            ]);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $powerPoint = $form->getData();
                var_dump($powerPoint->getDanses());
                return new Response('formulaire soumis et validé');
            }

            return $this->render('powerPoint/editPowerpoint.html.twig', [
                'form'=>$form->createView(),
            ]);

        }
        else {
            $this->addFlash('danger', "Vous avez tenté d'accéder à un powerpoint qui ne vous appartient pas");
            return $this->redirectToRoute('all_powerpoint_user');
        }

    }

}