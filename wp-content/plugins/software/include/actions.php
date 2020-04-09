<?php 

function software_menu_pages(){
	$admin_page_name = 'Code Software';
	add_menu_page( $admin_page_name, $admin_page_name, 'manage_options', 'software', 'software_admin_func', 'dashicons-media-archive' );
	add_submenu_page( 'software', 'Add Software', 'Add Software', 'manage_options', 'software-add', 'software_add_func' );
	add_submenu_page( 'software', 'Software', 'Software', 'manage_options', 'software-list', 'software_func' );
	add_submenu_page( 'software', 'Products', 'Products', 'manage_options', 'software-products', 'software_products_func');
  remove_submenu_page('software','software'); // pay a attention
}

add_action('admin_menu', 'software_menu_pages'); 

function software_scripts(){
	wp_enqueue_script( 'dropzone', plugin_dir_url( __DIR__ ). 'assets/js/dropzone.js', array('jquery'), false, false );
	wp_enqueue_style( 'admin-style', plugin_dir_url( __DIR__ ). 'assets/css/admin-style.css', array(), false, 'all' );
	wp_enqueue_script( 'sortable', plugin_dir_url(__DIR__) . 'assets/js/sortable.js', array('jquery'), false, true );
	// wp_enqueue_script( 'bs-custom-file-input', plugin_dir_url(__DIR__) . 'assets/js/bs-custom-file-input.js', array('jquery'), false, true );
	wp_enqueue_style( 'bootstrap-code', plugin_dir_url( __DIR__ ). 'assets/css/bootstrap-code.css', array(), false, 'all' );
}

function frontend_scripts(){

	wp_enqueue_style( 'front-end-css', plugin_dir_url(__DIR__).'assets/css/front-end.css', array(), false, 'all' );
}

add_action( 'admin_enqueue_scripts', 'software_scripts');
add_action( 'wp_enqueue_scripts', 'frontend_scripts' );

function software_admin_func(){
	$message = NULL;
	$options = array();
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );	
	}
	if( isset( $_POST['publish'] ) ){
		update_option( 'codesoftware', $_POST );
	}
	$options = get_option('codesoftware');
	ob_start(); 
	include dirname(__DIR__) . '/partial/admin.php'; 
	$template = ob_get_clean();
	echo $template;
}

function remove_from_set($set, $deletion){


	$set = trim($set,",");
	$set = explode(',', $set);
	if( is_array($set) ){
		
		$key = array_search($deletion,$set);
		unset($set[$key]);
		
		$set = array_values($set);
		$set = array_unique($set);
		$set = implode(',',$set);
		$set = trim($set,",");
	} else {
		return NULL;
	}
	return $set;
}

function add_to_set($set, $addition){
	$set = trim($set,",");
	$set = explode(',',$set);
	$set[] = $addition;
	$set = array_unique($set);
	$set = implode(',',$set);
	$set = trim($set,",");
	return $set;
}

function software_func(){
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );	
	}
	global $wpdb;
	$message = NULL;
	$options = get_option('codesoftware');
	if(isset($_GET['software_id']) && strlen($_GET['software_id']) ){
		if( isset($_POST['submit']) ){
			if (isset($_FILES) && !empty($_FILES)) {
				$software_name = sanitize_title($_POST['name']);
	            $uploads_folder = wp_upload_dir();
	            $uploads_folder = $uploads_folder['basedir'] . '/product_software/'.$software_name;
	            if (!file_exists($uploads_folder)) {
	            }
			}
			$update_array = array(
				'name' => $_POST['name'],
				'sdesc' => $_POST['sdesc'],
				'ldesc' => $_POST['ldesc'],
				'software_version' => $_POST['software_version'],
			);
			global $wpdb;
			$wpdb->query("
				DELETE FROM {$wpdb->prefix}swprodsoftware
				WHERE `software` = {$_GET['software_id']}
			");
			if( isset($_POST['products']) && !empty($_POST['products']) ){
				foreach( $_POST['products'] as $product) {
					$wpdb->insert($wpdb->prefix.'swprodsoftware', array('product_id'=>$product, 'software'=>$_GET['software_id']), array('%d'));	
				} 
			}
			$updated = $wpdb->update("{$wpdb->prefix}software", $update_array, array( 'id' => $_GET['software_id'] ), array('%s', '%s', '%s'), array('%d'));
			// ENTER QUERY TO UPDATE SORTING
			if( isset($_POST['products']) && !empty( $_POST['products'] ) ) {
				// WE are adding to the rows that have the product id;
				foreach( $_POST['products'] as $product ){
					$srow = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}swsort WHERE type_id={$product} AND sort_type='product'");
					if( $srow ) {
						if( stripos( $_GET['software_id'], $srow->software_array ) === FALSE ){
							$software_array_str = add_to_set($srow->software_array, $_GET['software_id']);
							$wpdb->update($wpdb->prefix.'swsort', array('software_array'=>$software_array_str, 'type_id' => $product, 'sort_type'=>'product'), array('id'=>$srow->id));
						}
					} else {
						$wpdb->insert($wpdb->prefix.'swsort', array('software_array'=>$software_array_str, 'type_id' => $product, 'sort_type'=>'product'));
					}
				}
				// We are removing any instances of this software_id from products that have it, but are not in the proposed array of products;
				$sortrows = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}swsort WHERE sort_type='product' AND FIND_IN_SET(".$_GET['software_id'].",software_array) > 0");
				if( !empty($sortrows) ){
					foreach($sortrows as $srow){
						// if the found row that contains the software id has a product_id (type_id) that is not found in the list of proposed products ($_POST['products']).
						if( !in_array($srow->type_id, $_POST['products']) ) {
							$software_array_str = remove_from_set($srow->software_array, $_GET['software_id']);
							$wpdb->update($wpdb->prefix.'swsort', array('software_array'=>$software_array_str), array('id'=>$srow->id));
						}
					}
				}
			} else {
				// Since we wiped out the products form this software, we need to update any sort rows that have the software id
				$sortrows = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}swsort WHERE sort_type='product' AND FIND_IN_SET(".$_GET['software_id'].",software_array) > 0");
				// loop thru all found rows and take the software id out of the software_array
				if( !empty($sortrows) )	{					
					foreach( $sortrows as $srow ){
						$software_array_str = remove_from_set($srow->software_array,$_GET['software_id']);
						// Update the row that you found
						$wpdb->update($wpdb->prefix.'swsort', array('software_array'=>$software_array_str), array('id'=>$srow->id));
					}
				}
			}
			if( empty($wpdb->last_error) ){
				$status = 'Updated Successfully';
			} else {
				$status = $wpdb->last_error;
			}
		}		
		ob_start(); include dirname(__DIR__) . '/partial/edit_software_package.php';
		$template = ob_get_clean();
		echo $template;
	} else {
		ob_start(); include dirname(__DIR__) . '/partial/software_packages.php';
		$template = ob_get_clean();
		echo $template;
	}
}

function software_add_func(){
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );	
	}
	if( session_id() && isset($_SESSION['errors']) ){ $errors = $_SESSION['errors']; unset($_SESSION['errors']); 	}
	$message = NULL;
	$options = get_option('codesoftware');
	ob_start(); include dirname(__DIR__) . '/partial/add_software_package.php';
	$template = ob_get_clean();
	echo $template;
}

function software_products_func(){
	$message = NULL;
	$options = array();
	global $wpdb;
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );	
	}
	if( isset($_POST['submit']) ){
		if( isset($_POST['documents']) && !empty($_POST['documents']) ){
			$_POST['documents'] = array_unique($_POST['documents']);
			$row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}swsort WHERE `sort_type`='product' AND `type_id` = ".$_GET['product_id']);
			if( $row ){
				$wpdb->update($wpdb->prefix.'swsort', array('software_array'=>implode(',',$_POST['documents'])), array('id'=>$row->id));
			} else {
				$wpdb->insert($wpdb->prefix.'swsort', array('sort_type'=>'product', 'type_id'=>$_GET['product_id'], 'software_array'=>implode(',',$_POST['documents'])));
			}
			foreach( $_POST['documents'] as $document) {
				if( !is_null($wpdb->get_var("SELECT id = FROM * `{$wpdb->prefix}swprodsoftware` WHERE software = {$document} AND product_id = {$_GET['product_id']}")) ){
					$wpdb->insert($wpdb->prefix.'swprodsoftware', array('software'=>$document, 'product_id'=>$_GET['product_id']), array('%d'));	
				}
			} 
			// check if software id was removed.
			$software = $wpdb->get_results("SELECT `software` FROM {$wpdb->prefix}swprodsoftware WHERE product_id = {$_GET['product_id']}");
			$software = wp_list_pluck( $software, 'software' );
			foreach( $software as $sw ){
				if( !in_array($sw, $_POST['documents']) ){
					$wpdb->query("DELETE FROM `{$wpdb->prefix}swprodsoftware` WHERE product_id = {$_GET['product_id']} AND `software` = {$sw}");
				}
			}
			/*$results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}software WHERE FIND_IN_SET(".$_GET['product_id'].",product) > 0");


			foreach($results as $res){
				if( !in_array($res->id, $_POST['documents'])){
					$missing = $_GET['product_id'];
					$products = explode(',', $res->product);
					if ( ($key = array_search($missing, $products)) !== false ) {
					  unset($products[$key]);
					}
					$products = array_values($products);
					$products = implode(',', $products);
					$products = trim($products, ',');
					$wpdb->update("{$wpdb->prefix}software", array('product'=>$products), array('id'=>$res->id));
				}
			}
			$documents = implode(',', $_POST['documents']);


			$results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}software WHERE FIND_IN_SET(".$_GET['product_id'].",product) < 1 AND id IN ({$documents})");
			if( count($results) ){
				foreach( $results as $res ){
					$missing = $_GET['product_id'];
					$products = explode(',', $res->product);
					array_push($products, $_GET['product_id']);
					$products = array_filter($products);
					$products = array_values($products);
					$products = implode(',', $products);
					$wpdb->update("{$wpdb->prefix}software", array('product'=>$products), array('id'=>$res->id));
				}
			}*/
		} else {
			$row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}swsort WHERE `sort_type`='product' AND `type_id` = ".$_GET['product_id']);
			if( !empty($wpdb->last_result) ){
				$wpdb->update($wpdb->prefix.'swsort', array('software_array'=>NULL), array('id'=>$row->id));
			} else {
				$wpdb->insert($wpdb->prefix.'swsort', array('sort_type'=>'product', 'type_id'=>$_GET['product_id'], 'software_array'=>NULL));
			}
			/*$result = $wpdb->get_row("SELECT `product`, id FROM {$wpdb->prefix}software WHERE FIND_IN_SET(".$_GET['product_id'].",product) > 0 ");
			$products = explode(',', $result->product);
			$pos = array_search($_GET['product_id'], $products);
			unset($products[$pos]);
			$products = implode(',', $products);
			$wpdb->update("{$wpdb->prefix}software", array('product'=>$products), array('id'=>$result->id));*/
		}
	}
	if( isset($_GET['product_id']) && strlen($_GET['product_id']) ){
		ob_start(); include dirname(__DIR__) . '/partial/product.php'; $template = ob_get_clean();
	} else {
		ob_start(); include dirname(__DIR__) . '/partial/products.php'; $template = ob_get_clean();
	}
	echo $template;
}

function software_shortcode_func( $atts = array(), $content = '' ) {
	$atts = shortcode_atts( array(
		'product_id' => '',
		'software_id' => '',
	), $atts, 'shortcode-id' );
	global $wpdb;
	if( isset($atts['product_id']) && strlen($atts['product_id']) > 0 ){
    $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}swsort WHERE `sort_type`='product' AND `type_id`= ".intval($atts['product_id']));
    $qs = "SELECT * FROM `{$wpdb->prefix}swprodsoftware` as `psw` 
			     JOIN `{$wpdb->prefix}software` as `sw` 
			     ON `psw`.`software` = `sw`.`id` 
			     WHERE `product_id` = {$atts['product_id']}";
    if ( $row && strlen($row->software_array)>0 )
      $qs .= "\n ORDER BY FIELD(sw.id, ". $row->software_array.")";      // JCM - 1/31/2020 - added ORDER BY to sort on front end
    $results = $wpdb->get_results($qs);
	}
	elseif( isset($atts['software_id']) && strlen($atts['software_id']) > 0 ){
		$results = $wpdb->get_results("
			SELECT * FROM `{$wpdb->prefix}swprodsoftware` as `psw` 
			JOIN `{$wpdb->prefix}software` as `sw` 
			ON `psw`.`software` = `sw`.`id` 
		  WHERE `id` = {$atts['software_id']}
		");     
	}
	$string = '';
	$string .=  '<div id="software-shortcode-list">';
	if( !empty($results) ){
		foreach($results as $result):
				$string .= '<div class="software">
								<div>
									<h4>'.$result->name.'</h4>
									<small>VERSION: '.$result->software_version.'</small>
									<p>'.$result->sdesc.'</p>
									<p>'.stripslashes($result->ldesc).'</p>
								</div>
								<div class="download" style="">
								<a href="'.get_bloginfo('url'). $result->folder.'/'. $result->filename .'"> <i class="fa fa-download"></i></a>
								</div>
							</div>';
		endforeach;
	} else {
		if( isset($atts['product_id']) && strlen($atts['product_id']) > 0 ){
			$string .= '<div class="software">No Software attached to this product.</div>';	
		} elseif(isset($atts['software_id']) && strlen($atts['software_id']) > 0) {
			$string .= '<div class="software">No Software matches this ID: '.$atts['software_id'].'</div>';
		}
	}
	$string .=  '</div>';
	ob_start();
	include dirname(__DIR__).'/partial/shortcode-script.php';
	$string .= ob_get_clean();
	return $string;
}

add_shortcode( 'software', 'software_shortcode_func' );