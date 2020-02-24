<?php


namespace App\Controller;


use App\Entity\Danse;
use App\Entity\PowerPoint;
use App\Form\DanseType;
use App\Form\PowerPointType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    /**
     * @Route("/", name="main")
     * @return Response
     */
    public function main(Request $request)
    {
        //Déclaration entitée
        $powerpoint = new PowerPoint();

        //Construction formulaire
        $form = $this->createForm(PowerPointType::class, $powerpoint);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $powerpoint = $form->getData();

            //Récupération du tableau danses $powerpoint->getDanses();

            // Test génération powerpoint

        }

        //Retourne la vue non formulaire
        return $this->render('powerPoint/new.html.twig', [
            'form'=>$form->createView(),

        ]);

    }

}