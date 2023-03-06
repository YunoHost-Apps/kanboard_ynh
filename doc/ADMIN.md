### How to connect as external (non-SSOwat) users

You have to edit this file `/var/www/kanboard/config.php`, find the line `define('REVERSE_PROXY_AUTH', true);` and change it from `true` to `false`.
**Warning** this disables the possibility to connect with SSOwat users. You will *only* be able to connect with Kanboard users created inside of Kanboard.
Then you can connect.

**NB**: if you don't make that change, you will get the following error message "Access Forbidden".

This is due to a Kanboard limitation.
