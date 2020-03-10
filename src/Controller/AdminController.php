<?php


namespace App\Controller;


use App\Repository\PowerPointRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 * @package App\Controller
 * @Route("/admin")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/users", name="user_list_admin", methods={"GET"})
     */
    public function listUsers(UserRepository $userRepository)
    {
        return $this->render('user/list.html.twig', [
            'users'=>$userRepository->findAll()
        ]);

    }

    /**
     * @Route("/powerpoints", name="powerpoints_list_admin", methods={"GET"})
     */
    public function listPowerpoints(PowerPointRepository $powerPointRepository)
    {
        return $this->render('powerPoint/listPowerpointsUser.html.twig', [
            'powerpoints'=>$powerPointRepository->findAll(),
        ]);
    }

}