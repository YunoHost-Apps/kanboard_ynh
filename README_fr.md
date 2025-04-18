<!--
Nota bene : ce README est automatiquement généré par <https://github.com/YunoHost/apps/tree/master/tools/readme_generator>
Il NE doit PAS être modifié à la main.
-->

# Kanboard pour YunoHost

[![Niveau d’intégration](https://apps.yunohost.org/badge/integration/kanboard)](https://ci-apps.yunohost.org/ci/apps/kanboard/)
![Statut du fonctionnement](https://apps.yunohost.org/badge/state/kanboard)
![Statut de maintenance](https://apps.yunohost.org/badge/maintained/kanboard)

[![Installer Kanboard avec YunoHost](https://install-app.yunohost.org/install-with-yunohost.svg)](https://install-app.yunohost.org/?app=kanboard)

*[Lire le README dans d'autres langues.](./ALL_README.md)*

> *Ce package vous permet d’installer Kanboard rapidement et simplement sur un serveur YunoHost.*  
> *Si vous n’avez pas YunoHost, consultez [ce guide](https://yunohost.org/install) pour savoir comment l’installer et en profiter.*

## Vue d’ensemble

Kanboard est un logiciel de gestion de projet Kanban gratuit et open source.

### Fonctionnalités

- Visualisez votre travail
- Limitez votre travail en cours pour vous concentrer sur votre objectif
- Glisser et déposez des tâches pour gérer votre projet


**Version incluse :** 1.2.44~ynh2

**Démo :** <https://demo.yunohost.org/kanboard/>

## Captures d’écran

![Capture d’écran de Kanboard](./doc/screenshots/board.png)

## Documentations et ressources

- Site officiel de l’app : <https://kanboard.net>
- Documentation officielle de l’admin : <https://docs.kanboard.org/>
- Dépôt de code officiel de l’app : <https://github.com/kanboard/kanboard>
- YunoHost Store : <https://apps.yunohost.org/app/kanboard>
- Signaler un bug : <https://github.com/YunoHost-Apps/kanboard_ynh/issues>

## Informations pour les développeurs

Merci de faire vos pull request sur la [branche `testing`](https://github.com/YunoHost-Apps/kanboard_ynh/tree/testing).

Pour essayer la branche `testing`, procédez comme suit :

```bash
sudo yunohost app install https://github.com/YunoHost-Apps/kanboard_ynh/tree/testing --debug
ou
sudo yunohost app upgrade kanboard -u https://github.com/YunoHost-Apps/kanboard_ynh/tree/testing --debug
```

**Plus d’infos sur le packaging d’applications :** <https://yunohost.org/packaging_apps>
