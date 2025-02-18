<?php 
//require_once est une fonction qui permet d'inclure un fichier dans un autre fichier
require_once('include/init.php');

// echo '<pre>'; print_r($_POST); echo '</pre>';

if(isset($_POST['add_cart'])){
  $data = $connect_db->prepare("SELECT * FROM product WHERE id_product = :id");
  $data->bindValue(':id', $_POST['id_product'], PDO::PARAM_INT);
  $data->execute();

  $product = $data->fetch(PDO::FETCH_ASSOC);
  // echo '<pre>'; print_r($product); echo '</pre>';

  addProductToCart($product['id_product'], $product['title'], $product['picture'], $product['reference'], $_POST['quantity'], $product['price']);
  
  header('location: panier.php');
}
//si le bouton de suppression est cliqué on supprime le produit du panier en utilisant la fonction removeProductTocart et on redirige l'utilisateur vers la page panier et on affiche le panier qui est mis à jour avec le produit supprimé 
if(isset($_POST['payForCart'])){
  // echo "Panier validé";
  //   for  cest une boucle qui permet de parcourir un tableau array de facon automatique
  //               4
  for($i = 0; $i < count($_SESSION['cart']['id_product']); $i++){
    //                                                                       10
    //data est une variable qui contient la requete sql qui permet de selectionner tous les produits de la table product
    $data = $connect_db->query("SELECT * FROM product WHERE id_product =" . $_SESSION['cart']['id_product'][$i]);
    $product = $data->fetch(PDO::FETCH_ASSOC);
    echo '<pre>'; print_r($product); echo '</pre>';
    
    // Si la quantité en stock en BDD est inférieur à la quantité commandée
    if($product['stock'] < $_SESSION['cart']['quantity'][$i]){
      $error = '';
   //$error est une variable qui contient un message d'erreur qui sera affiché à l'utilisateur 
      $error .= '<div class="alert alert-danger text-center">Stock restant du produit ' . $_SESSION['cart']['title'][$i] . ' : <strong>' . $product['stock'] . '</strong></div>';
     //error
      $error .= '<div class="alert alert-warning text-center mt-2">Quantité commandée du produit ' . $_SESSION['cart']['title'][$i] . ' : <strong>' . $_SESSION['cart']['quantity'][$i] . '</strong></div>';

     // si la quantiteen stock est superieur à 0 mais inferieur à la quantité commandée

      if($product['stock'] > 0){

  
       //le stock est inferieur à la quantité commandée; on modifie la quantité du produit dans le panier

        $_SESSION['cart']['quantity'][$i] = $product['stock'];
        $error .= '<div class="alert alert-info text-center mt-2">La quantité du produit ' . $_SESSION['cart']['title'][$i] . 'a ete reduite car notre stock est insuffissant <strong>' . $product['stock'] . '</strong></div>';


    }else{
      removeProductTocart($_SESSION['cart']['id_product'][$i]);

         //le stock est à 0; ruptutr de stock; on retire le produit du session cart
      $error .= '<div class="alert alert-danger text-center mt-2">Le produit ' . $_SESSION['cart']['title'][$i] . $_SESSION['cart']['title'][$i] . ' a été retiré du panier car en rupture de stock </div>';
  
    }
  }
}}

echo '<pre>'; print_r($_SESSION); echo '</pre>';

require_once('include/header.php');
?>
  <!-- inner page section -->
  <section class="inner_page_head">
    <div class="container_fuild">
      <div class="row">
        <div class="col-md-12">
          <div class="full">
            <h3>Votre panier</h3>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end inner page section -->
  <!-- product section -->
  <section class="product_section layout_padding">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>Valider vos <span>achats !</span></h2>
      </div>
        
      <?php if(isset($error)) echo $error; ?>

      <div class="row">
        <table class="table table-borderless">
          <thead>
            <tr>
              <th>Titre</th>
              <th>Image</th>
              <th>Référence</th>
              <th>Quantité</th>
              <th>Prix unitaire</th>
              <th>Prix total</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php if(empty($_SESSION['cart']['id_product'])): ?>

              <tr>
                <td colspan="6" class="text-center">Aucun article dans le panier</td>
              </tr>

            <?php else:
            
              //       3      4     4
              for($i = 0; $i < count($_SESSION['cart']['id_product']); $i++): 
            ?>
                <tr>
                  <td><?= ucfirst($_SESSION['cart']['title'][$i]); ?></td>

                  <td><img src="<?= $_SESSION['cart']['picture'][$i] ?>" class="picture__product" alt="<?=  $_SESSION['cart']['title'][$i] ?>"></td>

                  <td><?= $_SESSION['cart']['reference'][$i]; ?></td>
                  <td><?= $_SESSION['cart']['quantity'][$i]; ?></td>
                  <td><?= $_SESSION['cart']['price'][$i]; ?>€</td>

                  <td><strong><?= $_SESSION['cart']['quantity'][$i]*$_SESSION['cart']['price'][$i] ?>€</strong></td>

                  <td><a href="" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a></td>
                </tr>
            <?php 
              endfor; 
            ?>
            <tr>
              <th>MONTANT TOTAL</th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th><?= totalAmount(); ?>€</th>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
            
      <?php if(!empty($_SESSION['cart']['id_product'])): ?>

        <div class="btn-box">
          <?php if(userConnected()): ?>

            <form action="" method="post">
              <input type="submit" name="payForCart" value="Procéder au paiement">
            </form>

          <?php else: ?>

            <p>Veuillez vous <a href="inscription.php">inscrire</a> ou vous <a href="connexion.php">identifier</a> pour valider le paiement</p>

          <?php endif; ?>
        </div>
      
      <?php endif; ?>

      <div class="btn-box">
        <a href="product.php"> Continuer vos achats </a>
      </div>
    </div>
  </section>
  <!-- end product section -->
  <!-- footer section -->
   
<?php 
require_once('include/footer.php');
?>