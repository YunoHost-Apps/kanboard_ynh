# Kanboard for Yunohost

[![Integration level](https://dash.yunohost.org/integration/kanboard.svg)](https://dash.yunohost.org/appci/app/kanboard) ![](https://ci-apps.yunohost.org/ci/badges/kanboard.status.svg) ![](https://ci-apps.yunohost.org/ci/badges/kanboard.maintain.svg)  
[![Install Kanboard with YunoHost](https://install-app.yunohost.org/install-with-yunohost.svg)](https://install-app.yunohost.org/?app=kanboard)

*[Lire ce readme en français.](./README_fr.md)*

> *This package allow you to install Kanboard quickly and simply on a YunoHost server.  
If you don't have YunoHost, please see [here](https://yunohost.org/#/install) to know how to install and enjoy it.*

## Overview
Kanboard is a visual task manager that makes it easy to manage small projects in a collaborative way. The tool is particularly suitable for people who use the Kanban method. Kanboard can be seen as a (Simplified) alternative to the proprietary Trello software. Kanboard is a minimalist software, it focuses only on the features that are really necessary. The user interface is simple and clear. The tool is designed to run on a small machine such as a Raspberry Pi or a Virtual Private Server (VPS). There are no external dependencies, drag and drop of tasks uses the new HTML5 APIs.

**Shipped version:** 1.2.17

## Screenshots

![](https://kanboard.org/assets/img/board.png)

## Demo

* [YunoHost demo](https://demo.yunohost.org/kanboard/)

## Configuration

## Documentation

 * Official documentation: https://docs.kanboard.org/en/latest/
 * YunoHost documentation: https://yunohost.org/#/app_kanboard

## YunoHost specific features

#### Multi-users support

 * Are LDAP and HTTP auth supported? **No**
 * Can the app be used by multiple users? **Yes**

#### Supported architectures

* x86-64 - [![Build Status](https://ci-apps.yunohost.org/ci/logs/kanboard%20%28Apps%29.svg)](https://ci-apps.yunohost.org/ci/apps/kanboard/)
* ARMv8-A - [![Build Status](https://ci-apps-arm.yunohost.org/ci/logs/kanboard%20%28Apps%29.svg)](https://ci-apps-arm.yunohost.org/ci/apps/kanboard/)

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

## Developers info

Please do your pull request to the [testing branch](https://github.com/YunoHost-Apps/kanboard_ynh/tree/testing).

To try the testing branch, please proceed like that.
```
sudo yunohost app install https://github.com/YunoHost-Apps/kanboard_ynh/tree/testing --debug
or
sudo yunohost app upgrade kanboard -u https://github.com/YunoHost-Apps/kanboard_ynh/tree/testing --debug
```
