<?php 
    session_start();
    require("functions.php");
    $_SESSION["page"] = "page.php";
    $pseudos = [
        "kikouLol",
        "Shyarin Joestar",
        "Gfaim78",
        "bot",
        "JoDu20",
        "Quento",
        "Ronan_bloque_ses_compagnons",
        "thanosSnaps",
        "Abbacio-kun",
        "Yuri_best_waifu",
        ";)",
        "Made_in_Loir-et-Cher",
        "noPseudo",
        "SpeedWagon_is_a_waifu",
        "tsun_not_dere",
        "mon_poke",
        "sans-pseudo-du-69",
        "highway_to_heaven",
        "c'etais_myeu_avent",
        "pet_gaze",
        "your_Funny_Valentine",
        "back_in_blue",
        "moi",
        "moi5001",
        "myPseudoIsTheBest",
        "RonanKishibe",
        "E",
        "Jo",
        "Panacotta_Fugo_the_best",
        "lesTroisMeilleuresLettres_DIO",
        "moimoi",
        "Jai+de25ans"
    ];
    define("long", count($pseudos) - 1);
    if (empty($_SESSION["pseudo"])) {   //sinon la boucle génere plein d'erreurs.
        $_SESSION["pseudo"] = null;
    }
    foreach ($pseudos as $alea) {
       $tab[] = ($alea==$_SESSION["pseudo"]);
    }

    if(empty($_SESSION["pseudo"]) && orTab($tab)) {        //si il n'est pas déjà set par le scr alors on le set nous même
        $_SESSION["pseudo"] = $pseudos[random_int(0, long)];
    }

    $pseudos["longueur"] = long;

    $compteur = 0;

    assignValue("nom", $compteur, true, "", "nom");
    assignValue("prenom", $compteur, true, "quelqu'un", "nom");
    assignValue("password", $compteur, false, "un mot de passe");
    assignValue("pseudo", $compteur, false);
    assignValue2names("date_of_birth", "naissance", $compteur, true,"on ne sait quand", "date");


    /*echo("<script>
            var aleaPseudo = " . arrayToString($pseudos) . ';'
            . "
    </script>");*/
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Create your own account!</title>
        <style>
           <?php require("style.css"); ?>
        </style>
    </head>

    <body>
            <noscript>
                Active les script dans les settings sinon ça va moins bien marcher forcément...
            </noscript>

        <?php
            echo("<p class=\"big\">" . "Bonjour " . $_SESSION["prenom"] . ' ' . $_SESSION["nom"] . "( " . $_SESSION["pseudo"] . " )" . "!<br /> Vous êtes né.e le " . $_SESSION["date_of_birth"] . ". Voulez-vous créer un autre compte?" . "<br />");
            echo("Votre mot de passe est " . '"' . $_SESSION["password"] . '"</p>');
        ?>
        <h1>New account</h1>

<script> //src="main.js"
    <?php require("main.js"); ?>

    /* fonctionnalité abandonée

    function randPseudo() {
        let nb = Math.floor(Math.random() * (aleaPseudo["longueur"]+1));
        nb = Math.round(nb);
        return aleaPseudo[nb];
    }
    function setAlPseudo(){ //met un pseudo aléatoire
        document.getElementById("pseudo").value = randPseudo();
    }

    document.getElementById("pseudo").setAttribute("placeholder", "ex: " + randPseudo());*/
</script>
        <form method="POST" id="data">
            <label title="12 lettres maximum"><p>Votre prénom</p><input id="prenom" placeholder="ex: Jonathan" maxlength="12" class="noSign" /></label>

            <label title="2 lettres minimum, 15 lettres maximum" ><p>Votre nom</p><input id="nom" placeholder="ex: Joestar" minlength="2" maxlength="12" class="noSign" /></label>

            <label title="15 lettres maximum"><p>Votre identifant de connexion (pseudo)</p><input id="pseudo" maxlength="15" placeholder="ex: moi,LePseudo" />
               <!-- Pas fini <button onclick="setAlPseudo()" type="button">Choisir pour moi</button>--></label>

            <label title="Entre 8 et 22 (inclus) charactères, les charactères spéciaux sont autorisés." ><p>Votre mot de passe:</p><input id="password" type="password" minlength="8" maxlength="22" /> 
            <button id="changeMdp" onclick="see(true)" type="button" >voir</button> </label>

            <label><p>Votre date de naissance</p><input type="date" id="naissance" /></label>
        </form>
        <button id="verifier" onclick="verif()" type="button" >Soumettre</button><!--C'est pour que la page ne se rafraichisse pas quand on appuie dessus-->
        <button onclick="window.location.replace('eraseData.php')">déconnexion</button>
        <?php if($compteur == 5 || $compteur == 0): ?>
            Vous avez déjà un compte? <a href="connexion.php">connectez-vous</a>
        <?php else: 
            echo "error " . $compteur . "lignes trouvées."; 
        endif; ?>
       
</html>