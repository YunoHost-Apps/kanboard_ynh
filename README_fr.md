# Kanboard pour Yunohost

[![Integration level](https://dash.yunohost.org/integration/kanboard.svg)](https://dash.yunohost.org/appci/app/kanboard) ![](https://ci-apps.yunohost.org/ci/badges/kanboard.status.svg) ![](https://ci-apps.yunohost.org/ci/badges/kanboard.maintain.svg)  
[![Installer Kanboard avec YunoHost](https://install-app.yunohost.org/install-with-yunohost.svg)](https://install-app.yunohost.org/?app=kanboard)

*[Read this readme in english.](./README.md)* 

> *Ce package vous permet d'installer Kanboard rapidement et simplement sur un serveur YunoHost.  
Si vous n'avez pas YunoHost, consultez [le guide](https://yunohost.org/#/install) pour apprendre comment l'installer.*

## Vue d'ensemble
Kanboard est un gestionnaire de tâches visuel qui permet de gérer facilement des petits projets de manière collaborative. L'outil est particulièrement adapté aux personnes qui utilisent la méthode Kanban. On peut voir Kanboard comme une alternative (simplifiée) au logiciel propriétaire Trello. Kanboard est un logiciel minimaliste, il se concentre uniquement sur les fonctionnalités réellement nécessaires. L'interface utilisateur est simple et clair. L'outil est prévu pour fonctionner sur une petite machine tel qu'un Raspberry Pi ou un serveur virtuel privé (VPS). Il n'y a aucune dépendance externe, le glisser-déposer des tâches utilise les nouvelles API de HTML5.

**Version incluse :**  1.2.16

## Captures d'écran

![](https://kanboard.org/assets/img/board.png)

## Démo

* [Démo YunoHost](https://demo.yunohost.org/kanboard/)

## Configuration

## Documentation

 * Documentation officielle : https://docs.kanboard.org/fr/latest/
 * Documentation YunoHost : https://yunohost.org/#/app_kanboard_fr

## Caractéristiques spécifiques YunoHost

#### Support multi-utilisateur

* L'authentification LDAP et HTTP est-elle prise en charge ? **Non**
* L'application peut-elle être utilisée par plusieurs utilisateurs ? **Oui**

#### Architectures supportées

* x86-64 - [![Build Status](https://ci-apps.yunohost.org/ci/logs/kanboard%20%28Apps%29.svg)](https://ci-apps.yunohost.org/ci/apps/kanboard/)
* ARMv8-A - [![Build Status](https://ci-apps-arm.yunohost.org/ci/logs/kanboard%20%28Apps%29.svg)](https://ci-apps-arm.yunohost.org/ci/apps/kanboard/)

## Limitations

## Informations additionnelles

### Comment se connecter en tant qu'utilisateurs externes (non SSOwat)

Vous devez éditer ce fichier `/var/www/kanboard/config.php`, trouver la ligne `define('REVERSE_PROXY_AUTH', true);` et la changer de `true` à `false`.
**Attention** cela désactive la possibilité de se connecter avec les utilisateurs SSOwat. Vous ne pourrez *que* vous connecter avec les utilisateurs Kanboard créés à l'intérieur de Kanboard.
Ensuite, vous pouvez vous connecter.

**NB**: si vous n'effectuez pas cette modification, vous obtiendrez le message d'erreur suivant "Accès interdit".

Cela est dû à une limitation de Kanboard.

## Liens

 * Signaler un bug : https://github.com/YunoHost-Apps/kanboard_ynh/issues
 * Site de l'application : https://kanboard.org
 * Dépôt de l'application principale : https://github.com/kanboard/kanboard
 * Site web YunoHost : https://yunohost.org/

---

## Informations pour les développeurs

Merci de faire vos pull request sur la [branche testing](https://github.com/YunoHost-Apps/kanboard_ynh/tree/testing).

Pour essayer la branche testing, procédez comme suit.
```
sudo yunohost app install https://github.com/YunoHost-Apps/kanboard_ynh/tree/testing --debug
ou
sudo yunohost app upgrade kanboard -u https://github.com/YunoHost-Apps/kanboard_ynh/tree/testing --debug
```
