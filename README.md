<!--
N.B.: This README was automatically generated by https://github.com/YunoHost/apps/tree/master/tools/README-generator
It shall NOT be edited by hand.
-->

# Kanboard for YunoHost

[![Integration level](https://dash.yunohost.org/integration/kanboard.svg)](https://dash.yunohost.org/appci/app/kanboard) ![Working status](https://ci-apps.yunohost.org/ci/badges/kanboard.status.svg) ![Maintenance status](https://ci-apps.yunohost.org/ci/badges/kanboard.maintain.svg)

[![Install Kanboard with YunoHost](https://install-app.yunohost.org/install-with-yunohost.svg)](https://install-app.yunohost.org/?app=kanboard)

*[Lire ce readme en français.](./README_fr.md)*

> *This package allows you to install Kanboard quickly and simply on a YunoHost server.
If you don't have YunoHost, please consult [the guide](https://yunohost.org/#/install) to learn how to install it.*

## Overview

Kanboard is a free and open source Kanban project management software.

### Features

- Visualize your work
- Limit your work in progress to focus on your goal
- Drag and drop tasks to manage your project


**Shipped version:** 1.2.27~ynh4

**Demo:** https://demo.yunohost.org/kanboard/

## Screenshots

![Screenshot of Kanboard](./doc/screenshots/board.png)

## Documentation and resources

* Official app website: <https://kanboard.net>
* Official admin documentation: <https://docs.kanboard.org/en/latest/>
* Upstream app code repository: <https://github.com/kanboard/kanboard>
* YunoHost documentation for this app: <https://yunohost.org/app_kanboard>
* Report a bug: <https://github.com/YunoHost-Apps/kanboard_ynh/issues>

## Developer info

Please send your pull request to the [testing branch](https://github.com/YunoHost-Apps/kanboard_ynh/tree/testing).

To try the testing branch, please proceed like that.

``` bash
sudo yunohost app install https://github.com/YunoHost-Apps/kanboard_ynh/tree/testing --debug
or
sudo yunohost app upgrade kanboard -u https://github.com/YunoHost-Apps/kanboard_ynh/tree/testing --debug
```

**More info regarding app packaging:** <https://yunohost.org/packaging_apps>
