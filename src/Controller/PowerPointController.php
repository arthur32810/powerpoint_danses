<?php


namespace App\Controller;


use App\Entity\PowerPoint;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

}