<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Security\Core\Authentication\Token;

use Symfony\Component\Security\Core\Role\Role;

/**
 * PreAuthenticatedToken implements a pre-authenticated token.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class PreAuthenticatedToken extends AbstractToken
{
    private $credentials;
    private $providerKey;

    /**
     * @param string|object   $user        The user can be a UserInterface instance, or an object implementing a __toString method or the username as a regular string
     * @param mixed           $credentials The user credentials
     * @param string          $providerKey The provider key
     * @param (Role|string)[] $roles       An array of roles
     */
    public function __construct($user, $credentials, string $providerKey, array $roles = [])
    {
        parent::__construct($roles);

        if (empty($providerKey)) {
            throw new \InvalidArgumentException('$providerKey must not be empty.');
        }

        $this->setUser($user);
        $this->credentials = $credentials;
        $this->providerKey = $providerKey;

        if ($roles) {
            $this->setAuthenticated(true);
        }
    }

    /**
     * Returns the provider key.
     *
     * @return string The provider key
     */
    public function getProviderKey()
    {
        return $this->providerKey;
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials()
    {
        return $this->credentials;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        parent::eraseCredentials();

        $this->credentials = null;
    }

    /**
     * {@inheritdoc}
     */
    protected function getState(): array
    {
        return [$this->credentials, $this->providerKey, parent::getState()];
    }

    /**
     * {@inheritdoc}
     */
    protected function setState(array $data)
    {
        [$this->credentials, $this->providerKey, $parentData] = $data;
        parent::setState($parentData);
    }
}
