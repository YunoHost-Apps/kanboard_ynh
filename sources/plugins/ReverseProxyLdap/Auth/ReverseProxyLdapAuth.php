<?php

namespace Kanboard\Plugin\ReverseProxyLdap\Auth;

use Kanboard\Auth\ReverseProxyAuth;
use Kanboard\Core\Ldap\Client as LdapClient;
use Kanboard\Core\Ldap\ClientException as LdapException;
use Kanboard\Core\Ldap\User as LdapUser;

/**
 * Reverse-Proxy Ldap Authentication Provider
 *
 * @package  auth
 * @author   Frederic Guillot
 */
class ReverseProxyLdapAuth extends ReverseProxyAuth
{
    /**
     * Get authentication provider name
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return 'ReverseProxyLdap';
    }

    /**
     * Authenticate the user
     *
     * @access public
     * @return boolean
     */
    public function authenticate()
    {
        try {

            $username = $this->request->getRemoteUser();

            if (! empty($username)) {

                $client = LdapClient::connect();
                $user = LdapUser::getUser($client, $username);

                if ($user === null) {
                    $this->logger->info('User not found in LDAP server');
                    return false;
                }

                if ($user->getUsername() === '') {
                    throw new LogicException('Username not found in LDAP profile, check the parameter LDAP_USER_ATTRIBUTE_USERNAME');
                }

                $this->userInfo = $user;

                return true;
            }

        } catch (LdapException $e) {
            $this->logger->error($e->getMessage());
        }

        return false;
    }
}
