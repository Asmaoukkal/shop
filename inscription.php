<?php
require_once('include/init.php');

//si il est connecte il na rien a faire sur la page inscription , on le redirige vers la page index.php
if(userConnected()){
  header('location: index.php');
}
/*
EXO / 
1- controle que la receptionne bien toute les donnes saisie dans le formaulaire en PHP
2- CONTROLER LA disponibiliteDE L'EMAIL (SELECT + ROWCOUNT)
3- affichier un message d'erreurer si le champs email est vide 
4- controle la validation de l'email(filtre_var)
5- affichier un message si le champs mot de passe est vide
6- controler que les mots de passe corespondant
*/


//1- controle que la receptionne bien toute les donnes saisie dans le formaulaire en PHP
   echo '<pre>'; print_r($_POST); echo '</pre>';
   //2- CONTROLER LA disponibiliteDE L'EMAIL (SELECT + ROWCOUNT)

   if(isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] == 'POST'){

    //on selection tout dans la BDD a condition que la colone email dans la BDD soit egal a l'email saisir sur le formulaire
    $emailIxist = $connect_db->prepare('SELECT * FROM user WHERE email = :email');
    $emailIxist->bindValue(':email', $_POST['email'], PDO::PARAM_STR);//puche nom de marqueur 3-type de donne
    $emailIxist->execute();

   // echo $emailIxist->rowCount();//conpte les nombre de resultat

    if($emailIxist->rowCount()){
      $valueExiste='<small class="text-color-danger">un compte et deja existant à cette adress email.</small>';
      $error = true;
    }elseif(empty($_POST['email'])){
      $remplire= '<small class="text-danger">Merci de saisir une adress email</small>';
      $error = true;
     }elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL )){
      $errorEmail = '<small class="text-color-danger">Merci de saisir une adress email valide</small>';
      $error = true;
     }
  

    // if($emailIxist->fetchColumn()){
    //   $valueExiste='<small class="text-danger">Cette adresse e-mail est déjà enregistrée. Veuillez utiliser une autre adresse ou vous connecter.</small>';
    // }
    $password_regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/"; 
    // echo preg_match($password_regex, 'secret'); // returns 0
    // echo preg_match($password_regex, '-Secr3t.'); // returns 1
   if(empty($_POST['password'])){

    $rempliremotDepass= '<small class="text-color-danger">veuillez remplire votre mot de passe</small>';
    $error = true;
   }elseif(!preg_match($password_regex, $_POST['password'])){

    $rempliremotDepass= '<small class="text-color-danger">8caractere minimum, une majuscule, une minuscule, un chiffre , un caractere special(#?!@$%^&*-). </small>';
    $error = true;
   }elseif($_POST['password'] !== $_POST['repeat_password']){
    $rempliremotDepass= '<small class="text-danger">les mot de passe ne correspondent pas</small>';
    $error = true;
    }
  
//  ****************EXO2 
//si l'utilisateur a correctement rempli le formulaire , execute la requete d'insertion en bDD (prepare + bindValue + execute), on redirige l'internateur vers la page connexion.php

if(!isset($error)){

  //le mots de passe n'est jamais conserve en clair dans la base de donner 
  //password_hash permet de creee une cle de hachage du mot de passe dans la BDD
  $inscription = $connect_db->prepare("INSERT INTO user (password, firstName, lastName, email, city, zipcode,address) VALUES (:password, :firstName, :lastName, :email, :city, :zipcode, :address)");

  $inscription->bindValue(':password', password_hash($_POST['password'], PASSWORD_DEFAULT), PDO::PARAM_STR); 

  $inscription->bindValue(':firstName', $_POST['firstName'], PDO::PARAM_STR);

  $inscription->bindValue(':lastName', $_POST['lastName'], PDO::PARAM_STR);

  $inscription->bindValue(':email', $_POST['email'], PDO::PARAM_STR);

  $inscription->bindValue(':city', $_POST['city'], PDO::PARAM_STR);

  $inscription->bindValue(':zipcode', $_POST['zipcode'], PDO::PARAM_STR); 

  $inscription->bindValue(':address', $_POST['address'], PDO::PARAM_STR);

  $inscription->execute();
  
  // echo '<pre>'; print_r($_POST); echo '</pre>';

  /*******************On stock dans le fichier de l'utilisateur , le fichier de session est stocke cote serveur et accessible via la superglobale $_session et accessible sur n'import quelle page de site , on stock ici un message (message-flash) dans le fichier de session de l'utilisateur */
  $_SESSION['msgRegisterValidate'] = '<div class="bg-success p-3 text-white text-center mb-2">Votre inscription est valide. vous pouvez des a present vous connecter.</div>';
  header('location: connexion.php');
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
            <h3>Créer votre compte</h3>
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
          <div class="full">
            <form method="post" action="">
              <fieldset>
                <input
                  type="text"
                  placeholder="Enter votre prénom"
                  name="firstName"
                />
                <input
                  type="text"
                  placeholder="Enter votre nom"
                  name="lastName"
                 />
                  <?php if(isset($remplire)) echo" $remplire<br>"; ?>
                  <?php if(isset($errorEmail)) echo $errorEmail; ?>
                  <?php if(isset($valueExiste)) echo $valueExiste; ?>
                <input
                  type="text"
                  placeholder="Entrez votre adresse e-mail"
                  name="email"
                  class="<?php if(isset($valueExiste)) echo 'border-danger'; ?>"
             
                  value="<?php if(isset($_POST['email'])) echo $_POST['email'];?>"
                 />
                   <!-- value pour garde la valeur dans le champs apres reboute la page sauf sur mot de passe il faux pas la maitre   -->
                <input
                  type="text"
                  placeholder="Entrer votre adresse"
                  name="address"
                />
                <input
                  type="text"
                  placeholder="Entrer votre ville"
                  name="city"
                 />
                <input
                  type="text"
                  placeholder="Entrer votre code postal"
                  name="zipcode"
                   />
                  <?php if(isset($rempliremotDepass)) echo$rempliremotDepass ; ?>
                <input
                  type="text"
                  placeholder="Enter votre mot de passe"
                  name="password"
                  />
                  <?php if(isset($pasEdentique)) echo$pasEdentique; ?>
                <input
                  type="repeat_password"
                  placeholder="Répétez votre mot de passe"
                  name="repeat_password"
                  />
                 
                <input type="submit" value="Submit" name="submit"/>
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
  ?>