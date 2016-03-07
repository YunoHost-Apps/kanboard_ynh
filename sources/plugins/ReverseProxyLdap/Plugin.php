<?php

namespace Kanboard\Plugin\ReverseProxyLdap;

use Kanboard\Core\Plugin\Base;
use Kanboard\Plugin\ReverseProxyLdap\Auth\ReverseProxyLdapAuth;

/**
 * Reverse-Proxy Authentication with LDAP support
 *
 * @package  reverseproxyldap
 * @author   Frederic Guillot
 */
class Plugin extends Base
{
    public function initialize()
    {
        $this->authenticationManager->register(new ReverseProxyLdapAuth($this->container));
    }

    public function getPluginDescription()
    {
        return 'Authenticate users with Reverse-Proxy method but populate user information from the LDAP directory';
    }

    public function getPluginAuthor()
    {
        return 'Frédéric Guillot';
    }

    public function getPluginVersion()
    {
        return '1.0.0';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/kanboard/plugin-reverse-proxy-ldap';
    }
}
