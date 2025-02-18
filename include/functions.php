<?php 
// ------ FONCTION UTILISATEUR AUTHENTIFIE
// Fontion permettant de savoir si l'utilisateur est authentifié sur le site
function userConnected(){
    // Si l'indice 'user' dans le fichier de session n'est pas définit, cela veut dire que l'internaute n'est pas passé par la page connexion et n'est pas authentifié
    if(isset($_SESSION['user']))
        return true;
    else
        return false; // on retourne true si l'indice 'user' est définit dans la session
}

// ------ FONCTION ADMINISTRATEUR AUTHENTIFIE
// Fonction permettant de savoir si un administrateur est authentifié sur le site
// Cette fonction est utile pour sécuriser les pages du back-office (admin) et empêcher un utilisateur de se connecter à une page admin sans être authentifié en tant qu'administrateur 
function adminConnected(){
    // Si à l'indice 'roles' dans la session, la valeur est admin, cela veut dire que dire que c'est un administrateur on retourne true
 // on retourne true si dans la session le roles est 'admin' et que l'utilisateur est connecté 
    if(userConnected() && $_SESSION['user']['roles'] == 'admin') 
    // on retourne true si dans la session le roles est 'admin' et que l'utilisateur est connecté
        return true;
    else
    // on retourne false si dans la session le roles n'est pas 'admin'
        return false; // on retourne false si dans la session le roles n'est pas 'admin'
}

/*
// Exemple de tableau de session 'cart'
//cest un tableau array multidimensionnel stocké dans la session quant l'utilisateur ajoute un produit dans le panier cest nous qui le créons dans la session de l'utilisateur pour stocker les informations des produits ajoutés dans le panier
    cart => [
        id_product => [
            0 => 15,
            1 => 7
        ]

        title => [
            0 => Chemise bleu,
            1 => Pull vert
        ]
    ]
*/

// ------- FONCTION CREATION PANIER SESSION
function createCart(){
    // Si l'indice 'cart' n'est pas définit dans la session de l'utilisateur, cela veut dire que l'intilisateur n'a ajouté aucun produit dans le panier, alors on crée les différents tableaux dans la session
    if(!isset($_SESSION['cart'])){
        $_SESSION['cart'] = [];
        $_SESSION['cart']['id_product'] = [];
        $_SESSION['cart']['title'] = [];
        $_SESSION['cart']['picture'] = [];
        $_SESSION['cart']['reference'] = [];
        $_SESSION['cart']['quantity'] = [];
        $_SESSION['cart']['price'] = [];
    }
}

// ------- FONCTION AJOUTER PRODUIT DANS PANIER SESSION
function addProductToCart($id_product, $title, $picture, $reference, $quantity, $price){
    createCart(); // On contrôle si le panier existe ou non dans la session

    // On contrôle si l'id du produit que l'on tente d'ajouter dans le session panier existe déjà
    $positionProduct = array_search($id_product, $_SESSION['cart']['id_product']);
    // var_dump($positionProduct);

    // Si la valeur de $positionProduct est différente de false, cela veut dire que l'id_product existe dans le panier, on modifie seulement la quantité du produit
    if($positionProduct !== false){
        $_SESSION['cart']['quantity'][$positionProduct] += $quantity;
    }else{
        // Sinon l'id n'est pas dans le session, on crée une nouvelle ligne dans le panier
        // les [] vide permettent de créer des indices numérique dans les tableaux Array
        $_SESSION['cart']['id_product'][] = $id_product;
        $_SESSION['cart']['title'][] = $title;
        $_SESSION['cart']['picture'][] = $picture;
        $_SESSION['cart']['reference'][] = $reference;
        $_SESSION['cart']['quantity'][] = $quantity;
        $_SESSION['cart']['price'][] = $price;
    }
}

// ------- FONCTION CALCUL MONTANT TOTAL DU PANIER
//function permettant de calculer le montant total du panier
function totalAmount(){
    //total initialisé à 0
    $total = 0;
    //boucle permettant de parcourir le panier et de calculer le montant total du panier en multipliant la quantité par le prix et en l'ajoutant à la variable $total à chaque tour de boucle et on arrondit le montant à 2 chiffres après la virgule avec la fonction round() 
    for($i = 0; $i < count($_SESSION['cart']['id_product']); $i++){ 
        $total += $_SESSION['cart']['quantity'][$i] * $_SESSION['cart']['price'][$i];
    }
    //retourne le montant total du panier
    return round($total, 2);
}

// ------- FONCTION LIENS ACTIFS NAV
//                /PHP/shop/product.php
function activeLink($url){
    if($_SERVER['PHP_SELF'] == $url)
                echo ' active';
        }
                /*******FUNCTION SUPPRESSION ARTICLE PANIER */
                // Fonction permettant de supprimer un produit du panier
function removeProductTocart($id_product){
    //array_search() permet de trouver l'indice d'une valeur dans un tableau array
    $positionProduct = array_search($id_product, $_SESSION['cart']['id_product']);
    //var_dump cest une fonction qui permet d'afficher le contenu d'une variable
     var_dump($positionProduct);
     //si la valeur de $positionProduct est different de false, cela veut dire que l'id_product existe dans le panier, on supprime le produit
    if($positionProduct !== false){
        //array_splice() permet de supprimer une ligne dans un tableau array
        array_splice($_SESSION['cart']['id_product'], $positionProduct, 1);
        array_splice($_SESSION['cart']['title'], $positionProduct, 1);
        array_splice($_SESSION['cart']['picture'], $positionProduct, 1);
        array_splice($_SESSION['cart']['reference'], $positionProduct, 1);
        array_splice($_SESSION['cart']['quantity'], $positionProduct, 1);
        array_splice($_SESSION['cart']['price'], $positionProduct, 1);
    }

    //si le panier est vide, on supprime le panier de la session de l'utilisateur avec la fonction unset() 
}
