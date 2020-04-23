<?php

namespace App\Controller\Security;

use App\Entity\Security\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SupervisorController extends AbstractController
{
    /**
     * @Route("/security/supervisor", name="security_supervisor")
     */
    public function index(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        if ($request->isMethod('post')) {
            $username = $request->request->get('username');
            $pwd = $request->request->get('password');
            $userRepo = $this->getDoctrine()->getRepository(User::class);

            $supervisor = $userRepo->findOneByUsername($username);

            if (empty($supervisor)) {
                $supervisor = new User();
            }

            $supervisor->setUsername($username);
            $supervisor->setPlainPassword($pwd);

            $supervisor->setRoles([User::ROLE_SUPERVISOR]);

            $manager->persist($supervisor);
            $manager->flush();
        }
        return new JsonResponse(['username' => $username,
        ]);
//        return $this->render('security/supervisor/index.html.twig', [
//            'controller_name' => 'SupervisorController',
//        ]);
    }
}
