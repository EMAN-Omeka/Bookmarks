<?php
  echo head(array('title' => 'Mes notes','bodyclass' => 'bookmarks show'));
  
  echo $content;
  ?>
  <script>
  $ = jQuery;
  $(document).ready(function(){
    $('.delete-node').click(function() {
      var titre = $(this).parent().parent().find('h3').text();
      console.log(titre);
      var c = confirm("Voulez-vous vraiment supprimer la note appelée : '" + titre + "' ?");
      if (!c) {
        return false;
      }      
    });
  });    
  </script>
  
  <?php
  echo foot();