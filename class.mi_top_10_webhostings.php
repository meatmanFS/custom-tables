<?php

if (!class_exists('mi_top_10_webhostings'))
{
    class mi_top_10_webhostings 
    {
        public function admin_func()
        {   
            $posts = get_posts( array(
                'numberposts'     => -1,
                'orderby'         => 'post_date',
                'order'           => 'ASC',
                'meta_key'        => 'link-redirect-count',
                'meta_value'      => '',
                'post_type'       => 'post',
                'post_status'     => 'publish'
            ) );
            $return = '<table style="width: 100%;text-align: center;font-size: 1.3em;">
            <tr>
                <th>Post ID</th>
                <th>Post Title</th>
                <th>Post Table</th>
                <th>Post Link Redirect Count</th>
            </tr>';
            foreach ($posts as $post)
            {
                $return .= '<tr>';
                $return .= '<td>'.$post->ID;
                $return .= '</td>';
                $return .= '<td>'.get_the_title($post->ID);
                $return .= '</td>';
                $return .= '<td>'.get_post_meta( $post->ID , 'select-table', true);
                $return .= '</td>';
                $return .= '<td>'.get_post_meta( $post->ID , 'link-redirect-count', true);
                $return .= '</td>';
                $return .= '</tr>';
            }
            $return .= '</table>';
            echo $return;
        }
        public function bootstap()
        {
            wp_register_style('bootstrap_min_css', plugins_url('/mi_top_10_webhostings/bootstarp/css/bootstrap.min.css'));
            wp_enqueue_style( 'bootstrap_min_css' );
            wp_register_script("mi_if_ajax", "https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js");
            wp_enqueue_script("mi_if_ajax");
            wp_register_script("bootstrap_min_js", plugins_url('/mi_top_10_webhostings/bootstarp/js/bootstrap.min.js'));
            wp_enqueue_script("bootstrap_min_js");

        }
        public function scripts() 
        {
            wp_register_style('mi_ttwebhostings_css', plugins_url('/mi_top_10_webhostings/css/style.css'));
            wp_enqueue_style( 'mi_ttwebhostings_css' );
            wp_register_script("pbTable", plugins_url('/mi_top_10_webhostings/js/pbTable.js'));
            wp_enqueue_script("pbTable");
            wp_register_script("mi_script", plugins_url('/mi_top_10_webhostings/js/script.js'));
            wp_enqueue_script("mi_script");
        }
        public function sc_func($id)
        {
            global $opt_webhostings;
            $return = '
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">'.get_the_title().'</h3>
                                    <div class="pull-right">
                                            <span class="clickable filter" data-toggle="tooltip" title="" data-container="body" data-original-title="Toggle table filter">
                                                    <i class="glyphicon glyphicon-filter"></i>
                                            </span>
                                    </div>
                                </div>
                                <div class="panel-body">
                                        <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Filter Developers">
                                </div>
            ';
            $return .= '<table class="castom_table table table-hover" id="dev-table"><thead style="cursor: pointer;"><tr>';
            $return.='<th>#</th>'; 
            $return.='<th>Name</th>'; 
            foreach ($opt_webhostings['list-items_meta_new'][$id['id']-1]['section-column-text'] as $item)
            {
                
                $return.='<th>'.$item.'</th>'; 
            }
            $return.='<th>&nbsp;</th>'; 
            $return.= '</tr></thead>';
            
            $posts = get_posts( array(
                'numberposts'     => 10,
                'orderby'         => 'post_date',
                'order'           => 'ASC',
                'meta_key'        => 'select-table',
                'meta_value'      => 'Table'.$id['id'],
                'post_type'       => 'post',
                'post_status'     => 'publish'
            ) );
            $count =1;
            foreach ($posts as $post) 
            {
                $return.= '<tr>';
                setup_postdata($post);
                $return.= '<td>'.$count.'</td>';
                $return.= '<td><b>'.get_the_title($post->ID).'</b></td>';
                foreach ($opt_webhostings['list-items_meta_new'][$id['id']-1]['section-column-text'] as $key => $value)
                {       
                    $post_meta = get_post_meta( $post->ID , 'user-column-type'.$id['id'].'-'.($key+1), true);
                    if (is_array($post_meta))
                    {
                        $return.= '<td><img src="'.$post_meta['url'] .'" alt="" /></td>';
                    }
                    else 
                    {
                       $return.= '<td>'. $post_meta.'</td>'; 
                    }
                    
                     
                }
                $return.= '<td>
                        <a target="_blank" href="?top='.$post->ID.'&amp;name='.get_post_meta( $post->ID , 'link-redirect', true).'" class="btn btn-success btn-block"><span class="glyphicon glyphicon-log-out"></span> Visit</a>
                        <a href="'.  get_the_permalink($post->ID).'" class="btn btn-info btn-block"><span class="glyphicon glyphicon-comment"></span> Reviews</a>
                        </td>';
                $return.= '</tr>';
                
                $count++;
            }
            
            wp_reset_postdata();
            $return .="</table>";
            $return .="</div>";
            $return .="</div>";
            $return .="</div>";
            return $return;
        }
        public function count_redirect()
        {
            $id= $_GET['top'];
            $redir = $_GET['name'];
            
            if (isset($_GET['top']))
            {
                if (is_numeric($id))
                {
                   
                    $link_red = get_post_meta( $id , 'link-redirect', true);
                    if ($redir == $link_red)
                    {
                        if (!get_post_meta( $id , 'link-redirect-count', true))
                        {
                            add_post_meta($id, 'link-redirect-count', "1", true);
                        }
                        else
                        {
                            $count = get_post_meta( $id , 'link-redirect-count', true);
                            $count++;
                            update_post_meta($id, 'link-redirect-count', $count );
                        }
                        wp_redirect($redir, 301); 
                        exit;
                    }                
                }
            }
        }
        public function admin_menu()
        {
            add_options_page('Setup top 10 webhostings', 'Top 10 webhostings',8,'mi_top_10_webhostings',array($this,"admin_func"));
        }
        
    }
}
