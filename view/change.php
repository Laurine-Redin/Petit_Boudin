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

        <!-- Pseudo et information personnelle -->
        <?php echo $_SESSION['username']; ?>

        <!-- deconnexion du compte et profile -->
        <a href="/Petit_Boudin/LogOut">Log out</a>
        <a href="/Petit_Boudin/profile">Back to Profile</a>

        <form action="/Petit_Boudin/CMessageDB" method="post">
            <input type="hidden" value="<?php echo $params['message']->getId(); ?>" name="id">
            <input type="text" value="<?php if (strlen(substr($params['message']->getMessage(), 0,strrpos($params['message']->getMessage(), ' (edit)'))) === 0) echo htmlspecialchars($params['message']->getMessage()); else echo htmlspecialchars(substr($params['message']->getMessage(), 0,strrpos($params['message']->getMessage(), ' (edit)'))) ?>" name="message" maxlength="140">
            <button type="submit">Submit</button>
        </form>
    </body>
</html>