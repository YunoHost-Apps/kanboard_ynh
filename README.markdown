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

`sudo yunohost app install -l kanboard https://github.com/mbugeia/kanboard_ynh`


Upgrade
-------
From command line:

`sudo yunohost app upgrade -u https://github.com/mbugeia/kanboard_ynh kanboard`

Infos
-----
Kanboard v1.0.18

Yunohost forum thread:  <https://forum.yunohost.org/t/kanboard-package/78>

Kanboard and SSOwat
-------------------
Kanboard use SSOwat for user authentification (it means it use the user that the web server (nginx) sent him throught SSOwat), but can't list all user of the system.
If you wish to add a user, just log in with that user into Kanboard so the software knows him and displays it.
