<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Inscription</title>
        <link rel="stylesheet" type="text/css" href="../Petit_Boudin/Css/GlobalStyle.css">
        <link rel="stylesheet" type="text/css" href="../Petit_Boudin/Css/StyleConnex-Inscrip.css">
        <link rel="SHORTCUT ICON" href="../Petit_Boudin/Images/LOGO.ico">
    </head>

<header>
    
    <img src="../Petit_Boudin/Images/LOGO.png">

</header>

    <body>
        <form action="/Petit_Boudin/AccountCr" method="post">
            <section class="content bgcolor-3">
                 <h1>Inscription</h1>
                <span class="input input--manami">
                    <input class="input__field input__field--manami" type="text" name="FirstName" placeholder="First Name" value="<?php if (isset($params['back'])) echo $params['back']['FirstName']; ?>" id="input-1" />
                    <label class="input__label input__label--manami" for="input-1">
                        <span class="input__label-content input__label-content--manami">First Name</span>
                    </label>
                </span>
                <br/>
                <span class="input input--manami">
                    <input class="input__field input__field--manami" type="text" name="LastName" placeholder="Last Name" value="<?php if (isset($params['back'])) echo $params['back']['LastName']; ?>" id="input-2" />
                    <label class="input__label input__label--manami" for="input-2">
                        <span class="input__label-content input__label-content--manami">Last Name</span>
                    </label>
                </span>
                <br/>
                <span class="input input--manami">
                    <input class="input__field input__field--manami" type="text" name="Mail" placeholder="Mail" value="<?php if (isset($params['back'])) echo $params['back']['Mail']; ?>" id="input-3" />
                    <label class="input__label input__label--manami" for="input-3">
                        <span class="input__label-content input__label-content--manami">Mail</span>
                    </label>
                </span>
                <br/>
                <span class="input input--manami">
                    <input class="input__field input__field--manami" type="text" name="Pseudo" placeholder="Pseudo" value="<?php if (isset($params['back'])) echo $params['back']['Pseudo']; ?>" id="input-4" />
                    <label class="input__label input__label--manami" for="input-4">
                        <span class="input__label-content input__label-content--manami">Pseudo</span>
                    </label>
                </span>
                <br/>
                <span class="input input--manami">
                    <input class="input__field input__field--manami" type="password" name="Password" placeholder="Password" id="input-5" />
                    <label class="input__label input__label--manami" for="input-5">
                        <span class="input__label-content input__label-content--manami">Password</span>
                    </label>
                </span>
            </section>

            <button type="submit">Send</button>
        </form>
        <br/>

        <a href="/Petit_Boudin/">Return</a>
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
