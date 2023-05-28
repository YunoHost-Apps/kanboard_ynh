### Comment se connecter en tant qu'utilisateurs externes (non SSOwat)

Vous devez éditer ce fichier `__INSTALL_DIR__/config.php`, trouver la ligne `define('REVERSE_PROXY_AUTH', true);` et la changer de `true` à `false`.
**Attention** cela désactive la possibilité de se connecter avec les utilisateurs SSOwat. Vous ne pourrez *que* vous connecter avec les utilisateurs Kanboard créés à l'intérieur de Kanboard.
Ensuite, vous pouvez vous connecter.

**NB**: si vous n'effectuez pas cette modification, vous obtiendrez le message d'erreur suivant "Accès interdit".

Cela est dû à une limitation de Kanboard.
