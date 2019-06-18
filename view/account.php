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
        <?php echo htmlspecialchars($params['profile']->getPseudo()); ?>

        <!-- Follo Unfollow -->
        <?php
            if (isset($params['isFollow'])) {
                if ($params['profile']->getId() !== $_SESSION['id']) {
                    if (!$params['isFollow']) { ?>
                        <form action="/Petit_Boudin/follow" method="post">
                            <input type="hidden" name="id" value="<?php echo $params['profile']->getId()?>">
                            <button type="submit">Follow</button>
                        </form>
                    <?php }
                    else { ?>
                        <form action="/Petit_Boudin/unfollow" method="post">
                            <input type="hidden" name="id" value="<?php echo $params['profile']->getId()?>">
                            <button type="submit">UnFollow</button>
                        </form>
                    <?php }
                }
            }
        ?>

        <!-- deconnexion du compte et profile -->
        <a href="/Petit_Boudin/LogOut">Log out</a>
        <a href="/Petit_Boudin/profile">Back to Profile</a>

        <!-- Création des messages -->
        <form action="/Petit_Boudin/AMessage" method="post">
            <input type="text" name="message" placeholder="message" maxlength="140">
            <button type="submit">Send</button>
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
                    <a href="/Petit_Boudin/account/<?php echo $message->getPseudo(); ?>"><?php echo htmlspecialchars($message->getPseudo()); ?></a><br>
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
                    <a href="/Petit_Boudin/retweet/<?php echo $message->getRetweet()['nombre']; ?>"><?php echo htmlspecialchars($message->getRetweet()['nombre']); ?></a>
                    <a href="/Petit_Boudin/like/<?php echo $message->getLike()['nombre']; ?>"><?php echo htmlspecialchars($message->getLike()['nombre']); ?></a> <br>
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