<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Connection</title>
        <link rel="stylesheet" type="text/css" href="../Petit_Boudin/Css/GlobalStyle.css">
        <link rel="stylesheet" type="text/css" href="../Petit_Boudin/Css/StyleConnex-Inscrip.css">
        <link rel="SHORTCUT ICON" href="../Petit_Boudin/Images/LOGO.ico">
    </head>

    <header>
        
        <img src="../Petit_Boudin/Images/LOGO.png">

    </header>

    <body>
        <!-- Messages d'erreur -->
        <?php
            if (isset($params['error'])) {
                echo $params['error'];
            }
        ?>
       
        <!-- Form pour le login -->
        <form action="/Petit_Boudin/login" method="post">
            <section>
                <h1>Connection</h1>
                <span class="input input--manami">
                    <input class="input__field input__field--manami" ttype="text" name="login" value="<?php if (isset($params['login'])) echo $params['login']; ?>" placeholder="Pseudo" id="input-1" />
                    <label class="input__label input__label--manami" for="input-1">
                        <span class="input__label-content input__label-content--manami">Pseudo</span>
                    </label>
                </span>
                <br/>
                <span class="input input--manami">
                    <input class="input__field input__field--manami" type="password" name="password" placeholder="Password" id="input-2" />
                    <label class="input__label input__label--manami" for="input-2">
                        <span class="input__label-content input__label-content--manami">Password</span>
                    </label>
                </span>
            </section>

               <button type="submit">Log In</button>
        </form>

        <!-- Bouton qui amenera vers la création de compte -->
        <a href="/Petit_Boudin/CreateAccount">Register</a>
        <br/>
    </body>

    <script src="../Js/classie.js"></script>
        <script>
            (function() {
                // trim polyfill : https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/Trim
                if (!String.prototype.trim) {
                    (function() {
                        // Make sure we trim BOM and NBSP
                        var rtrim = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;
                        String.prototype.trim = function() {
                            return this.replace(rtrim, '');
                        };
                    })();
                }

                [].slice.call( document.querySelectorAll( 'input.input__field' ) ).forEach( function( inputEl ) {
                    // in case the input is already filled..
                    if( inputEl.value.trim() !== '' ) {
                        classie.add( inputEl.parentNode, 'input--filled' );
                    }

                    // events:
                    inputEl.addEventListener( 'focus', onInputFocus );
                    inputEl.addEventListener( 'blur', onInputBlur );
                } );

                function onInputFocus( ev ) {
                    classie.add( ev.target.parentNode, 'input--filled' );
                }

                function onInputBlur( ev ) {
                    if( ev.target.value.trim() === '' ) {
                        classie.remove( ev.target.parentNode, 'input--filled' );
                    }
                }
            })();
        </script>

    <footer>
        <p></p>
    </footer>
</html>
