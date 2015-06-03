<?php 
	/* Redux framework init */
	if ( !isset( $opt_webhostings ) && file_exists( dirname( __FILE__ ) . '/admin/metaboxes.php' ) ) {
	    require_once( dirname( __FILE__ ) . '/admin/metaboxes.php' );
	}

	if(!function_exists('redux_register_custom_extension_loader')) :
		function redux_register_custom_extension_loader($ReduxFramework) {
			$path = dirname( __FILE__ ) . '/admin/ReduxFramework/ReduxCore/extensions/';
			$folders = scandir( $path, 1 );		   
			foreach($folders as $folder) {
				if ($folder === '.' or $folder === '..' or !is_dir($path . $folder) ) {
					continue;	
				} 
				$extension_class = 'ReduxFramework_Extension_' . $folder;
				if( !class_exists( $extension_class ) ) {
					// In case you wanted override your override, hah.
					$class_file = $path . $folder . '/extension_' . $folder . '.php';
					$class_file = apply_filters( 'redux/extension/'.$ReduxFramework->args['opt_name'].'/'.$folder, $class_file );
					if( $class_file ) {
						require_once( $class_file );
						$extension = new $extension_class( $ReduxFramework );
					}
				}
			}
		}
		
		add_action("redux/extensions/opt_webhostings/before", 'redux_register_custom_extension_loader', 0);

	endif;

	if ( !class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/admin/ReduxFramework/ReduxCore/framework.php' ) ) {
    	require_once( dirname( __FILE__ ) . '/admin/ReduxFramework/ReduxCore/framework.php' );
	}
	if ( !isset( $products ) && file_exists( dirname( __FILE__ ) . '/admin/redux-config.php' ) ) {
	    require_once( dirname( __FILE__ ) . '/admin/redux-config.php' );
	}

?>