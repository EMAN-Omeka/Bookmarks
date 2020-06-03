<?php
  queue_css_file('bookmarks');
  echo head(array('title' => 'Tous les profils','bodyclass' => 'profils show'));
  
  echo "<h3>Les participants du projet</h3><br/><br/><br/>";
  echo $content;
  
  echo foot();
  