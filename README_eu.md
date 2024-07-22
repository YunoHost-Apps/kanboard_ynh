<!--
Ohart ongi: README hau automatikoki sortu da <https://github.com/YunoHost/apps/tree/master/tools/readme_generator>ri esker
EZ editatu eskuz.
-->

# Kanboard YunoHost-erako

[![Integrazio maila](https://dash.yunohost.org/integration/kanboard.svg)](https://ci-apps.yunohost.org/ci/apps/kanboard/) ![Funtzionamendu egoera](https://ci-apps.yunohost.org/ci/badges/kanboard.status.svg) ![Mantentze egoera](https://ci-apps.yunohost.org/ci/badges/kanboard.maintain.svg)

[![Instalatu Kanboard YunoHost-ekin](https://install-app.yunohost.org/install-with-yunohost.svg)](https://install-app.yunohost.org/?app=kanboard)

*[Irakurri README hau beste hizkuntzatan.](./ALL_README.md)*

> *Pakete honek Kanboard YunoHost zerbitzari batean azkar eta zailtasunik gabe instalatzea ahalbidetzen dizu.*  
> *YunoHost ez baduzu, kontsultatu [gida](https://yunohost.org/install) nola instalatu ikasteko.*

## Aurreikuspena

Kanboard is a free and open source Kanban project management software.

### Features

- Visualize your work
- Limit your work in progress to focus on your goal
- Drag and drop tasks to manage your project


**Paketatutako bertsioa:** 1.2.38~ynh1

**Demoa:** <https://demo.yunohost.org/kanboard/>

## Pantaila-argazkiak

![Kanboard(r)en pantaila-argazkia](./doc/screenshots/board.png)

## Dokumentazioa eta baliabideak

- Aplikazioaren webgune ofiziala: <https://kanboard.net>
- Administratzaileen dokumentazio ofiziala: <https://docs.kanboard.org/>
- Jatorrizko aplikazioaren kode-gordailua: <https://github.com/kanboard/kanboard>
- YunoHost Denda: <https://apps.yunohost.org/app/kanboard>
- Eman errore baten berri: <https://github.com/YunoHost-Apps/kanboard_ynh/issues>

## Garatzaileentzako informazioa

Bidali `pull request`a [`testing` abarrera](https://github.com/YunoHost-Apps/kanboard_ynh/tree/testing).

`testing` abarra probatzeko, ondorengoa egin:

```bash
sudo yunohost app install https://github.com/YunoHost-Apps/kanboard_ynh/tree/testing --debug
edo
sudo yunohost app upgrade kanboard -u https://github.com/YunoHost-Apps/kanboard_ynh/tree/testing --debug
```

**Informazio gehiago aplikazioaren paketatzeari buruz:** <https://yunohost.org/packaging_apps>
