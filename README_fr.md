# Kanboard pour YunoHost

[![Niveau d'intégration](https://dash.yunohost.org/integration/kanboard.svg)](https://dash.yunohost.org/appci/app/kanboard) ![](https://ci-apps.yunohost.org/ci/badges/kanboard.status.svg) ![](https://ci-apps.yunohost.org/ci/badges/kanboard.maintain.svg)  
[![Installer Kanboard avec YunoHost](https://install-app.yunohost.org/install-with-yunohost.svg)](https://install-app.yunohost.org/?app=kanboard)

*[Read this readme in english.](./README.md)*
*[Lire ce readme en français.](./README_fr.md)*

> *Ce package vous permet d'installer Kanboard rapidement et simplement sur un serveur YunoHost.
Si vous n'avez pas YunoHost, regardez [ici](https://yunohost.org/#/install) pour savoir comment l'installer et en profiter.*

## Vue d'ensemble

Kanboard est un logiciel de gestion de projet de type Kanban. 

**Version incluse :** 1.2.21~ynh3

**Démo :** https://demo.yunohost.org/kanboard/

## Captures d'écran

![](./doc/screenshots/board.png)

## Avertissements / informations importantes

## Limitations

## Informations additionnelles

### Comment se connecter en tant qu'utilisateurs externes (non SSOwat)

Vous devez éditer ce fichier `/var/www/kanboard/config.php`, trouver la ligne `define('REVERSE_PROXY_AUTH', true);` et la changer de `true` à `false`.
**Attention** cela désactive la possibilité de se connecter avec les utilisateurs SSOwat. Vous ne pourrez *que* vous connecter avec les utilisateurs Kanboard créés à l'intérieur de Kanboard.
Ensuite, vous pouvez vous connecter.

**NB**: si vous n'effectuez pas cette modification, vous obtiendrez le message d'erreur suivant "Accès interdit".

Cela est dû à une limitation de Kanboard.

## Documentations et ressources

* Site officiel de l'app : https://kanboard.net
* Documentation officielle utilisateur : https://docs.kanboard.org/en/latest/user_guide/index.html
* Documentation officielle de l'admin : https://docs.kanboard.org/en/latest/
* Dépôt de code officiel de l'app : https://github.com/kanboard/kanboard
* Documentation YunoHost pour cette app : https://yunohost.org/app_kanboard
* Signaler un bug : https://github.com/YunoHost-Apps/kanboard_ynh/issues

## Informations pour les développeurs

Merci de faire vos pull request sur la [branche testing](https://github.com/YunoHost-Apps/kanboard_ynh/tree/testing).

Pour essayer la branche testing, procédez comme suit.
```
sudo yunohost app install https://github.com/YunoHost-Apps/kanboard_ynh/tree/testing --debug
ou
sudo yunohost app upgrade kanboard -u https://github.com/YunoHost-Apps/kanboard_ynh/tree/testing --debug
```

**Plus d'infos sur le packaging d'applications :** https://yunohost.org/packaging_apps