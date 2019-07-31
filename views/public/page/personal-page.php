<?php
  queue_js_file('vendor/tinymce/tinymce.min');
  queue_js_file('bookmarks');  
  queue_css_file('bookmarks');
    
  echo head();
  echo flash();
  
  echo $content;
  
  echo foot();