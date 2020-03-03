<?php


namespace App\Controller;


use App\Entity\PowerPoint;
use App\Form\PowerPointType;
use App\Service\PowerPointGenerator;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
     * @Route("/powerpoints/{id}", name="powerpoint_edit", requirements={"id"="\d+"} )
     */
    public function editPowerpoint(PowerPoint $powerPoint, PowerPointGenerator $powerPointGenerator, SessionInterface $session, Request $request)
    {
        if($powerPoint->getUser() == $this->getUser()){

            $form = $this->createForm(PowerPointType::class, $powerPoint, [
                'name_button'=> 'Enregistrer et générer',
                'disabled_button_generate'=>false
            ]);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {

                if(count($powerPoint->getDanses()) === 0)
                {
                    return $this->redirectToRoute('powerpoint_delete', ['id'=>$powerPoint->getId()]);
                }

                $em = $this->getDoctrine()->getManager();

                //gestion si plus de danses -> suppression du powerpoint

                //Pour chaque nouvelle danses
                foreach ($powerPoint->getDanses() as $danse)
                {
                    if($danse->getId() === null)
                    {
                        $danse->setPowerPoint($powerPoint);
                    }
                }

                $em->flush();
                $this->addFlash('success', 'Votre powerpoint a bien été enregistré');

                // Détection bouton enregistrer cliqué
                if($form->getClickedButton() && 'onlySave' === $form->getClickedButton()->getName())
                {
                    return $this->redirectToRoute('all_powerpoint_user');
                }

                // gestion de la creation du powerpoint
               $urlPowerpoint = $powerPointGenerator->main($powerPoint->getDanses());

               $this->addFlash('success', 'Le fichier a bien été généré !');

               $session->set('urlPowerpoint', $urlPowerpoint);

               return $this->redirectToRoute('powerpoint_download');
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


    /**
     * @Route("/powerpoints/delete/{id}", name="powerpoint_delete", requirements={"id"="\d+"})
     */
    public function powerpointDelete(PowerPoint $powerPoint){
        if($powerPoint->getUser() == $this->getUser()) {
           $em = $this->getDoctrine()->getManager();

            $em->remove($powerPoint);
            $em->flush();

            $this->addFlash('success', 'La presentation a bien été supprimé');
            return $this->redirectToRoute('all_powerpoint_user');
        }
        else {
            $this->addFlash('danger', "Vous avez tenté d'accéder à un powerpoint qui ne vous appartient pas");
            return $this->redirectToRoute('all_powerpoint_user');
        }
    }


}