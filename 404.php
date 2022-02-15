<?php

/**
 *The template for displaying 404 pages (not found).
 */
?>

<?php get_header(); ?>
<div class="container">
   <div class="row mt-5 mb-5">
      <div class="col-1"></div>
      <div class="col-10 mt-5">
         <p class="P404">404</p>
         <p class="404-2">Oups !!!!!!! Mais Que se passe t-il ?
            La page demandée n'existe pas ! Ne vous inquiétez pas ! Vous retrouverez l'accueil à l'aide du menu ou du bouton ci-dessous ! </p>
      </div>
      <div class="col-"></div>
   </div>

   <div class="row">
      <div class="col-1"></div>
      <div class="col-10 mt-5" style="text-align: center;">
         <button type="button" class="btn btn-dark">Retour à l'accueil</button>
      </div>
      <div class="col-1"></div>
   </div>
</div>
<br>
<?php get_footer(); ?>