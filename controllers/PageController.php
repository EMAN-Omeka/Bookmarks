<?php
  
  class Bookmarks_PageController extends Omeka_Controller_AbstractActionController {
    
    public function historyAction() {
      $db = get_db();
      $content = '';
      $userId = $this->getRequest()->getParam('userid');  
      $user = current_user(); 
      if (! $user) {
        $user = new stdClass();
        $user->id = 0; 
      }
      $items = $db->query("SELECT e.text titre, i.id id, i.modified modif, i.added added FROM `$db->Items` i LEFT JOIN `$db->ElementTexts` e ON i.id = e.record_id WHERE e.element_id = 50 AND i.owner_id = " . $userId)->fetchAll();
      $content = "<table class='bookmarks-history'><tr><td colspan=2><h3>Notices</h3></td></tr>";
      $content .= "<tr><td class='bookmarks-header'>Titre</td>";
      if (current_user()) {
        $content .= "<td class='bookmarks-header'>Création</td><td class='bookmarks-header'>Modification</td>";         
      }
      if ($user->id == $userId) {      
        $content .= "<td class='bookmarks-header'>Edition</td>";
      }  
      $content .=  "<td class='bookmarks-header'>Visualisation</td></tr>";
      $ColorClass = "";
      foreach ($items as $i => $item) {
        $ColorClass == '' ? $ColorClass = "gris" : $ColorClass = "";
        $content .= "<tr><td class='bookmark-item-title $ColorClass'>" . $item['titre'] . "</td>";
        if (current_user()) {        
          $content .= "<td class='bookmark-item-link $ColorClass'>" . date('d/m/Y H:m:s', strtotime($item['added'])) . "</td>";  
          $content .= "<td class='bookmark-item-link $ColorClass'>" . date('d/m/Y H:m:s', strtotime($item['modif'])) . "</td>";  
        }
        if ($user->id == $userId) {
          $content .= "<td class='bookmark-item-link $ColorClass'><a href='" . WEB_ROOT . "/admin/items/edit/" . $item['id'] . "'>
        Editer </a></td>";          
        }      
        $content .= "<td class='bookmark-item-link $ColorClass'><a href='" . WEB_ROOT . "/items/show/" . $item['id'] . "'> Voir</a></td>";
        $content .= "</tr>";
      }
      $content .= "</table>";
      $ColorClass = "";
      $files = $db->query("SELECT f.id id, i.id itemid, f.original_filename nom, f.modified modif, f.added added FROM `$db->Files` f 
LEFT JOIN `$db->Items` i ON f.item_id = i.id WHERE i.owner_id = " . $userId)->fetchAll();
      $content .= "<table class='bookmarks-history'><tr><td colspan=2><h3>Fichiers</h3></td></tr>";
      $content .= "<tr><td class='bookmarks-header'>Titre</td>"; 
      if (current_user()) {      
        $content .= "<td class='bookmarks-header'>Création</td><td class='bookmarks-header'>Modification</td>"; 
      }
      if ($user->id == $userId) {      
        $content .= "<td class='bookmarks-header'>Edition</td>";
      }  
      $content .=  "<td class='bookmarks-header'>Visualisation</td></tr>";
      foreach ($files as $i => $file) {
        $ColorClass == '' ? $ColorClass = "gris" : $ColorClass = "";        
        $content .= "<tr><td class='bookmark-item-title $ColorClass'>" . $file['nom'] . "</td>";
        if (current_user()) {
          $content .= "<td class='bookmark-item-link $ColorClass'>" . date('d/m/Y H:m:s', strtotime($file['added'])) . "</td>";  
          $content .= "<td class='bookmark-item-link $ColorClass'>" . date('d/m/Y H:m:s', strtotime($file['modif'])) . "</td>";        
        }
        if ($user->id == $userId) {
          $content .= "<td class='bookmark-item-link $ColorClass'><a href='" . WEB_ROOT . "/admin/files/edit/" . $file['id'] . "'>
        Editer </a></td>";
        }
        $content .= "<td class='bookmark-item-link $ColorClass'><a href='" . WEB_ROOT . "/files/show/" . $file['id'] . "'> Voir</a></td>";
        $content .= "</tr>";
      }
      $content .= "</table>";
      $ColorClass = "";
      $collections = $db->query("SELECT c.id id, e.text nom, c.modified modif, c.added added FROM `$db->Collections` c 
LEFT JOIN `$db->ElementTexts` e ON c.id = e.record_id WHERE c.owner_id = " . $userId)->fetchAll();
      $content .= "<table class='bookmarks-history'><tr><td colspan=2><h3>Collections</h3></td></tr>";
      $content .= "<tr><td class='bookmarks-header'>Titre</td>";
      if (current_user()) {
        $content .= "<td class='bookmarks-header'>Création</td><td class='bookmarks-header'>Modification</td>"; 
      }
      if ($user->id == $userId) {      
        $content .= "<td class='bookmarks-header'>Edition</td>";
      }  
      $content .=  "<td class='bookmarks-header'>Visualisation</td></tr>"; 
      foreach ($collections as $i => $collection) {
        $ColorClass == '' ? $ColorClass = "gris" : $ColorClass = "";
        $content .= "<tr><td class='bookmark-item-title $ColorClass'>" . $collection['nom'] . "</td>";
        if (current_user()) {
          $content .= "<td class='bookmark-item-link $ColorClass'>" . date('d/m/Y H:m:s', strtotime($collection['added'])) . "</td>";  
          $content .= "<td class='bookmark-item-link $ColorClass'>" . date('d/m/Y H:m:s', strtotime($collection['modif'])) . "</td>";        
        }
        if ($user->id == $userId) {
          $content .= "<td class='bookmark-item-link $ColorClass'><a href='" . WEB_ROOT . "/admin/collections/edit/" . $collection['id'] . "'>
        Editer </a></td>";
        }
        $content .= "<td class='bookmark-item-link $ColorClass'><a href='" . WEB_ROOT . "/collections/show/" . $collection['id'] . "'> Voir</a></td>";
        $content .= "</tr>";
      }
      $content .= "</table>";
      $ColorClass = "";
      $expositions = $db->query("SELECT title nom, modified modif, added, id FROM `$db->Exhibits` WHERE owner_id = " . $userId)->fetchAll();
      $content .= "<table class='bookmarks-history'><tr><td colspan=2><h3>Expositions</h3></td></tr>";
      $content .= "<tr><td class='bookmarks-header'>Titre</td>";
      if (current_user()) {
        $content .= "<td class='bookmarks-header'>Création</td><td class='bookmarks-header'>Modification</td>"; 
      }
      if ($user->id == $userId) {      
        $content .= "<td class='bookmarks-header'>Edition</td>";
      }  
      $content .=  "<td class='bookmarks-header'>Visualisation</td></tr>";      
      foreach ($expositions as $i => $exposition) {
        $ColorClass == '' ? $ColorClass = "gris" : $ColorClass = "";
        $content .= "<tr><td class='bookmark-item-title $ColorClass'>" . $exposition['nom'] . "</td>";
        if (current_user()) {
          $content .= "<td class='bookmark-item-link $ColorClass'>" . date('d/m/Y H:m:s', strtotime($exposition['added'])) . "</td>";  
          $content .= "<td class='bookmark-item-link $ColorClass'>" . date('d/m/Y H:m:s', strtotime($exposition['modif'])) . "</td>"; 
        }
        if ($user->id == $userId) {               
          $content .= "<td class='bookmark-item-link $ColorClass'><a href='" . WEB_ROOT . "/admin/expositions/edit/" . $exposition['id'] . "'>
        Editer </a></td>";
        }
        $content .= "<td class='bookmark-item-link $ColorClass'><a href='" . WEB_ROOT . "/expositions/show/" . $exposition['id'] . "'> Voir</a></td>";
        $content .= "</tr>";
      }
      $content .= "</table>";
      $userName = $db->query("SELECT name FROM `$db->Users` WHERE id = " . $userId)->fetchAll();
      $content = "<span id='bookmarks-return'><a href='" . WEB_ROOT . "/mapage/" . $userId . "'>retour à la page de présentation de " . $userName[0]['name'] . "</a></span><h2>Contenus créés par " . $userName[0]['name'] . "</h2>" . $content;
      $this->view->content = $content;  
    }
    
    public function personalPageEditAction() {
      $db = get_db();
      $content = $form = $portrait = $pdf = '';
      $user = current_user();
      $userId = $this->getRequest()->getParam('userid');    
      if ($user->id == $userId) {
        $form = $this->getFormPersonalPage($userId);
    		if ($this->_request->isPost()) {
    			$formData = $this->_request->getPost();
          if ($form->isValid($formData)) {
     				$valeurs = $form->getValues();
     				$bio = $db->query("SELECT 1 FROM `$db->Bios` WHERE userid = $userId")->fetchAll();   				
     				if ($bio) {
              $bio = $db->query("UPDATE `$db->Bios` SET text = '" . $valeurs['bio'] . "' WHERE userid = $userId");
     				} else {
              $bio = $db->query("INSERT INTO `$db->Bios` VALUES(null, '" . $valeurs['bio'] . "', $userId)");
     				}
   				}
          $this->_helper->flashMessenger("Modifications sauvegardées.");   				
   				$this->_redirect('mapage/' . $user->id);
   			}
   	   $filename = '/files/portraitUser' . $userId . '.jpg';
   	   if (file_exists(BASE_DIR . $filename)) {
     	   $content .= "<img id='portrait-perso' src='" . WEB_ROOT . "/" . $filename ."' />"; 
   	   }   
   	   $filename = '/files/pdfUser' . $userId . '.pdf';
   	   if (file_exists(BASE_DIR . $filename)) {
     	   $content .= "<img id='pdf-perso' src='" . WEB_ROOT . "/" . $filename ."' />"; 
   	   }    	   			
       $portrait = $this->getFormUpload(); 
       $pdf = $this->getFormUploadPdf();
       $pdf .= "Vous pouvez mettre votre CV ou votre liste de publication. Attention pas d'espace au nom du fichier. A l'affichage, le document sera téléchargeable sous le lien 'Document de présentation'";
      }
      $this->view->content = $content . $portrait . $pdf . $form;      
    }
    
    public function personalPageAction() {
      $db = get_db();
      $user = current_user();
      $userId = $this->getRequest()->getParam('userid');  
      $content = '';            			
   	  if (!isset($userId)) {
     	   $content = "Profil non renseigné.";
     	} else { 
     	   $userinfo = $db->query("SELECT name FROM `$db->Users` WHERE id = $userId")->fetchAll();
     	   if ($userinfo) {
       	   $filename = '/files/portraitUser' . $userId . '.jpg';
       	   if (file_exists(BASE_DIR . $filename)) {
         	   $content .= "<img id='portrait-perso' src='" . WEB_ROOT . "/" . $filename ."' />"; 
       	   }
       	   $content .= $userinfo[0]['name'] . "<br /><br />";
       	   $filename = '/files/pdfUser' . $userId . '.pdf';
       	   if (file_exists(BASE_DIR . $filename)) {
         	   $content .= "<a href='" . WEB_ROOT . "/" . $filename ."' id='pdf-perso'>Document de présentation</a>"; 
       	   }       	   
           $bio = $db->query("SELECT text FROM `$db->Bios` WHERE userid = " . $userId)->fetchAll();
           if (isset($bio[0]['text'])) {
       			$content .= $bio[0]['text'];        
           }       	   
     	   }
       }
       if (isset($portrait)) {
         $content .= $portrait;         
       }
//        if (! current_user()) {         
         if ($user->id == $userId) {
           $content .= "<a href='" . WEB_ROOT . "/mapage/" . $userId . "/edit'>Editer</a>";
           $content .= "<a href='" . WEB_ROOT . "/historique/" . $userId . "'><br />Voir vos contenus</a>";;
         } else {
           $content .= "<a href='" . WEB_ROOT . "/historique/" . $userId . "'><br />Voir les contenus créés par " . $userinfo[0]['name'] . "</a>";                    
         }
//         } else {
//         }

      $this->view->content = $content;  
    }
    
    public function getFormPersonalPage($uid) {
      $db = get_db();
      $texte = $titre = '';
      $form = new Zend_Form();
      $form->setName('PagePerso');      
      $form->setAction(WEB_ROOT . '/mapage/' . $uid . '/edit');
      $userBio = new Zend_Form_Element_Textarea('bio');
      $bio = $db->query("SELECT text FROM `$db->Bios` WHERE userid = " . $uid)->fetchAll();
      if (isset($bio[0]['text'])) {
   			$userBio->setValue($bio[0]['text']);        
      }
 			$form->addElement($userBio);
      $submit = new Zend_Form_Element_Submit('submit');
      $submit->setLabel('Enregistrer');
      $form->addElement($submit);    			
      return $form;
    }
    
    public function uploadAction() {
      $user = current_user();
      $form = $this->getFormUpload(); 
  		if ($this->_request->isPost()) {
  			$formData = $this->_request->getPost();
        if ($form->isValid($formData)) {   
          $fichier = pathinfo($form->lefichier->getFileName());
          array_map('unlink', glob(BASE_DIR . "/files/portraitUser" . $user->id . ".*"));          
          $name = BASE_DIR . '/files/portraitUser' . $user->id . '.' . $fichier['extension']; 
          $form->lefichier->addFilter('Rename', $name);           
   				$valeurs = $form->getValues();   
   				if (!$form->lefichier->receive()) {
              print "Error receiving the file";
          }                    				
        }
      }
  		$this->_redirect('mapage/' . $user->id);         
    }
    
    public function uploadpdfAction() {
      $this->_helper->flashMessenger("Upload PDF OK.");       
      $user = current_user();
      $form = $this->getFormUploadpdf(); 
  		if ($this->_request->isPost()) {
  			$formData = $this->_request->getPost();
        if ($form->isValid($formData)) {   
          $fichier = pathinfo($form->lefichier->getFileName());
          $name = BASE_DIR . '/files/pdfUser' . $user->id . '.' . $fichier['extension']; 
          if (file_exists($name)) {
            unlink($name);
          }
          $form->lefichier->addFilter('Rename', $name);           
   				$valeurs = $form->getValues();   
   				if (!$form->lefichier->receive()) {
              print "Error receiving the file";
          }                    				
        }
      }
  		$this->_redirect('mapage/' . $user->id);         
    }
        
    public function getFormUpload() {
      $form = new Zend_Form();
      $form->setName('Portrait');      
      $form->setAction(WEB_ROOT . '/upload');      
      $form->setAttrib('enctype', 'multipart/form-data');
      $file = new Zend_Form_Element_File('portrait'); 
      $file->setName('Portrait');
      $file->setDestination(BASE_DIR . '/files/');
      $file->addValidator('Extension', false, 'jpg');      
      $form->addElement($file, 'lefichier');
      $submit = new Zend_Form_Element_Submit('submit');
      $submit->setLabel('Télécharger le portrait');
      $form->addElement($submit);    
      return $form;        
    }

    public function getFormUploadPdf() {
      $form = new Zend_Form();
      $form->setName('PDF');      
      $form->setAction(WEB_ROOT . '/uploadpdf');      
      $form->setAttrib('enctype', 'multipart/form-data');
      $file = new Zend_Form_Element_File('pdf'); 
      $file->setName('PDF');
      $file->setDestination(BASE_DIR . '/files/');
      $file->addValidator('Extension', false, 'pdf');      
      $form->addElement($file, 'lefichier');
      $submit = new Zend_Form_Element_Submit('submit');
      $submit->setLabel('Télécharger le PDF');
      $form->addElement($submit);    
      return $form;        
    }
        
    public function displayNotesAction() {
      $user = current_user();
      if (! $user) {
        $this->view->content = "Vous n'êtes pas autorisé à voir cette page.";
        return false;        
      }
      $userid = $user->id;
      $db = get_db();
      $notes = $db->query("SELECT * FROM `$db->Notes` WHERE userid = $userid")->fetchAll();
      $content = '';
      foreach ($notes as $id => $note) {
        $content .= "<div class='note'><h3 style='margin-top:0;'>" . $note['title'] . "</h3>" . date('d/m/Y H:i:s', strtotime($note['date']));
        $content .= "<div>" . $note['text'] . "<hr /></div>";
        $content .= "<div class='note-link-edit' style='float:left;' > <a href='" . WEB_ROOT . '/mesnotes/edit/' . $note['id'] . "'> Éditer </a> </div>";
        $content .= "<div class='note-link-delete' style='float:left;'> <a  class='delete-node' href='" . WEB_ROOT . '/mesnotes/delete/' . $note['id'] . "'> &nbsp;Effacer </a> </div>";
        $content .=  "</div>";
      }      
      $content .= "<div class='note-link-create'><a href='" . WEB_ROOT . "/mesnotes/create/'> Nouvelle note </a></div>";
      $this->view->content = $content;
    }
    
    public function editNoteAction() {
      $db = get_db();      
      $noteId = $this->getParam('noteid');  
      if (! $noteId) {
        $noteId = 9999;
      } 
      $user = current_user();
      $userid = $user->id;      
      if (! $user) {
        $this->view->content = "Vous n'avez pas accès à cette page.";
        return false;
      }        
      $note = $db->query("SELECT 1 FROM `$db->Notes` WHERE id = $noteId")->fetchAll();
      if (! $note) {
         $this->view->content = "Cette note n'existe pas.";        
        return false;
      }      
      $iduser = $db->query("SELECT userid FROM `$db->Notes` WHERE id = $noteId")->fetchAll();
      if (isset($iduser[0]['userid'])) {
        if ($iduser[0]['userid'] <> $userid) {
          $this->view->content = "Vous ne pouvez pas éditer cette note.";
          return false;                
        }              
      }
      $form = $this->getForm($noteId);
  		if ($this->_request->isPost()) {
  			$formData = $this->_request->getPost();
  			if ($form->isValid($formData)) {
  				$valeurs = $form->getValues();    
  				if ($valeurs['noteid']) {
    				$date = date('Y-m-d H:i:s', time());
    				$db->query("UPDATE `$db->Notes` SET text = '"  . $valeurs['lanote'] . "', title = '" . $valeurs['title'] . "' , date = '" . $date . "' WHERE id = " . $valeurs['noteid']);    				
  				}
  				$this->_redirect('mesnotes');  				
        }
      }      
      $this->view->content = $form;    
    }
        
    public function deleteNoteAction() {
      $user = current_user();
      if (! $user) {
        $this->view->content = "Vous n'avez pas accès à cette page.";
        return false;
      }        
      $noteId = $this->getParam('noteid');        
      $db = get_db();
      $userid = $user->id;       
      $iduser = $db->query("SELECT userid FROM `$db->Notes` WHERE id = $noteId")->fetchAll();
      if (isset($iduser[0]['userid'])) {
        if ($iduser[0]['userid'] <> $userid) {
          $this->view->content = "Vous ne pouvez pas effacer cette note.";
          return false;                
        }              
      }
      $db->query("DELETE FROM `$db->Notes` WHERE id = $noteId");
      $this->_redirect('mesnotes');
      $this->view->content .= "Effacement note " . $noteId;
    }

    public function createNoteAction() {     
      $db = get_db();
      $form = $this->getForm();
  		if ($this->_request->isPost()) {
  			$formData = $this->_request->getPost();
  			if ($form->isValid($formData)) {
  				$valeurs = $form->getValues();    
  				$user = current_user();
  				$userid = $user->id;
  				$date = date('Y-m-d H:i:s');
   				$db->query("INSERT INTO `$db->Notes` VALUES (null, '" . $valeurs['lanote'] . "', $userid, '" . $valeurs['title'] . "','" . $date . "')");			  
  			}
  			$this->_redirect('mesnotes');  				
      }          
      $this->view->content = $form;
    }         
    
    public function getForm($noteId = null) {
      $db = get_db();
      $texte = $titre = '';
      $form = new Zend_Form();
      $form->setName('Notes');      
      if ($noteId) {
        $note = $db->query("SELECT id, title, text FROM `$db->Notes` WHERE id = $noteId")->fetchAll();
        $titre = $note[0]['title'];
        $texte = $note[0]['text'];          
      }
      $note_id = new Zend_Form_Element_Hidden('noteid');
 			$note_id->setValue($noteId);
 			$form->addElement($note_id);
 			$title = new Zend_Form_Element_Text('title');
 			$title->setValue($titre);
 			$form->addElement($title);          
 			$note = new Zend_Form_Element_Textarea('lanote');
 			$note->setValue($texte);
 			$form->addElement($note);          			      			    
      $submit = new Zend_Form_Element_Submit('submit');
      $submit->setLabel('Enregistrer la note');
      $form->addElement($submit);   			   
 			return $form;
    }
    
    public function addtofavAction() {
  		$params = Zend_Controller_Front::getInstance()->getRequest()->getParams();
      echo $params['type'];
      echo $params['id'];
      $user = current_user();
      $db = get_db();
      $query = "INSERT INTO `$db->Favoris` VALUES(null, '" . $params['type'] . "'," . $params['id'] . ", " . $user->id . ")";
      $db->query($query);
      $url = $this->getRequest()->getServer('HTTP_REFERER');
      $this->redirect($url);      
    }

    public function removeFavAction() {
  		$params = Zend_Controller_Front::getInstance()->getRequest()->getParams();
      echo $params['type'];
      echo $params['id'];
      $user = current_user();
      $db = get_db();
      $query = "DELETE FROM `$db->Favoris` WHERE type = '" . $params['type'] . "' AND favid = " . $params['id'] . " AND userid = " . $user->id;
      $db->query($query);
      $url = $this->getRequest()->getServer('HTTP_REFERER');
      $this->redirect($url);      
    }
    
    public function displayFavAction() {
      // Check permission
      $user = current_user();
  		$params = Zend_Controller_Front::getInstance()->getRequest()->getParams();
      if ($user->id != $params['userid']) {
        $this->view->content = "Vous n'êtes pas autorisé à accéder à cette page.";
        return false;
      }; 
  		$db = get_db();
  		$favoris['items'] = $db->query("SELECT f.type, f.favid, e.text text FROM `$db->Favoris` f LEFT JOIN `$db->ElementTexts` e ON e.record_id = f.favid WHERE f.type = 'items' AND e.element_id = 50 AND f.type = 'items' AND f.userid = " . $params['userid'])->fetchAll();
  		$favoris['collections'] = $db->query("SELECT f.type, f.favid, e.text text FROM `$db->Favoris` f LEFT JOIN `$db->ElementTexts` e ON e.record_id = f.favid WHERE f.type = 'collections' AND f.type = 'collections' AND e.element_id = 50 AND f.userid = " . $params['userid'])->fetchAll();
  		$favoris['files'] = $db->query("SELECT f.type, f.favid, e.text text FROM `$db->Favoris` f LEFT JOIN `$db->ElementTexts` e ON e.record_id = f.favid WHERE f.type = 'files' AND e.element_id = 50 AND f.type = 'files' AND f.userid = " . $params['userid'])->fetchAll();
  		$favoris['page'] = $db->query("SELECT f.type, f.favid, s.slug slug FROM `$db->Favoris` f LEFT JOIN `$db->SimplePagesPages` s ON s.id = f.favid WHERE f.type = 'page' AND  f.userid = " . $params['userid'])->fetchAll();
  		$favoris['exhibits'] = $db->query("SELECT f.type, f.favid, h.slug slug, h.title title, e.slug slugexpo FROM `$db->Favoris` f LEFT JOIN `$db->ExhibitPages` h ON h.id = f.favid LEFT JOIN `$db->Exhibits` e ON h.exhibit_id = e.id WHERE f.type = 'exhibit' AND f.userid = " . $params['userid'])->fetchAll();   
//   		Zend_Debug::dump($favoris['exhibits']);   
  		// Items
      $content = "<div><span class='bookmarks-title'>Notices<br /></span>";
      if (count($favoris['items']) == 0) { $content .= "<span class='bookmarks-nofav'>Aucun favori enregistré</span><br />";}
  		foreach ($favoris['items'] as $id => $favori) {
    		$content .= "<a class='bookmarks-item' href='" . WEB_ROOT . "/" . $favori['type'] . '/show/' . $favori['favid'] . "'>" . $favori['text'] ."</a><br />";
  		}
      $content .= "<br /><span class='bookmarks-title'>Collections<br /></span>";  		
      if (count($favoris['collections']) == 0) { $content .= "<span class='bookmarks-nofav'>Aucun favori enregistré</span><br />";}
  		foreach ($favoris['collections'] as $id => $favori) {
    		$content .= "<a class='bookmarks-item' href='" . WEB_ROOT . "/" . $favori['type'] . '/show/' . $favori['favid'] . "'>" . $favori['text'] ."</a><br />";
  		}  		
      $content .= "<br /><span class='bookmarks-title'>Fichiers<br /></span>";  		
      if (count($favoris['files']) == 0) { $content .= "<span class='bookmarks-nofav'>Aucun favori enregistré</span><br />";}
  		foreach ($favoris['files'] as $id => $favori) {
    		$content .= "<a class='bookmarks-item' href='" . WEB_ROOT . "/" . $favori['type'] . '/show/' . $favori['favid'] . "'>" . $favori['text'] ."</a><br />";
  		}  		
      $content .= "<br /><span class='bookmarks-title'>Pages<br /></span>";  		  		
      if (count($favoris['page']) == 0) { $content .= "<span class='bookmarks-nofav'>Aucun favori enregistré</span><br />";}
  		foreach ($favoris['page'] as $id => $favori) {
    		$content .= "<a class='bookmarks-item' href='" . WEB_ROOT . "/" . $favori['slug'] . "'>" . $favori['slug'] ."</a><br />";
  		} 
      $content .= "<br /><span class='bookmarks-title'>Pages d'exposition<br /></span>";  		
      if (count($favoris['exhibits']) == 0) { $content .= "<span class='bookmarks-nofav'>Aucun favori enregistré</span><br />";}
  		foreach ($favoris['exhibits'] as $id => $favori) {
    		$content .= "<a class='bookmarks-item' href='" . WEB_ROOT ."/exhibits/show/" . $favori['slugexpo'] . "/" . $favori['slug'] . "'>" . $favori['title'] ."</a><br />";
  		}  		 		
      $this->view->content = $content . "</div>";
    }
  }