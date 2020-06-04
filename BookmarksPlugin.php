<?php 
/**
 * The Book Reader plugin.
 * @package Omeka\Plugins\BookReader
 */
class BookmarksPlugin extends Omeka_Plugin_AbstractPlugin
{
      /**
     * @var array Hooks for the plugin.
     */
    protected $_hooks = array(
        'install',
        'uninstall',	
        'define_routes',
        'define_acl',
        'public_items_show',
        'public_collections_show', 
        'public_files_show',                       
   );
    
    protected $_filters = array(
        'public_navigation_admin_bar',  	      
        );
    
    private function hookPublicPagesShow() {
      echo 'ok';  
    }
    
    private function bookmarks_signature($entity) {
      $db = get_db();
      $e = 'e';
      $type = get_class($entity);
      switch ($type) {
        case 'Item' :
          $t = 'Notice';
          $userName = $db->query("SELECT u.name nom, u.id userid FROM `$db->Users` u LEFT JOIN `$db->Items` i ON i.owner_id = u.id WHERE i.id = " . $entity->id)->fetchAll();
          break;
        case 'Collection' : 
          $t = 'Collection';
          $query = "SELECT u.name nom, u.id userid FROM `$db->Users` u LEFT JOIN `$db->Collections` c ON c.owner_id = u.id WHERE c.id = " . $entity->id;
          $userName = $db->query("SELECT u.name nom, u.id userid FROM `$db->Users` u LEFT JOIN `$db->Collections` c ON c.owner_id = u.id WHERE c.id = " . $entity->id)->fetchAll();
          break;
        case 'File' :
          $e = '';
          $t = 'Fichier';        
          $userName = $db->query("SELECT u.name nom, u.id userid FROM `$db->Users` u LEFT JOIN `$db->Items` i ON i.owner_id = u.id LEFT JOIN `$db->Files` f ON f.item_id = i.id WHERE f.id = " . $entity->id)->fetchAll();
          break;
      }
      if (isset($userName[0])) {
        return "$t créé$e par <a href='" . WEB_ROOT . '/mapage/' . $userName[0]['userid'] ."'>" . $userName[0]['nom'] . "</a>";        
      } else {
        return "";
      }
    }
    
    public function hookPublicItemsShow($args) {
      echo $this->bookmarks_signature($args['item']);  
    }    
        
    public function hookPublicCollectionsShow($args) {
      echo $this->bookmarks_signature($args['collection']);    
    }  
    
    public function hookPublicFilesShow($args) {
      echo $this->bookmarks_signature($args['file']);   
    }  
            
    public function filterPublicNavigationAdminBar($nav)
    {
   		$params = Zend_Controller_Front::getInstance()->getRequest()->getParams(); 
      $user = current_user();  
      if (! $user) {return $nav;} 		
      if (isset($params['controller'])) {
    		if (in_array($params['controller'], array('page', 'items', 'collections', 'files', 'eman', 'exhibits')) && $params['action'] <> 'browse' && isset($params['id']) || $params['module'] == 'exhibit-builder') { 
  				if ($params['controller'] == 'eman') {
  					$controller = explode("-", $params['action']);
  					$controller = $controller[0];    		     
  				} else {
    				$controller = '';
  				}
          if ($params['module'] == 'simple-pages') {$controller = 'page';}
         
          // Déjà dans les favoris ?
          $db = get_db();
          if ($params['module'] == 'exhibit-builder') {
            $controller = 'exhibit';            
            if (! isset($params['page_slug_1'])) {
              // Front page expo
              $exhibitid = $db->query("SELECT id FROM `$db->Exhibits` WHERE slug = '" . $params['slug'] . "'")->fetchAll();
              $params['id'] = $exhibitid[0]['id'];
//               $tagged = $db->query("SELECT 1 FROM `$db->Favoris` WHERE type = 'exhibit' AND favid = " . $params['id'] . " AND userid = " . $user->id)->fetchAll();                         
            } else {
              $exhibitid = $db->query("SELECT id FROM `$db->ExhibitPages` WHERE slug = '" . $params['page_slug_1'] . "'")->fetchAll();
              $params['id'] = $exhibitid[0]['id'];
              $tagged = $db->query("SELECT 1 FROM `$db->Favoris` WHERE type = 'exhibit' AND favid = " . $params['id'] . " AND userid = " . $user->id)->fetchAll();                                       
            }            
          } else {
            $tagged = $db->query("SELECT 1 FROM `$db->Favoris` WHERE type = '$controller' AND favid = " . $params['id'] . " AND userid = " . $user->id)->fetchAll();            
          }
          if (isset($tagged[0])) {
            $url = 'remove_fav';
            $text = 'Retirer de mes favoris';
          } else {
            $url = 'add_to_fav';
            $text = 'Ajouter à mes favoris';
          };
        	$nav[] = array(
            'label' => __($text),
            'uri' => WEB_ROOT . '/' . $url . '?type=' . $controller . '&id=' . $params['id'],
          );       
        }
      }
      if (isset($user)) {
      	$nav[0]['pages'] = array('bookmark-menu' => array(
      	    'id' => 'bookmark-page',
            'label' => __('Ma page'),
            'uri' => url('/mapage/' . $user->id),
            ),
            array(       	    
            'id' => 'bookmark-favoris',
            'label' => __('Mes favoris'),
            'uri' => url('/favoris/' . $user->id),
            ), 
            array(
      	    'id' => 'bookmark-notes',
            'label' => __('Mes notes'),
            'uri' => url('/mesnotes'),
            ),
            array(
      	    'id' => 'bookmark-history',
            'label' => __('Mes contenus'),
            'uri' => url('/historique/' . $user->id),
            ),
             array(
      	    'id' => 'bookmark-index',
            'label' => __('Index des valeurs'),
            'uri' => url('/emanindexpage?q=50'),
            ),                       
        );       
      }
      return $nav;
    }    
    
    public function hookDefineRoutes($args) {
  
    		$router = $args['router'];    
  
        $user = current_user();
        
    		$router->addRoute(
  				'bookmarks_add_to_fav',
  				new Zend_Controller_Router_Route(
  						'add_to_fav',
  						array(
  								'module' => 'bookmarks',
  								'controller'   => 'page',
  								'action'       => 'addtofav',
  						)
  				)
    		); 
    		$router->addRoute(    		    		
   				'bookmarks_remove_fav',
  				new Zend_Controller_Router_Route(
  						'remove_fav',
  						array(
  								'module' => 'bookmarks',
  								'controller'   => 'page',
  								'action'       => 'remove-fav',
  						)
  				)
    		);
    		$router->addRoute(    		    		
   				'bookmarks_notes',
  				new Zend_Controller_Router_Route(
  						'mesnotes',
  						array(
  								'module' => 'bookmarks',
  								'controller'   => 'page',
  								'action'       => 'display-notes',
  						)
  				)
    		);  
    		$router->addRoute(    		    		
   				'bookmarks_note_create',
  				new Zend_Controller_Router_Route(
  						'mesnotes/create',
  						array(
  								'module' => 'bookmarks',
  								'controller'   => 'page',
  								'action'       => 'create-note',
  						)
  				)
    		);
    		$router->addRoute(    		    		
   				'bookmarks_note_edit',
  				new Zend_Controller_Router_Route(
  						'mesnotes/edit/:noteid',
  						array(
  								'module' => 'bookmarks',
  								'controller'   => 'page',
  								'action'       => 'edit-note',
   								'noteid' => '',  								
  						)
  				)
    		);
    		$router->addRoute(    		    		
   				'bookmarks_note_delete',
  				new Zend_Controller_Router_Route(
  						'mesnotes/delete/:noteid',
  						array(
  								'module' => 'bookmarks',
  								'controller'   => 'page',
  								'action'       => 'delete-note',
   								'noteid' => '',  								
  						)
  				)
    		); 
    		$router->addRoute(
    				'bookmarks_personal_page',
    				new Zend_Controller_Router_Route(
    						'mapage/:userid',
    						array(
    								'module' => 'bookmarks',
    								'controller'   => 'page',
    								'action'       => 'personal-page',
    								'userid' => '',
    						)
    				)
    		);
    		$router->addRoute(
    				'bookmarks_history',
    				new Zend_Controller_Router_Route(
    						'historique/:userid',
    						array(
    								'module' => 'bookmarks',
    								'controller'   => 'page',
    								'action'       => 'history',
//     								'userid' => $user->id,
    						)
    				)
    		);       		    		    		     		   		 
     		if (current_user()) {
      		$router->addRoute(
      				'bookmarks_display_favs',
      				new Zend_Controller_Router_Route(
      						'favoris/:userid',
      						array(
      								'module' => 'bookmarks',
      								'controller'   => 'page',
      								'action'       => 'display-fav',
      								'userid' => $user->id,
      						)
      				)
      		); 
      		$router->addRoute(
      				'bookmarks_personal_page_edit',
      				new Zend_Controller_Router_Route(
      						'mapage/:userid/edit',
      						array(
      								'module' => 'bookmarks',
      								'controller'   => 'page',
      								'action'       => 'personal-page-edit',
      								'userid' => '',
      						)
      				)
      		);       		      		    		         		
       		$router->addRoute(
      				'bookmarks_upload',
      				new Zend_Controller_Router_Route(
      						'upload',
      						array(
      								'module' => 'bookmarks',
      								'controller'   => 'page',
      								'action'       => 'upload',
      						)
      				)
      		);
       		$router->addRoute(
      				'bookmarks_uploadpdf',
      				new Zend_Controller_Router_Route(
      						'uploadpdf',
      						array(
      								'module' => 'bookmarks',
      								'controller'   => 'page',
      								'action'       => 'uploadpdf',
      						)
      				)
      		);       		     		       		
      }
   		$router->addRoute(
  				'bookmarks_list_pages',
  				new Zend_Controller_Router_Route(
  						'listpages',
  						array(
  								'module' => 'bookmarks',
  								'controller'   => 'page',
  								'action'       => 'list-pages',
  						)
  				)
  		);       
    }
    public function hookInstall() { 
      $db = get_db();
      $db->query("CREATE TABLE `$db->Favoris` (
      `id` int(11) NOT NULL,
      `type` varchar(20) NOT NULL,
      `favid` int(11) NOT NULL,
      `userid` int(11) NOT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
      $db->query("ALTER TABLE `$db->Favoris`
      ADD PRIMARY KEY (`id`);");
      $db->query("ALTER TABLE `$db->Favoris`
      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
      
      $db->query("CREATE TABLE `$db->Notes` (
        `id` int(11) NOT NULL,
        `text` text NOT NULL,
        `userid` int(11) NOT NULL,
        `title` varchar(50) NOT NULL,
        `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
  
      $db->query("ALTER TABLE `$db->Notes`
      ADD PRIMARY KEY (`id`)");
      
      $db->query("ALTER TABLE `$db->Notes`
      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
        
      $db->query("CREATE TABLE `$db->Bios` (
        `id` int(11) NOT NULL,
        `text` text NOT NULL,
        `userid` int(11) NOT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
      $db->query("ALTER TABLE `$db->Bios`
      ADD PRIMARY KEY (`id`)");      
      $db->query("ALTER TABLE `$db->Bios`
      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");            
    }
    public function hookUninstall() { 
      $db = get_db();
      $db->query("DROP TABLE `$db->Favoris`");         
      $db->query("DROP TABLE `$db->Notes`");         
      $db->query("DROP TABLE `$db->Bios`");         
    }
  
    function hookDefineAcl($args)  {
      $acl = $args['acl'];
      
/*
      $acl->addResource('Bookmarks_Page');
      $acl->deny(null, 'Bookmarks_Page',
        array('display-fav'));
      
      $acl->allow(array('admin', 'super'), 'Bookmarks_Page', array('display-fav'));
*/
        
    }
}