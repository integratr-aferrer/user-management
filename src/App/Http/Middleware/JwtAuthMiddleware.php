<?php

namespace UserPackage\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use UserPackage\App\Exceptions\ClaimNotFoundException;
use UserPackage\App\Models\User;
use UserPackage\App\Repositories\UserRepository;
use UserPackage\App\Traits\JwtParser;

class JwtAuthMiddleware
{
    use JwtParser;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws ClaimNotFoundException
     */
    public function handle($request, Closure $next)
    {
        if (!$this->parseTokenFromRequest($request)) {
            abort(401, 'No bearer token');
        }

        if ($request->isMethod('OPTIONS')) {
            return $next($request);
        }

        $token = $this->parseToken($this->parseTokenFromRequest($request));

        $uid = $this->parseClaimFromToken($token, 'uid');
        $account = $this->parseClaimFromToken($token, 'account');
        $access = $this->parseClaimFromToken($token, 'access');

        $request->headers->set('uid', $uid);
        $request->headers->set('account', $account);
        $request->headers->set('access', json_encode($access));

        $request->merge([
            'uid' => $uid,
            'account' => $account,
            'access' => $access
        ]);

        $response = $next($request);

        $headers = [
            'Access-Control-Max-Age' => '86400',
        ];

        foreach ($headers as $key => $value) {
            $response->header($key, $value);
        }

        return $response;
    }
}
