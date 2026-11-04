<?php

namespace app\components;

use Yii;
use yii\filters\auth\HttpBearerAuth;

/**
 * Authenticates a request by the `Authorization: Bearer <jwt>` header using lcobucci/jwt 5.x.
 *
 * Drop-in replacement for sizeg\jwt\JwtHttpBearerAuth. It parses and validates the bearer
 * token through the application `jwt` component, then resolves the identity via
 * User::findIdentityByAccessToken($token, self::class), preserving the existing flow where
 * the user id is read from the token's `uid` claim.
 */
class JwtHttpBearerAuth extends HttpBearerAuth
{
    /**
     * @var string the application component id of the JWT component.
     */
    public $jwt = 'jwt';

    /**
     * {@inheritdoc}
     */
    public function authenticate($user, $request, $response)
    {
        $authHeader = $request->getHeaders()->get($this->header);
        if ($authHeader === null || !preg_match($this->pattern, $authHeader, $matches)) {
            return null;
        }

        /** @var Jwt $jwt */
        $jwt = Yii::$app->get($this->jwt);

        try {
            $token = $jwt->parse($matches[1]);
        } catch (\Throwable $e) {
            return null;
        }

        if (!$jwt->validate($token)) {
            $this->handleFailure($response);
            return null;
        }

        $identity = $user->loginByAccessToken($token, get_class($this));
        if ($identity === null) {
            $this->handleFailure($response);
        }

        return $identity;
    }
}
