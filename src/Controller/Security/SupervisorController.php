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
     * @Route("/security/supervisor/{uuid}", name="security_supervisor")
     */
    public function index(Request $request, $uuid = null)
    {
        $manager = $this->getDoctrine()->getManager();

        if ($request->isMethod('post')) {
            $jsonContent = $request->getContent();
            $content = json_decode($jsonContent);

            $uuid = $content->uuid;
            $benefitProviderUuid = $content->benefitProviderUuid;
            $usercode = $content->usercode;
            $username = $content->username;
            $pwd = $content->password;

            $userRepo = $this->getDoctrine()->getRepository(User::class);

            $bpRepo = $this->getDoctrine()
                ->getRepository(BenefitProvider::class);

            /** @var BenefitProvider $bp */
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
                $org->setSupervisorCode($usercode);
                $org
                    ->setEnabled(true)
                    ->setUuid($data->organisationUuid)
                    ->setName($data->name)
                    ->setLegacyId($data->organisationLegacyId);

                $bp->setOrganisation($org);
                $org->setBenefitProvider($bp);

                $manager->persist($bp);
                $manager->persist($org);
            } else {
                $org = $bp->getOrganisation();
                if (!empty($usercode)) {
                    $org->setCode($usercode);
                    $manager->persist($org);
                }
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

            $supervisor->setOrganisation($org);
            $supervisor->setUsername($username);
            if (!empty($pwd)) {
                $supervisor->setPlainPassword($pwd);
            }

            $supervisor->setRoles([User::ROLE_SUPERVISOR]);

            $supervisor->initUuid();
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
