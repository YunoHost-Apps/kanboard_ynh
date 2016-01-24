Reverse-Proxy Authentication plugin with LDAP support for Kanboard
==================================================================

Authenticate users with Reverse-Proxy method but populate user information from the LDAP directory.

Author
------

- Frédéric Guillot
- License MIT

Installation
------------

- Create a folder **plugins/ReverseProxyLdap** or uncompress the latest archive in the folder **plugins**
- Copy all files under this directory

Configuration
-------------

- You must have LDAP configured in proxy mode in Kanboard
- Reverse-Proxy server configured correctly, the config parameter `REVERSE_PROXY_USER_HEADER` must be defined
- You **don't need** to set to `true` those constants: `LDAP_AUTH` and `REVERSE_PROXY_AUTH`
