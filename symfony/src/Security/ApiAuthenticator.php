<?php


namespace App\Security;


use App\Repository\AccessTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ApiAuthenticator extends AbstractGuardAuthenticator
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var AccessTokenRepository */
    private $accessTokenRepository;

    public function __construct(EntityManagerInterface $em, AccessTokenRepository $accessTokenRepository)
    {
        $this->em = $em;
        $this->accessTokenRepository = $accessTokenRepository;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            // you might translate this message
            'message' => 'Authentication Required'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supports(Request $request)
    {
//        return $request->headers->has('X-AUTH-TOKEN');
        return true;
    }

    public function getCredentials(Request $request)
    {
        return (string)$request->headers->get('Authorization');
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $credentials = (string)$credentials;
        if ('' === $credentials || strlen($credentials) !== 36) {
            // The token header was empty, authentication fails with HTTP Status
            // Code 401 "Unauthorized"
            return null;
        }

        // The "username" in this case is the apiToken, see the key `property`
        // of `your_db_provider` in `security.yaml`.
        // If this returns a user, checkCredentials() is called next:
        $token = $this->accessTokenRepository->findOneBy(['token' => $credentials]);
        if (!$token) {
            return null;
        }

        return $token->getOwner();
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // Check credentials - e.g. make sure the password is valid.
        // In case of an API token, no credential check is needed.

        // Return `true` to cause authentication success
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'error' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        // on success, let the request continue
        return null;
    }

    public function supportsRememberMe()
    {
        return false;
    }

}