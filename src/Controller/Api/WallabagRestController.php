<?php

namespace App\Controller\Api;

use App\Entity\ApplicationInfo;
use App\Entity\User;
use Craue\ConfigBundle\Util\Config;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Contracts\Translation\TranslatorInterface;

class WallabagRestController extends AbstractFOSRestController
{
    protected EntityManagerInterface $entityManager;
    protected SerializerInterface $serializer;
    protected AuthorizationCheckerInterface $authorizationChecker;
    protected TokenStorageInterface $tokenStorage;
    protected TranslatorInterface $translator;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer, AuthorizationCheckerInterface $authorizationChecker, TokenStorageInterface $tokenStorage, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
        $this->translator = $translator;
    }

    /**
     * Retrieve version number.
     *
     * @Operation(
     *     tags={"Information"},
     *     summary="Retrieve version number.",
     *     @OA\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @OA\JsonContent(
     *             description="Version number of the application.",
     *             type="string",
     *             example="2.5.2",
     *         )
     *     )
     * )
     *
     * @deprecated Should use info endpoint instead
     *
     * @Route("/api/version.{_format}", methods={"GET"}, name="api_get_version", defaults={"_format": "json"})
     *
     * @return JsonResponse
     */
    public function getVersionAction()
    {
        $version = $this->getParameter('wallabag.version');
        $json = $this->serializer->serialize($version, 'json');

        return (new JsonResponse())->setJson($json);
    }

    /**
     * @Operation(
     *     tags={"Information"},
     *     summary="Retrieve information about the running wallabag application.",
     *     @OA\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type=ApplicationInfo::class),
     *     )
     * )
     *
     * @Route("/api/info.{_format}", methods={"GET"}, name="api_get_info", defaults={"_format": "json"})
     *
     * @return JsonResponse
     */
    public function getInfoAction(Config $craueConfig)
    {
        $info = new ApplicationInfo(
            $this->getParameter('wallabag.version'),
            $this->getParameter('fosuser_registration') && $craueConfig->get('api_user_registration'),
        );

        return (new JsonResponse())->setJson($this->serializer->serialize($info, 'json'));
    }

    protected function validateAuthentication()
    {
        if (false === $this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }
    }

    /**
     * Validate that the first id is equal to the second one.
     * If not, throw exception. It means a user try to access information from an other user.
     *
     * @param int $requestUserId User id from the requested source
     */
    protected function validateUserAccess($requestUserId)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        \assert($user instanceof User);

        if ($requestUserId !== $user->getId()) {
            throw $this->createAccessDeniedException('Access forbidden. Entry user id: ' . $requestUserId . ', logged user id: ' . $user->getId());
        }
    }

    /**
     * Shortcut to send data serialized in json.
     *
     * @param mixed $data
     *
     * @return JsonResponse
     */
    protected function sendResponse($data)
    {
        // https://github.com/schmittjoh/JMSSerializerBundle/issues/293
        $context = new SerializationContext();
        $context->setSerializeNull(true);

        $json = $this->serializer->serialize($data, 'json', $context);

        return (new JsonResponse())->setJson($json);
    }
}
