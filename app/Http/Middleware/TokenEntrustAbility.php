<?php

namespace App\Http\Middleware;

use Illuminate\Http\Response as HttpResponse;
use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Middleware\BaseMiddleware;

class TokenEntrustAbility extends BaseMiddleware {

    public function handle($request, Closure $next, $roles, $permissions, $validateAll = false) {

        if (!$token = $this->auth->setRequest($request)->getToken()) {
            return $this->respond('tymon.jwt.absent', 'token_not_provided', HttpResponse::HTTP_BAD_REQUEST);
        }

        try {
            $user = $this->auth->authenticate($token);
        } catch (TokenExpiredException $e) {
            return $this->respond('tymon.jwt.expired', 'token_expired', $e->getStatusCode(), [$e]);
        } catch (JWTException $e) {
            return $this->respond('tymon.jwt.invalid', 'token_invalid', $e->getStatusCode(), [$e]);
        }

        if (!$user) {
            return $this->respond('tymon.jwt.user_not_found', 'user_not_found', HttpResponse::HTTP_NOT_FOUND);
        }

        if (!$request->user()->ability(explode('|', $roles), explode('|', $permissions), array('validate_all' => $validateAll))) {
            return $this->respond('tymon.jwt.invalid', 'token_unauthorized', HttpResponse::HTTP_UNAUTHORIZED, 'Unauthorized');
        }

        $this->events->fire('tymon.jwt.valid', $user);

        return $next($request);
    }

}
