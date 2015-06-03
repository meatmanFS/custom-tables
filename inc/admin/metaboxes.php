<?php 
if ( !function_exists( "redux_add_metaboxes" ) ):
    function redux_add_metaboxes($metaboxes) {
        $get_webhostings = get_option('opt_webhostings');
        
            $res_arr[] =array(
                    'id'=>'link-redirect',
                    'type' => 'text', 
                    'title' => __('Link redirect', 'products'),
            );
            foreach ($get_webhostings['list-items_meta_new'] as $key => $value)
            {          
                $tables['Table'.($key+1)] = 'Table #'.($key+1);
            }       

            $res_arr[] = array(
                    'id'=>'select-table',
                    'type' => 'select', 
                    'title' => __('Select table', 'products'),
                    'options'  => $tables,
            );
        if (!empty($get_webhostings))
        {    
            foreach ($get_webhostings['list-items_meta_new'] as $key => $value)
            {          
                foreach ($value['section-column-text'] as $keyt => $valuet)
                {
                    $res_arr[] = array(
                        'id'=>'user-column-type'.($key+1).'-'.($keyt+1),
                        'type' => $get_webhostings['section-column-type'.($key+1).'-'.($keyt+1)], 
                        'title' => __('Table'.($key+1).' column-'.$valuet),
                        'required' => array('select-table', '=' , 'Table'.($key+1))
                    );
                }   
            }
        }    
        
	$homepage_amazon_ebay_links = array(
			array(
				'fields' => $res_arr
                            ),    
            );	
        $metaboxes = array();

        $metaboxes[] = array(
                'id' => 'slider-options',
                'title' => __('Product page options', 'products'),
                'post_types' => array('post'),
                'position' => 'normal',
                'priority' => 'high',
                'sections' => $homepage_amazon_ebay_links
        );

		

		return $metaboxes;
	}
	add_action('redux/metaboxes/opt_webhostings/boxes', 'redux_add_metaboxes');
endif;
?>