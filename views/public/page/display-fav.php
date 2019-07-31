<?php
  queue_css_file('bookmarks');
  echo head(array('title' => 'Mes favoris','bodyclass' => 'favoris show'));
  
  echo $content;
  
  echo foot();
  