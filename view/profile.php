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

        <?php echo $_SESSION['username'] ?>

        <!-- deconnexion du compte et profile -->
        <a href="/Petit_Boudin/account/<?php echo $_SESSION['username'] ?>">Profile</a>
        <a href="/Petit_Boudin/LogOut">Log out</a>
        <a href="/Petit_Boudin/UpdateProfile">Update Profile</a>


        <!-- Création des messages -->
        <form action="/Petit_Boudin/AMessage" method="post">
            <input type="text" name="message" placeholder="message" maxlength="140">
            <button type="submit">Envoyer</button>
        </form>

        <!-- Recherche -->
        <form action="/Petit_Boudin/search" method="post">
            <input type="text" name="search" placeholder="Search ...">
            <button type="submit">Search</button>
        </form>

        <!-- Affichage des messages -->
        <?php
            if (isset($params['message'])) {
                foreach ($params['message'] as $message) {
                    if ($message->getIdCreator() === $_SESSION['id']) {?>
                        <a href="/Petit_Boudin/account/<?php echo $message->getPseudo(); ?>"><?php echo $message->getPseudo(); ?></a><br>
                        <?php echo htmlspecialchars($message->getDate()); ?> <br> <!-- les br sont des techniques de slague pour revenir à la ligne -->
                        <?php echo htmlspecialchars($message->getMessage()); ?><br>
                        <?php echo htmlspecialchars($message->getRetweet()['nombre']); ?>
                        <?php echo htmlspecialchars($message->getLike()['nombre']); ?>
                        <form action="/Petit_Boudin/CMessage" method="post">
                            <input type="hidden" value="<?php echo $message->getId() ?>" name="MessageId">
                            <button type="submit">modifier</button>
                        </form> <br>
                <?php }
                    else { ?>
                        <a href="/Petit_Boudin/account/<?php echo $message->getPseudo(); ?>"><?php echo htmlspecialchars($message->getPseudo()); ?></a><br>
                        <?php echo htmlspecialchars($message->getDate()); ?> <br> <!-- les br sont des techniques de slague pour revenir à la ligne -->
                        <?php echo htmlspecialchars($message->getMessage()); ?><br>
                        <a href="/Petit_Boudin/retweet/<?php echo $message->getId(); ?>"><?php echo htmlspecialchars($message->getRetweet()['nombre']); ?></a>
                        <a href="/Petit_Boudin/like/<?php echo $message->getId(); ?>"><?php echo htmlspecialchars($message->getLike()['nombre']); ?></a><br><br>
                   <?php }
                }
            }
        ?>

        <!-- Affichage des followers et des follows -->
        <?php
            echo "Follows:";
            if (isset($params['follows'])) {
                foreach ($params['follows'] as $follow) { ?>
                    <br> <a href="/Petit_Boudin/account/<?php echo $follow->getPseudo(); ?>"><?php echo htmlspecialchars($follow->getPseudo()); ?></a>
                <?php } }

            echo "<br> Followers:";
            if (isset($params['followers'])) {
                foreach ($params['followers'] as $follower) { ?>
                    <br> <a href="/Petit_Boudin/account/<?php echo $follower->getPseudo(); ?>"><?php echo htmlspecialchars($follower->getPseudo()); ?></a>
                <?php } }
        ?>
    </body>
</html>