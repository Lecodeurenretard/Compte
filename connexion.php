<?php 
    session_start();
    define("firstTime", (isset($_SESSION["page"]) &&$_SESSION["page"] != "connexion.php"));   //on considère que c'est sa 1ere fois si il vien d'arriver sur la page
    $_SESSION["page"] = "connexion.php";
    require("functions.php");
     
?>

<!DOCTYPE html>
<head>
    <link href="style.css" rel="stylesheet" type="text/css" />
    <style>
        #infoForm{
            border: 3px black solid;
            border-radius: 15px;

            padding: 10px 40px;
        }

        noscript{
            margin: 30px;
            font-size: xxbig;
        }
    </style>
<script>//src="main.js"
    function see(hidden) {
        const butt = document.getElementById("changeMdp");


        if(hidden) { 
            inPassword = butt.parentElement.querySelectorAll("input[type=\"password\"]");
            for (let i = 0; i < inPassword.length; i++) {
                inPassword[i].setAttribute("type", "text");
            }

            butt.innerText = "cacher";
            butt.setAttribute("onclick", "see(false)");   
            
        } else {
            
            for (let i = 0; i < inPassword.length; i++) {
                inPassword = butt.parentElement.querySelectorAll("input[type=\"text\"]");
                inPassword[i].setAttribute("type", "password");
            }

                butt.innerText = "voir";
                butt.setAttribute("onclick", "see(true)");   
        }
    }
</script>
    <script>
        function changeIdentification(way){ //switch entre l'identification par le nom et par le pseudo
            const input = document.getElementById(way);
            if (way == "pseudonyme") {
                const input2 = document.createElement("input"),
                    label = document.createElement("label"),
                    text = document.createElement('p');

                text.innerText = "entrez votre nom";
                input.setAttribute("placeholder", "ex: Joseph");
                input2.setAttribute("placeholder", "ex: Joestar");

                input.previousElementSibling/*le p du 1er input*/.innerText = "entrez votre prénom";

                input.id = "prenom";
                input2.id = "nom";

                label.appendChild(text);
                label.appendChild(input2);
                document.getElementById('id').insertBefore(label, document.getElementById("change"));

                document.getElementById("change").innerText = "entrer votre pseudo"
                document.getElementById("change").setAttribute("onclick", "changeIdentification('" + input.id + "');");
            }else if(way == "prenom"){
                document.getElementById("nom").parentElement/*le label*/.remove();

                input.setAttribute("placeholder", "ex: LeRetardatN");
                input.id = "pseudonyme";
                input.previousElementSibling.innerHTML = "entrez votre pseudo";

                document.getElementById("change").innerText = "enter votre nom";
                document.getElementById("change").setAttribute("onclick", "changeIdentification('" + input.id + "')");
            }

            changeAuthName();
        }

        function changeAuthName() { //set l'attribut name à la valeur de l'id de chaque input
            const tab_input = document.getElementsByTagName("input");
            for (let i = 0; i < tab_input.length; i++) {
                tab_input[i].setAttribute(
                    "name",
                    tab_input[i].id
                ); 
            }
        }
        
    </script>
</body>Password
</head>
<body>

    <noscript>
        Active les script dans les settings sinon ça va moins bien marcher forcément...
    </noscript>

    <h1>Qui êtes-vous ??</h1>
    <form method="POST">
        <section id="id">
            <label><p>entrez votre pseudo</p> <input id="pseudonyme" placeholder="ex: LeRetardatN" /></label>
            <button type="button" onclick="changeIdentification('pseudonyme')" id="change">entrer le nom</button>
        </section>

        <section id="password">
            <label><p>entrez votre mot de passe</p> <input id="mdp" type="password" /></label>
            <button type="button" onclick="see(true)" id="changeMdp">voir</button>
        </section>

    <button type="submit">submit</button>
    </form>
    <script>
        changeAuthName();
    </script>

    
    Vous n'avez pas de compte? <a href="page.php">Créez-en un ici</a>.
    
    <?php
        if (firstTime) {    //on n'exectue pas la balise si l'utilisateur vien d'arriver sur la page
            return;
        }
        define("styleOK","
            <style>
                #infoForm{
                background-color: green;
                }
            </style>
        ");
        define('styleNO', '
                <style>
                    #infoForm{
                        background-color: red;
                    }
                </style>
        ');
        
        if(hasValue($_POST["pseudonyme"]) && Compte::existsAs("pseudo", $_POST["pseudonyme"], "string")){   //on vérifie si on regarde le pseudo ou le nom

            define('pseudoOk', true);  //on défini une constante (const ne marche pas vraisemblablement)
            define("nomOk", false);
            define('ID', $_POST["pseudonyme"]);
            

        }elseif (
            andTab(hasValueTab($_POST["nom"], $_POST["prenom"]))
            && Compte::existsAs("nom", $_POST["nom"], "string")
            && Compte::existsAs("prenom", $_POST["prenom"], "string")       //quand le le nom est valide
            ){
            define('nomOk', true);
            define('pseudoOK', false);
            
            define("ID", $_POST["prenom"]);
        }else{
            echo('
                <div id="infoForm">
                    Désolé, mais vous n\'avez pas remplis le formulaire correctement, les champs du nom, du prénom ou du pseudo ont dû ne pas être remplis.<br />
                    Veuillez reéssayer.
                </div>' . styleNO
            );
            return;
        }

        $passwordOk = Compte::existsAs("password", $_POST["mdp"], "string");

        if($passwordOk && nomOk ){
            logN($_POST["nom"], ID, $_POST["mdp"]);
        }elseif($passwordOk && pseudoOk ){
            logP(ID, $_POST["mdp"]);
        }

        if(verifConnected()){
            $user = compteConnected();
            echo('
                <div id="infoForm">
                    Vous êtes bien connecté.e ' .  $user->prenom . ' ' .  $user->nom . " ou " . $user->pseudo .'.<br />
                    Merci de vous être créé un compte.
                </div>' . styleOK
            );
        }else{
            echo('
                <div id="infoForm">
                    L\'un des champs est erroné, veuillez réessayer.
                </div>' .styleNO
            );
        }
        ?>    
<button onclick="window.location.replace('eraseData.php')">déconnexion</button>
</body>