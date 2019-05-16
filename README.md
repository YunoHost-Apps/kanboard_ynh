# Kanboard for Yunohost

[![Integration level](https://dash.yunohost.org/integration/kanboard.svg)](https://dash.yunohost.org/appci/app/kanboard)  
[![Install Kanboard with YunoHost](https://install-app.yunohost.org/install-with-yunohost.png)](https://install-app.yunohost.org/?app=kanboard)

> *This package allow you to install Kanboard quickly and simply on a YunoHost server.  
If you don't have YunoHost, please see [here](https://yunohost.org/#/install) to know how to install and enjoy it.*

## Overview
Kanboard is a simple visual task board web application.

**Shipped version:** 1.2.9

## Screenshots

![](https://kanboard.org/assets/img/board.png)

## Demo

* [YunoHost demo](https://demo.yunohost.org/dokuwiki/)

## Configuration

## Documentation

 * Official documentation: https://docs.kanboard.org/en/latest/
 * YunoHost documentation: If specific documentation is needed, feel free to contribute.

## YunoHost specific features

#### Multi-users support

#### Supported architectures

* x86-64b - [![Build Status](https://ci-apps.yunohost.org/ci/logs/kanboard%20%28Apps%29.svg)](https://ci-apps.yunohost.org/ci/apps/kanboard/)
* ARMv8-A - [![Build Status](https://ci-apps-arm.yunohost.org/ci/logs/kanboard%20%28Apps%29.svg)](https://ci-apps-arm.yunohost.org/ci/apps/kanboard/)
* Jessie x86-64b - [![Build Status](https://ci-stretch.nohost.me/ci/logs/kanboard%20%28Apps%29.svg)](https://ci-stretch.nohost.me/ci/apps/kanboard/)

## Limitations

## Additional information

### How to connect as external (non-SSOwat) users

You have to edit this file `/var/www/kanboard/config.php`, find the line `define('REVERSE_PROXY_AUTH', true);` and change it from `true` to `false`.
**Warning** this disables the possibility to connect with SSOwat users. You will *only* be able to connect with Kanboard users created inside of Kanboard.
Then you can connect.

**NB**: if you don't make that change, you will get the following error message "Access Forbidden".

This is due to a Kanboard limitation.

## Links

 * Report a bug: https://github.com/YunoHost-Apps/kanboard_ynh/issues
 * Kanboard website: https://kanboard.org
 * Upstream app repository: https://github.com/kanboard/kanboard
 * YunoHost website: https://yunohost.org/

---

Developers info
----------------

**Only if you want to use a testing branch for coding, instead of merging directly into master.**
Please do your pull request to the [testing branch](https://github.com/YunoHost-Apps/kanboard_ynh/tree/testing).

To try the testing branch, please proceed like that.
```
sudo yunohost app install https://github.com/YunoHost-Apps/kanboard_ynh/tree/testing --debug
or
sudo yunohost app upgrade kanboard -u https://github.com/YunoHost-Apps/kanboard_ynh/tree/testing --debug
```
