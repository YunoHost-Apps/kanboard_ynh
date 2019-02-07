Kanboard for Yunohost
=====================

Kanboard is a simple visual task board web application.
Official website: <http://kanboard.net>

Requirements
------------

Functionnal instance of [Yunohost](https://yunohost.org/#/)

Installation
------------

From yunohost admin panel:

1. Use yunohost admin panel and enter the repository url
![2015-02-19 16_58_52-yunohost admin](https://cloud.githubusercontent.com/assets/6364564/6270409/1597e646-b85a-11e4-97af-b3b5b2a6b286.png)
2. Configure the app
![2015-02-19 16_59_28-yunohost admin](https://cloud.githubusercontent.com/assets/6364564/6270411/19f9a54e-b85a-11e4-83da-eb813c0457f7.png)
3. Click install

From command line:

`sudo yunohost app install -l kanboard https://github.com/YunoHost-Apps/kanboard_ynh`


Upgrade
-------
From command line:

`sudo yunohost app upgrade -u https://github.com/YunoHost-Apps/kanboard_ynh kanboard`

Infos
-----
Kanboard v1.2.8

Yunohost forum thread:  <https://forum.yunohost.org/t/kanboard-package/78>

Kanboard and SSOwat
-------------------
Kanboard use SSOwat for user authentification (it means it use the user that the web server (nginx) sent him throught SSOwat), but can't list all user of the system.
If you wish to add a user, just log in with that user into Kanboard so the software knows him and displays it.

Developer infos
----------------

Please do your pull request to the dev branch.

Update package version in `scripts/_common.sh`

Then do a manual diff between `conf/config.php` and `config.default.php` [from upstream Kanboard project](https://github.com/kanboard/kanboard/blob/master/config.default.php) to see if there are new config options

Update readme with the new version

Test it

Test or upgrade to dev version:

```
su - admin
git clone -b dev https://github.com/YunoHost-Apps/kanboard_ynh
# to install
sudo yunohost app install -l Kanboard /home/admin/kanboard_ynh
# to upgrade
sudo yunohost app upgrade -f /home/admin/kanboard_ynh kanboard

```
