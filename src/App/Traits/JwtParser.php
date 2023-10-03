<?php

namespace UserPackage\App\Traits;

use Illuminate\Http\Request;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token;
use OutOfBoundsException;
use UserPackage\App\Exceptions\ClaimNotFoundException;

trait JwtParser
{
    /**
     * @param Request $request
     * @return string|null
     */
    public function parseTokenFromRequest(Request $request): string|null
    {
        return $request->bearerToken();
    }

    /**
     * Parse Token from the JWT String
     *
     * @param string $token
     * @return Token
     */
    public function parseToken(string $token): Token
    {
        $parser = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText(config('api.secret_key'))
        );

        return $parser->parser()->parse($token);
    }

    /**
     * @param Token $token
     * @param string $claim
     * @return mixed
     * @throws ClaimNotFoundException
     */
    public function parseClaimFromToken(Token $token, string $claim)
    {
        try {
            return $token->claims()->get($claim);
        } catch (OutOfBoundsException $e) {
            throw new ClaimNotFoundException();
        }
    }
}
