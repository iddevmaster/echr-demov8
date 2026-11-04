<?php

namespace app\components;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Validation\Constraint\IdentifiedBy;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * JWT component built on lcobucci/jwt 5.x.
 *
 * Replaces the former sizeg/yii2-jwt wrapper (which was pinned to lcobucci/jwt 3.x and
 * could not run on PHP 8.x). Exposes the lcobucci Configuration plus the validation
 * constraints the application expects, so token issuing and verification stay in one place.
 */
class Jwt extends Component
{
    /**
     * @var string symmetric signing secret. Must be configured.
     */
    public $key;

    /**
     * @var string expected issuer (iss) and audience (aud) claim.
     */
    public $issuer = 'https://echr.kku.ac.th';

    /**
     * @var string expected audience (aud) claim.
     */
    public $audience = 'https://echr.kku.ac.th';

    /**
     * @var string expected token id (jti) claim.
     */
    public $id = '93f5f52b2b6a5d9fce4e2278b1341da1c1e06bbd';

    /**
     * @var Configuration|null
     */
    private $configuration;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if (empty($this->key)) {
            throw new InvalidConfigException('Jwt::$key must be set.');
        }
    }

    /**
     * @return Configuration the lcobucci configuration (HMAC-SHA256 symmetric signer).
     */
    public function getConfiguration()
    {
        if ($this->configuration === null) {
            $configuration = Configuration::forSymmetricSigner(
                new Sha256(),
                InMemory::plainText($this->resolveSigningKey())
            );
            $this->configuration = $configuration->withValidationConstraints(
                new SignedWith($configuration->signer(), $configuration->signingKey()),
                new IssuedBy($this->issuer),
                new PermittedFor($this->audience),
                new IdentifiedBy($this->id)
            );
        }

        return $this->configuration;
    }

    /**
     * Returns a signing key that satisfies the HMAC-SHA256 minimum length (256 bits) required
     * by lcobucci/jwt 5.x. Short legacy secrets are stretched with SHA-256 so the configured
     * value in config/web.php can stay unchanged. The derivation is deterministic, so tokens
     * signed and verified by this application remain self-consistent.
     *
     * @return string a key of at least 32 bytes.
     */
    private function resolveSigningKey()
    {
        if (strlen($this->key) >= 32) {
            return $this->key;
        }

        // Raw 32-byte (256-bit) digest derived from the configured secret.
        return hash('sha256', $this->key, true);
    }

    /**
     * Parses a JWT string into a Token object.
     *
     * @param string $jwt
     * @return Token
     */
    public function parse($jwt)
    {
        return $this->getConfiguration()->parser()->parse($jwt);
    }

    /**
     * Validates a parsed token against the configured constraints (signature, iss, aud, jti).
     *
     * @param Token $token
     * @return bool true when every constraint passes.
     */
    public function validate(Token $token)
    {
        $config = $this->getConfiguration();
        return $config->validator()->validate($token, ...$config->validationConstraints());
    }
}
