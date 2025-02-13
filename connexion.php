<?php
require_once('include/init.php');

//si l'indice 'action' est defenit dans l'URL et quil a pour valeur 'logout' cela veut dire que l'internaute a clique sur le lien de deconnexion , on supprime le tableau array de donnes user dans la session
if(isset($_GET['action']) && $_GET['action'] == 'logout'){
  //on ne supprime pas le fichier de session mais seulement l'indice 'user
  unset($_SESSION['user']);
  //session_destroy
}
//si l'utilisateur est connecte, il n'a rien a faire la page identifiez- vous , on le redirige vers la page index.php
if(userConnected()){
  header('location: index.php');
}

   echo '<pre>'; print_r($_POST); echo '</pre>';

if(isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] ==='POST'){
 ///On selection toiut dans la bdd a condition que la collonne email dans la bdd egal a l'email saisi dans le formulaire
         // echo "password valide";                               asma.oukkal88@gmail.com
    $data = $connect_db->prepare("SELECT * FROM user WHERE email = :email");
    $data->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
    $data->execute();
   
   // si la requete  de selection retourn un resultat, cela veux que l'email est connu en BDD
    if($data->rowCount()){
     // echo "email existant";
     //fetche recupere un seul user quant il rentre dans la condition de email valide , il va a la base de donnes et il recupere la collon qui rassemble de email
     // on recupere un array contenant touts les donnes de l'utilisateur qui a saisi le bon email
    $user =$data->fetch(PDO::FETCH_ASSOC);
    //echo '<pre>'; print_r($user); echo '</pre>';
     /// si on soumet le formulaire
     //password verify() fonction pre defenie permettant de compare le mot de passe saisi  dans le formulaire a la cle de hachage du mot de passe dans la BDD on entre dans la condition If si les mots de passe correspandant
    if(password_verify($_POST['password'], $user['password'])){
        //echo "password valide

         foreach($user as $key => $value){
          // $_SESSION['user']['id_user'] = 2;
          //$_SESSION['user']['firstname'] = asma;
          $_SESSION['user'][$key] = $value;
  
         } //echo '<pre>'; print_r($_SESSION); echo '</pre>';
           header('location: index.php');
         }else{
       //  echo "password error";
         $error = '<div class="background-danger p-3 mb-3 text-white text-center">Email ou mot de passe invalide.</div>';
         }
 
    }else{
     // echo "email non existant";
     $error = '<div class="background-danger p-3 mb-3 text-white text-center">Email ou mot de passe invalide.</div>';
    }

}
require_once('include/header.php');
  ?>
    <!-- end header section -->

  <!-- inner page section -->
  <section class="inner_page_head">
    <div class="container_fuild">
      <div class="row">
        <div class="col-md-12">
          <div class="full">
            <h3>Identifiez-vous</h3>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end inner page section -->
  <!-- why section -->
  <section class="why_section layout_padding">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 offset-lg-2">

         <!-- appele la session de la page inscription pour utulise ici -->
          <?PHP  if(isset($_SESSION['msgRegisterValidate'])) echo$_SESSION['msgRegisterValidate'];?>
          <?PHP  if(isset($error)) echo $error;?>
          <div class="full">
            <form method="post" action="">
              <fieldset>
                <input
                  type="text"
                  placeholder="Entrez votre adresse e-mail"
                  name="email"
                  class="<?PHP if(isset($error)) echo 'border-danger';?>"
                  value="<?PHP if(isset($_POST['email'])) echo$_POST['email'];?>"
                  required />
                <input
                  type="password"
                  class="<?PHP if(isset($error)) echo 'border-danger';?>"
                  placeholder="Enter votre mot de passe"
                  name="password"
                  required />
                <input type="submit" name="submit" value="Continuer" />
              </fieldset>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end why section -->
  <!-- arrival section -->
  <!-- end arrival section -->
  <!-- footer section -->
  <?php
  require_once('include/footer.php');
  // on supprime le message de validation d'inscription dans la session, afin qu'il ne soit plus affiche a chaque visite sur la page d'authentification
  unset($_SESSION['msgRegisterValidate']);
  ?>