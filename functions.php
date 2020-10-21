<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
		if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );

// END ENQUEUE PARENT ACTION



// END ENQUEUE PARENT ACTION
add_action( 'template_redirect', 'quadrito_add_to_cart_on_custom_page_and_redirect');
 
function quadrito_add_to_cart_on_custom_page_and_redirect() {
	
	$product_id0 = 19;
	$marco_id1 = 42;
	$marco_id2 = 43;
	$marco_id3 = 44;
	$marco_id4 = 45; 
	
	$product_id1 = 48;
	$quantity_1 = 0;
	$marco_id5 = 49;
	$marco_id6 = 50;
	$marco_id7 = 51;
	$marco_id8 = 52;
	
	$marco = 0;
	$variante_0 = 0;
	$variante_1 = 0;
	$quantity = 0;
	$regalo = "no";
	
	if (isset($_GET['marco'])) {
		$marco = $_GET['marco'];
	}
	
	//Marco Base
	switch ($marco) {
		case 0:
			$variante_0 = $marco_id1;
			break;
		case 1:
			$variante_0 = $marco_id2;
			break;
		case 2:
			$variante_0 = $marco_id3;
			break;
		case 3:
			$variante_0 = $marco_id4;
			break;
	}
	
	if (isset($_GET['cantidad'])) {
		$quantity = $_GET['cantidad']; // Menos 3 del Quadrito base
	}

	if ( $quantity > 3 ) {
		switch ($marco) {
			case 0:
				$variante_1 = $marco_id5;
				break;
			case 1:
				$variante_1 = $marco_id6;
				break;
			case 2:
				$variante_1 = $marco_id7;
				break;
			case 3:
				$variante_1 = $marco_id8;
				break;
		}
	}
	 
	
	
	if( is_page( 'test' ) ) { 
 
		WC()->cart->empty_cart();

		WC()->cart->add_to_cart( $product_id0, 3, $variante_0);
		
		if ( $quantity > 3 ) {
			$quantity_1 = $quantity - 3;
			WC()->cart->add_to_cart( $product_id, $quantity_1, $variante_1 );
		}
		
		
		
		// Guardar valor en Session WC
		if (isset($_GET['regalo'])) {
		$regalo = $_GET['regalo'];
		}
		
		$image_list = 'https://i.imgur.com/PvyxtWw.jpg,https://i.imgur.com/PvyxtWw.jpg",https://i.imgur.com/PvyxtWw.jpg';
		
		WC()->session->set('sess_gift', $regalo);
		
		WC()->session->set('sess_images', $image_list);
		
		
		
		wp_safe_redirect( wc_get_checkout_url() );
		
		exit();
 
	}

}




add_action('wp_ajax_cvf_upload_files', 'cvf_upload_files');
add_action('wp_ajax_nopriv_cvf_upload_files', 'cvf_upload_files'); // Allow front-end submission 

function cvf_upload_files(){
    
    $parent_post_id = isset( $_POST['post_id'] ) ? $_POST['post_id'] : 0;  // The parent ID of our attachments
    $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg"); // Supported file types
    $max_file_size = 1024 * 50000; // in kb
    $max_image_upload = 20; // Define how many images can be uploaded to the current post
    $wp_upload_dir = wp_upload_dir();
    $path = $wp_upload_dir['path'] . '/';
    $count = 0;
	$list_files = array();

    $attachments = get_posts( array(
        'post_type'         => 'attachment',
        'posts_per_page'    => -1,
        'post_parent'       => $parent_post_id,
        'exclude'           => get_post_thumbnail_id() // Exclude post thumbnail to the attachment count
    ) );

    // Image upload handler
    if( $_SERVER['REQUEST_METHOD'] == "POST" ){
        
        // Check if user is trying to upload more than the allowed number of images for the current post
        if( ( count( $attachments ) + count( $_FILES['files']['name'] ) ) > $max_image_upload ) {
            $upload_message[] = "Sorry you can only upload " . $max_image_upload . " images for each Ad";
        } else {
            
            foreach ( $_FILES['files']['name'] as $f => $name ) {
                $extension = pathinfo( $name, PATHINFO_EXTENSION );
                // Generate a randon code for each file name
                $new_filename = cvf_td_generate_random_code( 20 )  . '.' . $extension;
                
                if ( $_FILES['files']['error'][$f] == 4 ) {
                    continue; 
                }
                
                if ( $_FILES['files']['error'][$f] == 0 ) {
                    // Check if image size is larger than the allowed file size
                    if ( $_FILES['files']['size'][$f] > $max_file_size ) {
                        $upload_message[] = "$name es demasiado grande, baje a X MB!.";
                        continue;
                    
                    // Check if the file being uploaded is in the allowed file types
                    } elseif( ! in_array( strtolower( $extension ), $valid_formats ) ){
                        $upload_message[] = "$name no es una imagen, formato no valido";
                        continue; 
                    
                    } else{ 
                        // If no errors, upload the file...
                        if( move_uploaded_file( $_FILES["files"]["tmp_name"][$f], $path.$new_filename ) ) {
                            
                            $count++; 

                            $filename = $path.$new_filename;
                            $filetype = wp_check_filetype( basename( $filename ), null );
                            $wp_upload_dir = wp_upload_dir();
                            $attachment = array(
                                'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
                                'post_mime_type' => $filetype['type'],
                                'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
                                'post_content'   => '',
                                'post_status'    => 'inherit'
                            );
                            // Insert attachment to the database
                            $attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );

                            require_once( ABSPATH . 'wp-admin/includes/image.php' );
                            
                            // Generate meta data
                            $attach_data = wp_generate_attachment_metadata( $attach_id, $filename ); 
                            wp_update_attachment_metadata( $attach_id, $attach_data );
							
							// Array Files URL
							array_push($list_files, $wp_upload_dir['url'].'/'.basename( $filename ));
                            
                        }
                    }
                }
            }
        }
    }
    // Loop through each error then output it to the screen
    if ( isset( $upload_message ) ) :
        foreach ( $upload_message as $msg ){        
            printf( __('<p class="bg-danger">%s</p>', 'wp-trade'), $msg );
        }
    endif;
    
    // If no error, show success message
    if( $count != 0 ){
        printf( __('<p class = "bg-success">%d fotos se subieron con exito!</p>', 'wp-trade'), $count );   
		
		// print images upload
        for ($i = 0; $i < count($list_files); $i++)  {
            echo '<img class="croppie_img" src="'. $list_files[$i] .'" />';
        }
		
    }
    
    exit();
}

// Random code generator used for file names.
function cvf_td_generate_random_code($length=10) {
 
   $string = '';
   $characters = "23456789ABCDEFHJKLMNPRTVWXYZabcdefghijklmnopqrstuvwxyz";
 
   for ($p = 0; $p < $length; $p++) {
       $string .= $characters[mt_rand(0, strlen($characters)-1)];
   }
 
   return $string;
 
}



//Croppie
wp_register_script( 'croppie', 'https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js', null, null, true );
wp_enqueue_script('croppie');

wp_register_style( 'croppie', 'https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css' );
wp_enqueue_style('croppie');

wp_enqueue_script( 'script', get_stylesheet_directory_uri() . '/js/script.js', array ( 'jquery' ), 1.1, true);
