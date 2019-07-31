<?php 
  queue_js_file('vendor/tinymce/tinymce.min');
  queue_js_file('bookmarks');
  echo head();
  
  echo $content;
  
  echo foot();
  ?>
