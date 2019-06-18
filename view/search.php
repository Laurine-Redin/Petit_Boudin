<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Petit Boudin</title>
    </head>

    <body>
        <!-- Affichage des messages d'erreurs ou des messages d'informations pour l'utilisateur -->
        <?php
        if (isset($params['error'])) {
            echo htmlspecialchars($params['error']);
        }

        if (isset($params['info'])) {
            echo htmlspecialchars($params['info']);
        }
        ?>

        <a href="/Petit_Boudin/profile">Return to profile</a><br><br>

        <?php
            if (isset($params['search'])) {
                foreach ($params['search'] as $search) { ?>
                    <a href="/Petit_Boudin/account/<?php echo $search->getPseudo(); ?>"><?php echo htmlspecialchars($search->getPseudo()); ?></a><br>
               <?php }
            }
        ?>

    </body>
</html>