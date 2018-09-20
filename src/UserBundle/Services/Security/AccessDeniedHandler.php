<?php

namespace UserBundle\Services\Security;


use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AccessDeniedHandler implements AccessDeniedHandlerInterface{

    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(array('success' => false, 'message' => $accessDeniedException->getMessage()));
        }

        return $accessDeniedException;
    }
}

?>
