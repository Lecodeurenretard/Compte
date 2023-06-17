<?php 


/**
 * @return bool Retourne si la variable existe.
 */
function hasValue(&$var) : bool{
    return (isset($var) && $var != '');
}


/**
 * Utilise hasValue() sur toutes les variables entrée
 * @param mixed $vars les variables à vérifier.
 * @return array Retourne un tableau de booléen sur si les variables existent ou pas.
 *      Le tableau est classé dans l'ordre  où les variables ont été données.
 */
function hasValueTab(&...$vars) : array //sinn on vérifie pas la variable en elle même
{
    $exists = [];
    foreach ($vars as $i =>$var) { 
        $exists[$i] = hasValue($var);
    }
    return $exists;
}

/**
 * Applique la porte AND sur l'array donné.
 * @param array $tab Le tableau de BOOLEAN à appliquer la porte.
 * @return bool|null Si l'un des éléments n'est pas de type bool alors retourne NULL
 *  sinon vérifie que tous les bools du tableau sont true.
 */

function andTab(array $tab): ?bool {
    $ok = true; //ça ne change pas l'issue de la fonction
    foreach ($tab as $elem) {
        if (gettype($elem)!="boolean") {return null;}
        $ok = $elem && $ok;
    }
    return $ok;
}


/**
 * Applique la porte OR sur la liste $tab.
 * @param array $tab le tableau sur lequel la porte soit être éffectuée.
 * @return ?bool Si l'un des éléments n'est pas de type bool alors retourne NULL 
 * sinon retourne si au moins un élément du tableau est true. 
 */

function orTab(array $tab) : ?bool
{
    $ok = false; //ça ne change pas l'issue du prog
    foreach ($tab as $elem) {
        if (gettype($elem)!="boolean") {return null;}
        $ok = $elem || $ok;
    }
    return $ok;
}


/**
 * @return mixed Retourne un élément d'une liste au hazard ou du fun.
 * @param array $list La liste dans laquelle il faut chercher.
 * @param bool $noTroll Si vrai ne retourne pas quelque chose de fun (en str).
 */
function getRand(array $list, bool $noTroll = false){
    if(rand(0, 9999999) == 69 && !$noTroll){return "You was expecting a pseudo but it was me, DIO!!";}
    return $list[rand(0, count($list)-1)];
}

/**
 * Encarde $what avec $encadre (Par exemple, between("jo", " DIO ") == " DIO jo DIO ").
 * @param string $what La string à encadrer
 * @param string $encadre Ce qui dois encadrer la string.
 */

function between(string $what, string $encadre = '"') : string
{
    return $encadre . $what . $encadre;
}

$compteur = 0;
 
/**
 * Assigne une valeur dans $_SESSION avec une valeur de $_POST.
 * @param string $name La clef du $_POST ($_POST[$name]), ce sera aussi la clef de $_SESSION.
 * @param int &$compter le compteur (on compte combien de fois c'est bien fait).
 * @param bool $NoSpecial Un paramètre à passer à deleteSpecialChar().
 * @param string $whatElse La valeur à assigner dans la $_SESSION si $_POST[$key] n'est pas défini.
 * @param string $drapeaux D'éventuelles instructions à passer à deleteSpecialChar().
 */
function assignValue(string $name, int &$compter, bool $NoSpecial = true, string $whatElse = '', string $drapeaux = '') : void 
{
    if(hasValue($_POST[$name])){
        $_SESSION[$name] = deleteSpecialChar($_POST[$name], $NoSpecial, $drapeaux); //on se prémunit contre la faille XSS
        $compter++;
    }else {
        $_SESSION[$name] = $whatElse;
    }
}

/**
 * assignValue() mais la clef du $_POST n'est pas la même que celle de $_SESSION.
 * @param string $name La clef de la $_SESSION ($_SESSION[$name]).
 * @param string $postName La clef du $_POST ($_POST[$postName]).
 * @param int &$compter le compteur (on compte combien de fois c'est bien fait).
 * @param bool $NoSpecial Un paramètre à passer à deleteSpecialChar().
 * @param string $whatElse La valeur à assigner dans la $_SESSION si $_POST[$key] n'est pas défini.
 * @param string $drapeau D'éventuelles instructions à passer à deleteSpecialChar().
 */
function assignValue2names(string $name, string $postName, int &$compter, bool $NoSpecial, string $whatElse = '', string $drapeaux = '') : void
{
    if (hasValue($_POST[$postName])) {
        $_SESSION[$name] = deleteSpecialChar($_POST[$postName], $NoSpecial, $drapeaux);
        $compter++;
    }else {
        $_SESSION[$name] = $whatElse;
    }
}

/*ça bug trop

function StrToStr(string $var, string|null $type = null) : string{  //sert à réduire arrayToString()
    $type = ($type == null) ? gettype($var) : $type ;   //si $type n'estpas défini alors on le défini
    
    if($type == "string"){
        return '"' . $var . '"';
    }
    return $var;
}

function arrayToString(array $tab) : string|null //retourne l'array sous forme de string pour être en js
{
    $string = "[";
    
    foreach ($tab as $key => $elem) {
        $type = gettype($elem);
        $typeKey = gettype($key);

        if ($typeKey !== "string" && $typeKey !== "integer") { 
            return null;
        }elseif($typeKey === "string"){
            $string .= StrToStr($key, "string") . " : " . $elem ;    //on met la clef
        }
        /*note: $tab doit être continu, c'est à dire que si  $tab[$i] existe alors $tab[j] existe pour tout j appartenant à [0; $i] et appartenant à N

        $string = ', ';
    }
    $string = sprintf("%s\b\b", $string); //on enlève la derniere virgule
    $string .= ']';
    return $string;
}*/


/**
 * Permet d'enlever les caractères spéciaux d'une chaine.
 * @param string $str La chaine qu' il faut regarder.
 * @param bool $allSpecials Indique si il faut inclure les caractères spéciaux n'ayant pas d'importance en SQL.
 * @param string $flags Indique si il faut un traitement spécial. 
 * Pour l'instant ce paramètre réagit à:
 *  -"date": On enlève le slash de la liste;
 *  -"nom": On enlève les tirets de la liste (mais pas les doubles tiret '--');
 *  -"accents": Enlève tous les caractères à accents et à trémats ('Ä', 'ì', 'ö', ...) de la liste;
 *  -"espaces": Enlève les espaces de la liste;
 *  -"numbers": Enlève les nombres;
 *  -"spaces": Permet les espaces d'exister
 *
 * @return string Retourne une chaine "néttoyée".
 */
function deleteSpecialChar(string $str, bool $allSpecials = true, string $flags='') : string  //enlève les caractères spéciaux
{
    if ($str == '') {
        return '';
    }

    $return = str_replace('<', '&lt;', $str);   //htmlspecialchars() ne transforme pas que ça
    $return = str_replace('>', '&gt;', $return);
    
    $aEnlever = ["'", '"', '\\', '{', '}', '(', ')', '^', '=', '+', '*', '.', ';', '/', ':', '!', '%', '--', '#', ',', ' ', '  '];  //le double-espace
    
    if ($allSpecials) {
        $return = str_replace(['-', ' '], '_', $return); 
        $aEnlever = array_merge(['§', 'µ', '¨', '@', '$', '£', '¤', '°', '`', '~', 'é', 'è', 'à', 'ù', 'ì', 'ò', 'ä', 'ë', 'ï', 'ö','ü', 'À', 'È', 'Ì', 'Ò', 'Ù', 'Ä', 'Ë', 'Ï', 'Ö', 'Ü'], $aEnlever);
    }
    if (str_contains($flags, "date")) {
       $aEnlever = array_splice($aEnlever, array_search('/', $aEnlever), 1);   //enlève le slash du tableau
       $aEnlever = array_splice($aEnlever, array_search('-', $aEnlever), 1);
    }
    if (str_contains($flags,"nom")) {
        $aEnlever = array_splice($aEnlever, array_search('-', $aEnlever), 1);   //je ne m'inqiète pas car les nom sont entre guillemets
        $flags .= ", accents";      //les noms contiennent des accents
    }
    if(str_contains($flags, "accents")){        //on enlève...
        $aEnlever = array_splice($aEnlever, array_search('é', $aEnlever), 1);   //...Les accents   
        $aEnlever = array_splice($aEnlever, array_search('è', $aEnlever), 1);
        $aEnlever = array_splice($aEnlever, array_search('à', $aEnlever), 1);
        $aEnlever = array_splice($aEnlever, array_search('ì', $aEnlever), 1);
        $aEnlever = array_splice($aEnlever, array_search('ò', $aEnlever), 1);
        $aEnlever = array_splice($aEnlever, array_search('ù', $aEnlever), 1);

        $aEnlever = array_splice($aEnlever, array_search('À', $aEnlever), 1);
        $aEnlever = array_splice($aEnlever, array_search('È', $aEnlever), 1);
        $aEnlever = array_splice($aEnlever, array_search('Ì', $aEnlever), 1);
        $aEnlever = array_splice($aEnlever, array_search('Ò', $aEnlever), 1);
        $aEnlever = array_splice($aEnlever, array_search('Ù', $aEnlever), 1);

        $aEnlever = array_splice($aEnlever, array_search('ä', $aEnlever), 1);   //...les '¨'
        $aEnlever = array_splice($aEnlever, array_search('ë', $aEnlever), 1);
        $aEnlever = array_splice($aEnlever, array_search('ö', $aEnlever), 1);
        $aEnlever = array_splice($aEnlever, array_search('ü', $aEnlever), 1);

        $aEnlever = array_splice($aEnlever, array_search('Ä', $aEnlever), 1);
        $aEnlever = array_splice($aEnlever, array_search('Ë', $aEnlever), 1);
        $aEnlever = array_splice($aEnlever, array_search('Ï', $aEnlever), 1);
        $aEnlever = array_splice($aEnlever, array_search('Ö', $aEnlever), 1);
        $aEnlever = array_splice($aEnlever, array_search('Ü', $aEnlever), 1);
    }
    if (str_contains($flags, 'espaces')) {
        $aEnlever = array_splice($aEnlever, array_search(' ', $aEnlever), 1);
    }
    if (str_contains($flags, "numbers")) {
        array_push($aEnlever, '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    }
    
    
    return str_replace($aEnlever,'', $return);
}


/**
 * Crée un nouveau Compte en l'enregistrant dans la BDD.
 * Si les paramètres de $nom à $naissance nesont pas précisés ou égals à null alors la fonction utilisera ifEmptySet() pour Aller les chercher.
 * @param ?bool &$jobDone Si l'opération s'est bien passée.
 * @return Compte|false Si il existe déjà un compte avec les mêmes identifiants alors retourne false, 
 * Sinon retourne un objet Compte correspondant.
 */


/**
 * Si empty($var) alors on la set à $_SESSION[$sessionName].
 */
function ifEmptySet(&$var, string $sessionName): void{    //pour raccoucir juste au dessus 
    if(empty($var)){
        $var = $_SESSION[$sessionName];
    }
}


?>