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

        <form action="/Petit_Boudin/ChangeAccountDB" method="post">
            <label>First Name</label>
            <input type="text" name="FirstName" placeholder="First Name" value="<?php if (isset($params['back'])) echo htmlspecialchars($params['back']->getFirstname());?>">

            <label>Last Name</label>
            <input type="text" name="LastName" placeholder="Last Name" value="<?php if (isset($params['back'])) echo htmlspecialchars($params['back']->getLastname()); ?>">

            <label>Mail</label>
            <input type="text" name="Mail" placeholder="Mail" value="<?php if (isset($params['back'])) echo htmlspecialchars($params['back']->getMail()); ?>">

            <label>Pseudo</label>
            <input type="text" name="Pseudo" placeholder="Pseudo" value="<?php if (isset($params['back'])) echo htmlspecialchars($params['back']->getPseudo()); ?>">

            <button type="submit">Send</button>
        </form>

        <a href="/Petit_Boudin/profile">Return</a>
    </body>
</html>