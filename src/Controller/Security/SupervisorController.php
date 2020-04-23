<?php

namespace App\Controller\Security;

use App\Entity\BenefitProvider\BenefitProvider;
use App\Entity\Organisation\Organisation;
use App\Entity\Security\User;
use App\Service\HttpService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SupervisorController extends AbstractController
{
    private $http;

    public function __construct(HttpService $http)
    {
        $this->http = $http;
    }

    /**
     * @Route("/security/supervisor", name="security_supervisor")
     */
    public function index(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        if ($request->isMethod('post')) {
            $benefitProviderUuid = $request->request->get('benefitProviderUuid');

            $uuid = $request->request->get('uuid');
            $usercode = $request->request->get('usercode');
            $username = $request->request->get('username');
            $pwd = $request->request->get('password');

            $userRepo = $this->getDoctrine()->getRepository(User::class);

            $bpRepo = $this->getDoctrine()
                ->getRepository(BenefitProvider::class);
            $bp = $bpRepo->findOneByUuid($benefitProviderUuid);

            if (empty($bp)) {
                $bp = new BenefitProvider();
                $bp->setUuid($benefitProviderUuid);
                $resourcePath = 'benefit-providers';
                $res = $this->http->get($resourcePath, $benefitProviderUuid);
                $data = $res['body'];
                if (empty($data)) {
                    return new JsonResponse(null);
                }

                $bp
                    ->setEnabled(true)
                    ->setName($data->name);

                $org = new Organisation();
                $org
                    ->setEnabled(true)
                    ->setUuid($data->organisationUuid)
                    ->setName($data->name)
                    ->setLegacyId($data->organisationLegacyId);

                $bp->setOrganisation($org);
                $org->setBenefitProvider($bp);

                $manager->persist($bp);
                $manager->persist($org);
            }

            if ($uuid) {
                /** @var User $supervisor */
                $supervisor = $userRepo->findOneByUuid($uuid);
            }

            if (empty($supervisor)) {
                /** @var User $supervisor */
                $supervisor = $userRepo->findOneByUsername($username);
            }

            if (empty($supervisor)) {
                $supervisor = new User();
            }

            $supervisor->setUsername($username);
            $supervisor->setPlainPassword($pwd);

            $supervisor->setRoles([User::ROLE_SUPERVISOR]);

            $manager->persist($supervisor);
            $manager->flush();
        }
        if ($request->isMethod('delete')) {
            $uuid = $request->request->get('uuid');
            /** @var User $supervisor */
            $supervisor = $userRepo->findOneByUuid($uuid);
            if ($supervisor) {
                $manager->remove($supervisor);
                $manager->flush();
            }
        }
        return new JsonResponse(['uuid' => $supervisor->getUuid(), 'username' => $username,
        ]);
//        return $this->render('security/supervisor/index.html.twig', [
//            'controller_name' => 'SupervisorController',
//        ]);
    }
}
