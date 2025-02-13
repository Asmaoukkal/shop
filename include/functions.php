<?php
// fonction utulisateur authenthique
//fonction permettant de savoir si l'utilisateur est authentique sur le site 
function userConnected() {

    //si l'indice 'user' dans le fichier de session n'est pas defenie cela veut dire que l'internaute n'est pas passepar la page connection et n'est pas authentifie
    if (isset($_SESSION['user'])) 
        return true;
    else
        return false;//on retourn true si l'indice 'user' est defenie dans la session
}
// fonction admenistrateur authenthique
function adminConnected() {
    // echo 'test';
    // fonction permettant de savoir si un admenistrateur est authentifie sur le site 
     //si a l'indice 'role' dans la session, la valeur est differente d'admine, cela veut dire que c'est un utulisateur lambda(non admin), on retourn false
    if (userConnected() && $_SESSION['user']['roles'] === 'admin') return true; 

    return false;//on retourn true si dans la session le roles est bien 'admin
}
?>