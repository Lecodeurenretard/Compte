function see(hidden /* : bool*/) { // change le mot de passe 
    const inPassword = document.getElementById("password"),
            butt = document.getElementById("changeMdp");

        if (hidden) {
            inPassword.setAttribute("type", "text");
            butt.innerText = "cacher";
            butt.setAttribute("onclick", "see(false)");
        } else {
            inPassword.setAttribute("type", "password");
            butt.innerText = "voir"
            butt.setAttribute("onclick", "see(true)"); 
        }
}
function calculAge(ageStr /* : string*/){     // :  int
        const date_naissance = new Date(ageStr),
            now = new Date();

        let age = now.getFullYear() - date_naissance.getFullYear();

       /* //on regarde si l'anniversaire est passé
        const mois = date_naissance.getMonth() > now.getMonth(),
            mois2 = date_naissance.getMonth() == now.getMonth(),
            jour = date_naissance.getDate() > now.getDate();
        
        //si il n'est pas passé
        if (mois) {
            return age--;
        }else if(jour && mois2){
            return age--;
        }*/

        return age; //C pas précis mais tant pis
}



        function verif(){
        const inNom = document.getElementById("nom"),
            inPassword = document.getElementById("password"),
            naissance = document.getElementById("naissance").value,
            sign = document.getElementsByClassName("noSign");

        const nom = inNom.value,
            mdp = inPassword.value,
            nomMin = parseInt(inNom.getAttribute("minlength")),
            mdpMin = parseInt(inPassword.getAttribute("minlength"));
    
        let good = (nom != '' && nom.length >= nomMin); //on peut envoyer l'élément?
        console.info("On peut envoyer le nom: " + good.toString()); //oui ou non
        let bad = false; //on peut envoyer le formulaire
        if (!good) {
            inNom.value = '';
            bad = true;
        }
    
        good = (mdp != '' && mdp.length >= mdpMin);
        console.info("on peut envoyer le mot de passe: " + good.toString());
        if (!good) {
            inPassword.value = '';
            bad = true;
        }
    
        for(i = 0; i > sign.length; i++){   //good est bon si la chaine ne comporte que des lettres
            good = true;
            for (let u = 0; u < sign[i].length; u++) {
                let letter = sign[i].value[u];  //un tableau de str
                if(letter.match(/[a-z]/i) == false && letter !='-'){    //une lettre ou un tiret
                    good = false;
                    bad = true;
                    console.info("stopped at " + u.toString() + " of input number " + i.toString());
                    break;
                }
            }
            if(!good){break;}
        }
        console.info("les champs sont correct(pas de caractères spéciaux): " + good.toString());
        console.log("On peut envoyer le formulaire: " + (!bad).toString());
    
        if(calculAge(naissance) < 7){//l'utilisateur déclare avoir moins de 7  ans
            console.warn("L'utilisateur dis avoir " + calculAge(naissance).toString() + "ans, c'est pas net...");
        }




        if (!bad) {
            let inputs = document.getElementsByTagName("input");
    
            for(i=0; i<inputs.length; i++){
                let id = inputs[i].getAttribute("id");
                inputs[i].setAttribute("name", id);
            }
    
            /* Array.prototype.forEach(input => {
                let id=input.getAttribute("id");
                input.setAttribute("name", id);
             }, inputs);*/
    
    
            document.getElementById("verifier").removeAttribute("onclick");
            document.getElementById("verifier").setAttribute("type", "submit");
            document.getElementById("verifier").setAttribute("form", "data");
        }
        return;
    }