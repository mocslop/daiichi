<?php
define('BASE_URL', get_template_directory_uri());
include get_template_directory().'/includes/admin/function-admin.php';
include get_template_directory().'/includes/admin/core.php';
include get_template_directory().'/includes/admin/custom-post-type.php';  
include get_template_directory().'/includes/admin/aio-list-categories/aio-list-category.php';  

function load_admin_style() {
  wp_register_style( 'admin_css', get_template_directory_uri() . '/css/admin.css', false, '1.0.0' );
  wp_enqueue_style('admin_css');
}
add_action( 'admin_enqueue_scripts', 'load_admin_style' );

function load_admin_js() {
  wp_register_script( 'admin_js', get_template_directory_uri() . '/js/admin.js', false, '1.0.0' );
  wp_enqueue_script('admin_js');
}
add_action( 'admin_enqueue_scripts', 'load_admin_js' );

// Navigation menus 
register_nav_menus(array(
  'primary' => __('Primary Menu'),
  'menu_mobile' => __('Mobile Menu')
));
  // Get top ancestor id
function get_top_ancestor_id(){
  global $post;
  if($post->post_parent){
    $ancestors= array_reverse(get_post_ancestors($post->ID));
    return $ancestors[0];
  } 
  return $post->ID;
}
  // Does page have children ? 
function has_children(){
  global $post;
  $pages = get_pages('child_of=' . $post->ID);
  return count($pages);
}
  // Customize excerpt word count length
function excerpt($limit) {
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  } 
  $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
  return $excerpt;
}
  // ADD FEATURED IMAGE SUPPORT
function featured_images_setup(){
  add_theme_support('post-thumbnails');
  }
  add_action('after_setup_theme','featured_images_setup');

  // ADD OUR WIDGETS LOCATION
  function our_widget_inits(){

    register_sidebar(array(
      'name' => 'Sidebar',
      'id' => 'sidebar1',
      'before_widget' => '<div id="%1$s" class="widget %2$s widget_area">',
      'after_widget' => "</div>",
      'before_title' => '<h3 class="widget-title">',
      'after_title' => '</h3>',
    ));
      register_sidebar(array(
    'name' => 'Footer area 1',
    'id' => 'footer1',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
  ));
  register_sidebar(array(
    'name' => 'Footer area 2',
    'id' => 'footer2',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
  ));
  }
  add_action('widgets_init','our_widget_inits');
  /** Filter & Hook In Widget Before Post Content .*/
  function before_post_widget() {
    if ( is_home() && is_active_sidebar( 'sidebar1' ) ) { 
      dynamic_sidebar('sidebar1', array(
        'before' => '<div class="before-post">',
        'after' => '</div>',
      ) );      
    }
  }
  add_action( 'woo_loop_before', 'before_post_widget' );
// ADD THEME CUSTOM LOGO
  add_theme_support( 'custom-logo' );
//  ADD BREADCRUMB
  function the_breadcrumb() {
     
    if (!is_front_page()) {
      echo '<ul>';
      echo '<li><a href="';
      echo home_url();
      echo '">';
      if(get_locale() == 'en_US'){ echo 'Home';} else{ echo 'Trang chủ ';}
      echo "</a><li>";
      if (is_category() || is_single()) {
        echo '';
        the_category(' ');
        if (is_single()) {
          echo "<li>";
          the_title();
          echo '</li>';
        }
      } elseif (is_page()) {
        echo '';
        echo the_title();
        echo '';
      } elseif (is_home()) {
        echo wp_title('');
      }
      echo '</ul>';
    }
    elseif (is_tag()) {single_tag_title();}
    elseif (is_day()) {echo"Archive for "; the_time('F jS, Y'); echo'';}
    elseif (is_month()) {echo"Archive for "; the_time('F, Y'); echo'';}
    elseif (is_year()) {echo"Archive for "; the_time('Y'); echo'';}
    elseif (is_author()) {echo"Author Archive"; echo'';}
    elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {echo "Blog Archives"; echo'';}
    elseif (is_search()) {echo"Search Results"; echo'';}

  }
//  END BREADCRUMB

/*
 *  DUPLICATE POST IN  ADMIN. Dups appear as drafts. User is redirected to the edit screen
 */
function rd_duplicate_post_as_draft(){
  global $wpdb;
  if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'rd_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
    wp_die('No post to duplicate has been supplied!');
  }

  /*
   * Nonce verification
   */
  if ( !isset( $_GET['duplicate_nonce'] ) || !wp_verify_nonce( $_GET['duplicate_nonce'], basename( __FILE__ ) ) )
    return;

  /*
   * get the original post id
   */
  $post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
  /*
   * and all the original post data then
   */
  $post = get_post( $post_id );

  /*
   * if you don't want current user to be the new post author,
   * then change next couple of lines to this: $new_post_author = $post->post_author;
   */
  $current_user = wp_get_current_user();
  $new_post_author = $current_user->ID;

  /*
   * if post data exists, create the post duplicate
   */
  if (isset( $post ) && $post != null) {

    /*
     * new post data array
     */
    $args = array(
      'comment_status' => $post->comment_status,
      'ping_status'    => $post->ping_status,
      'post_author'    => $new_post_author,
      'post_content'   => $post->post_content,
      'post_excerpt'   => $post->post_excerpt,
      'post_name'      => $post->post_name,
      'post_parent'    => $post->post_parent,
      'post_password'  => $post->post_password,
      'post_status'    => 'draft',
      'post_title'     => $post->post_title,
      'post_type'      => $post->post_type,
      'to_ping'        => $post->to_ping,
      'menu_order'     => $post->menu_order
    );

    /*
     * insert the post by wp_insert_post() function
     */
    $new_post_id = wp_insert_post( $args );

    /*
     * get all current post terms ad set them to the new post draft
     */
    $taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
    foreach ($taxonomies as $taxonomy) {
      $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
      wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
    }

    /*
     * duplicate all post meta just in two SQL queries
     */
    $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
    if (count($post_meta_infos)!=0) {
      $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
      foreach ($post_meta_infos as $meta_info) {
        $meta_key = $meta_info->meta_key;
        if( $meta_key == '_wp_old_slug' ) continue;
        $meta_value = addslashes($meta_info->meta_value);
        $sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
      }
      $sql_query.= implode(" UNION ALL ", $sql_query_sel);
      $wpdb->query($sql_query);
    }


    /*
     * finally, redirect to the edit post screen for the new draft
     */
    wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
    exit;
  } else {
    wp_die('Post creation failed, could not find original post: ' . $post_id);
  }
}
add_action( 'admin_action_rd_duplicate_post_as_draft', 'rd_duplicate_post_as_draft' );

/*
 * Add the duplicate link to action list for post_row_actions
 */
function rd_duplicate_post_link( $actions, $post ) {
  if (current_user_can('edit_posts')) {
    $actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=rd_duplicate_post_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce' ) . '" title="Duplicate this item" rel="permalink">Nhân bản</a>';
  }
  return $actions;
}

add_filter( 'post_row_actions', 'rd_duplicate_post_link', 10, 2 );
// duplicate page
//add_filter('page_row_actions', 'rd_duplicate_post_link', 10, 2);


// REMOVE CSS WP_HEAD
//xoa header
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action( 'wp_head', 'feed_links', 2 ); 
remove_action('wp_head', 'feed_links_extra', 3 );
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');

// Keep old Editor
   add_filter('use_block_editor_for_post', '__return_false');

// Remove description heading in tabs content
  add_filter('woocommerce_product_description_heading', '__return_null');


/* WRAP IMAGE POST CONTENT WITH FIGURE*/
function filter_images($content){
    return preg_replace('/<img (.*) \/>\s*/iU', '<figure><img \1 /></figure>', $content);
}
add_filter('the_content', 'filter_images');
/* END WRAP IMAGE POST CONTENT WITH FIGURE*/

/* REMOVE EMPTY P */

add_filter('the_content', 'remove_empty_p', 20, 1);
function remove_empty_p($content){
    $content = force_balance_tags($content);
    return preg_replace('#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content);
}

//SHOW POST COUNT VIEWS 
function wpb_set_post_views($postID) {
    $count_key = 'wpb_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 1;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '1');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

function wpb_get_post_views($postID){
    $count_key = 'wpb_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '1');
        return "1";
    }
    return $count.'';
}
if ( !function_exists( 'add_action' ) ) { exit; 
}
function VelvetBluesUU_add_management_page(){
  add_management_page("Velvet Blues Update URLs", "Update URLs", "manage_options", basename(__FILE__), "VelvetBluesUU_management_page");
}
function VelvetBluesUU_load_textdomain(){
  load_plugin_textdomain( 'velvet-blues-update-urls', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
function VelvetBluesUU_management_page(){
  if ( !function_exists( 'VB_update_urls' ) ) {
    function VB_update_urls($options,$oldurl,$newurl){  
      global $wpdb;
      $results = array();
      $queries = array(
      'content' =>    array("UPDATE $wpdb->posts SET post_content = replace(post_content, %s, %s)",  __('Content Items (Posts, Pages, Custom Post Types, Revisions)','velvet-blues-update-urls') ),
      'excerpts' =>   array("UPDATE $wpdb->posts SET post_excerpt = replace(post_excerpt, %s, %s)", __('Excerpts','velvet-blues-update-urls') ),
      'attachments' =>  array("UPDATE $wpdb->posts SET guid = replace(guid, %s, %s) WHERE post_type = 'attachment'",  __('Attachments','velvet-blues-update-urls') ),
      'links' =>      array("UPDATE $wpdb->links SET link_url = replace(link_url, %s, %s)", __('Links','velvet-blues-update-urls') ),
      'custom' =>     array("UPDATE $wpdb->postmeta SET meta_value = replace(meta_value, %s, %s)",  __('Custom Fields','velvet-blues-update-urls') ),
      'guids' =>      array("UPDATE $wpdb->posts SET guid = replace(guid, %s, %s)",  __('GUIDs','velvet-blues-update-urls') )
      );
      foreach($options as $option){
        if( $option == 'custom' ){
          $n = 0;
          $row_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->postmeta" );
          $page_size = 10000;
          $pages = ceil( $row_count / $page_size );
          
          for( $page = 0; $page < $pages; $page++ ) {
            $current_row = 0;
            $start = $page * $page_size;
            $end = $start + $page_size;
            $pmquery = "SELECT * FROM $wpdb->postmeta WHERE meta_value <> ''";
            $items = $wpdb->get_results( $pmquery );
            foreach( $items as $item ){
            $value = $item->meta_value;
            if( trim($value) == '' )
              continue;
            
              $edited = VB_unserialize_replace( $oldurl, $newurl, $value );
            
              if( $edited != $value ){
                $fix = $wpdb->query("UPDATE $wpdb->postmeta SET meta_value = '".$edited."' WHERE meta_id = ".$item->meta_id );
                if( $fix )
                  $n++;
              }
            }
          }
          $results[$option] = array($n, $queries[$option][1]);
        }
        else{
          $result = $wpdb->query( $wpdb->prepare( $queries[$option][0], $oldurl, $newurl) );
          $results[$option] = array($result, $queries[$option][1]);
        }
      }
      return $results;      
    }
  }
  if ( !function_exists( 'VB_unserialize_replace' ) ) {
    function VB_unserialize_replace( $from = '', $to = '', $data = '', $serialised = false ) {
      try {
        if ( false !== is_serialized( $data ) ) {
          $unserialized = unserialize( $data );
          $data = VB_unserialize_replace( $from, $to, $unserialized, true );
        }
        elseif ( is_array( $data ) ) {
          $_tmp = array( );
          foreach ( $data as $key => $value ) {
            $_tmp[ $key ] = VB_unserialize_replace( $from, $to, $value, false );
          }
          $data = $_tmp;
          unset( $_tmp );
        }
        else {
          if ( is_string( $data ) )
            $data = str_replace( $from, $to, $data );
        }
        if ( $serialised )
          return serialize( $data );
      } catch( Exception $error ) {
      }
      return $data;
    }
  }
  if ( isset( $_POST['VBUU_settings_submit'] ) && !check_admin_referer('VBUU_submit','VBUU_nonce')){
    if(isset($_POST['VBUU_oldurl']) && isset($_POST['VBUU_newurl'])){
      if(function_exists('esc_attr')){
        $vbuu_oldurl = esc_attr(trim($_POST['VBUU_oldurl']));
        $vbuu_newurl = esc_attr(trim($_POST['VBUU_newurl']));
      }else{
        $vbuu_oldurl = attribute_escape(trim($_POST['VBUU_oldurl']));
        $vbuu_newurl = attribute_escape(trim($_POST['VBUU_newurl']));
      }
    }
    echo '<div id="message" class="error fade"><p><strong>'.__('ERROR','velvet-blues-update-urls').' - '.__('Please try again.','velvet-blues-update-urls').'</strong></p></div>';
  }
  elseif( isset( $_POST['VBUU_settings_submit'] ) && !isset( $_POST['VBUU_update_links'] ) ){
    if(isset($_POST['VBUU_oldurl']) && isset($_POST['VBUU_newurl'])){
      if(function_exists('esc_attr')){
        $vbuu_oldurl = esc_attr(trim($_POST['VBUU_oldurl']));
        $vbuu_newurl = esc_attr(trim($_POST['VBUU_newurl']));
      }else{
        $vbuu_oldurl = attribute_escape(trim($_POST['VBUU_oldurl']));
        $vbuu_newurl = attribute_escape(trim($_POST['VBUU_newurl']));
      }
    }
    echo '<div id="message" class="error fade"><p><strong>'.__('ERROR','velvet-blues-update-urls').' - '.__('Your URLs have not been updated.','velvet-blues-update-urls').'</p></strong><p>'.__('Please select at least one checkbox.','velvet-blues-update-urls').'</p></div>';
  }
  elseif( isset( $_POST['VBUU_settings_submit'] ) ){
    $vbuu_update_links = $_POST['VBUU_update_links'];
    if(isset($_POST['VBUU_oldurl']) && isset($_POST['VBUU_newurl'])){
      if(function_exists('esc_attr')){
        $vbuu_oldurl = esc_attr(trim($_POST['VBUU_oldurl']));
        $vbuu_newurl = esc_attr(trim($_POST['VBUU_newurl']));
      }else{
        $vbuu_oldurl = attribute_escape(trim($_POST['VBUU_oldurl']));
        $vbuu_newurl = attribute_escape(trim($_POST['VBUU_newurl']));
      }
    }
    if(($vbuu_oldurl && $vbuu_oldurl != 'http://www.oldurl.com' && trim($vbuu_oldurl) != '') && ($vbuu_newurl && $vbuu_newurl != 'http://www.newurl.com' && trim($vbuu_newurl) != '')){
      $results = VB_update_urls($vbuu_update_links,$vbuu_oldurl,$vbuu_newurl);
      $empty = true;
      $emptystring = '<strong>'.__('Why do the results show 0 URLs updated?','velvet-blues-update-urls').'</strong><br/>'.__('This happens if a URL is incorrect OR if it is not found in the content. Check your URLs and try again.','velvet-blues-update-urls').'<br/><br/><strong>'.__('Want us to do it for you?','velvet-blues-update-urls').'</strong><br/>'.__('Contact us at','velvet-blues-update-urls').' <a href="mailto:info@velvetblues.com?subject=Move%20My%20WP%20Site">info@velvetblues.com</a>. '.__('We will backup your website and move it for $75 OR simply update your URLs for only $39.','velvet-blues-update-urls');

      $resultstring = '';
      foreach($results as $result){
        $empty = ($result[0] != 0 || $empty == false)? false : true;
        $resultstring .= '<br/><strong>'.$result[0].'</strong> '.$result[1];
      }
      
      if( $empty ):
      ?>
<div id="message" class="error fade">
<table>
<tr>
  <td><p><strong>
      <?php _e('ERROR: Something may have gone wrong.','velvet-blues-update-urls'); ?>
      </strong><br/>
      <?php _e('Your URLs have not been updated.','velvet-blues-update-urls'); ?>
    </p>
    <?php
      else:
      ?>
    <div id="message" class="updated fade">
      <table>
        <tr>
          <td><p><strong>
              <?php _e('Success! Your URLs have been updated.','velvet-blues-update-urls'); ?>
              </strong></p>
            <?php
      endif;
      ?>
            <p><u>
              <?php _e('Results','velvet-blues-update-urls'); ?>
              </u><?php echo $resultstring; ?></p>
            <?php echo ($empty)? '<p>'.$emptystring.'</p>' : ''; ?></td>
          <td width="60"></td>
          <td align="center"><?php if( !$empty ): ?>
            <p>
              <?php //You can now uninstall this plugin.<br/> ?>
              <?php printf(__('If you found our plugin useful, %s please consider donating','velvet-blues-update-urls'),'<br/>'); ?>.</p>
            <p><a style="outline:none;" href="http://www.velvetblues.com/go/updateurlsdonate/" target="_blank"><img src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" alt="PayPal -<?php _e('The safer, easier way to pay online!','velvet-blues-update-urls'); ?>"></a></p>
            <?php endif; ?></td>
        </tr>
      </table>
    </div>
    <?php
    }
    else{
      echo '<div id="message" class="error fade"><p><strong>'.__('ERROR','velvet-blues-update-urls').' - '.__('Your URLs have not been updated.','velvet-blues-update-urls').'</p></strong><p>'.__('Please enter values for both the old url and the new url.','velvet-blues-update-urls').'</p></div>';
    }
  }
?>
    <div class="wrap">
    <h2>Velvet Blues Update URLs</h2>
    <form method="post" action="tools.php?page=<?php echo basename(__FILE__); ?>">
      <?php wp_nonce_field('VBUU_submit','VBUU_nonce'); ?>
      <p><?php printf(__("After moving a website, %s lets you fix old URLs in content, excerpts, links, and custom fields.",'velvet-blues-update-urls'),'<strong>Update URLs</strong>'); ?></p>
      <p><strong>
        <?php _e('WE RECOMMEND THAT YOU BACKUP YOUR WEBSITE.','velvet-blues-update-urls'); ?>
        </strong><br/>
        <?php _e('You may need to restore it if incorrect URLs are entered in the fields below.','velvet-blues-update-urls'); ?>
      </p>
      <h3 style="margin-bottom:5px;">
        <?php _e('Step'); ?>
        1:
        <?php _e('Enter your URLs in the fields below','velvet-blues-update-urls'); ?>
      </h3>
      <table class="form-table">
        <tr valign="middle">
          <th scope="row" width="140" style="width:140px"><strong>
            <?php _e('Old URL','velvet-blues-update-urls'); ?>
            </strong><br/>
            <span class="description">
            <?php _e('Old Site Address','velvet-blues-update-urls'); ?>
            </span></th>
          <td><input name="VBUU_oldurl" type="text" id="VBUU_oldurl" value="<?php echo (isset($vbuu_oldurl) && trim($vbuu_oldurl) != '')? $vbuu_oldurl : 'http://www.oldurl.com'; ?>" style="width:300px;font-size:20px;" onfocus="if(this.value=='http://www.oldurl.com') this.value='';" onblur="if(this.value=='') this.value='http://www.oldurl.com';" /></td>
        </tr>
        <tr valign="middle">
          <th scope="row" width="140" style="width:140px"><strong>
            <?php _e('New URL','velvet-blues-update-urls'); ?>
            </strong><br/>
            <span class="description">
            <?php _e('New Site Address','velvet-blues-update-urls'); ?>
            </span></th>
          <td><input name="VBUU_newurl" type="text" id="VBUU_newurl" value="<?php echo (isset($vbuu_newurl) && trim($vbuu_newurl) != '')? $vbuu_newurl : 'http://www.newurl.com'; ?>" style="width:300px;font-size:20px;" onfocus="if(this.value=='http://www.newurl.com') this.value='';" onblur="if(this.value=='') this.value='http://www.newurl.com';" /></td>
        </tr>
      </table>
      <br/>
      <h3 style="margin-bottom:5px;">
        <?php _e('Step'); ?>
        2:
        <?php _e('Choose which URLs should be updated','velvet-blues-update-urls'); ?>
      </h3>
      <table class="form-table">
        <tr>
          <td><p style="line-height:20px;">
              <input name="VBUU_update_links[]" type="checkbox" id="VBUU_update_true" value="content" checked="checked" />
              <label for="VBUU_update_true"><strong>
                <?php _e('URLs in page content','velvet-blues-update-urls'); ?>
                </strong> (
                <?php _e('posts, pages, custom post types, revisions','velvet-blues-update-urls'); ?>
                )</label>
              <br/>
              <input name="VBUU_update_links[]" type="checkbox" id="VBUU_update_true1" value="excerpts" />
              <label for="VBUU_update_true1"><strong>
                <?php _e('URLs in excerpts','velvet-blues-update-urls'); ?>
                </strong></label>
              <br/>
              <input name="VBUU_update_links[]" type="checkbox" id="VBUU_update_true2" value="links" />
              <label for="VBUU_update_true2"><strong>
                <?php _e('URLs in links','velvet-blues-update-urls'); ?>
                </strong></label>
              <br/>
              <input name="VBUU_update_links[]" type="checkbox" id="VBUU_update_true3" value="attachments" />
              <label for="VBUU_update_true3"><strong>
                <?php _e('URLs for attachments','velvet-blues-update-urls'); ?>
                </strong> (
                <?php _e('images, documents, general media','velvet-blues-update-urls'); ?>
                )</label>
              <br/>
              <input name="VBUU_update_links[]" type="checkbox" id="VBUU_update_true4" value="custom" />
              <label for="VBUU_update_true4"><strong>
                <?php _e('URLs in custom fields and meta boxes','velvet-blues-update-urls'); ?>
                </strong></label>
              <br/>
              <input name="VBUU_update_links[]" type="checkbox" id="VBUU_update_true5" value="guids" />
              <label for="VBUU_update_true5"><strong>
                <?php _e('Update ALL GUIDs','velvet-blues-update-urls'); ?>
                </strong> <span class="description" style="color:#f00;">
                <?php _e('GUIDs for posts should only be changed on development sites.','velvet-blues-update-urls'); ?>
                </span> <a href="http://codex.wordpress.org/Changing_The_Site_URL#Important_GUID_Note" target="_blank">
                <?php _e('Learn More.','velvet-blues-update-urls'); ?>
                </a></label>
            </p></td>
        </tr>
      </table>
      <p>
        <input class="button-primary" name="VBUU_settings_submit" value="<?php _e('Update URLs NOW','velvet-blues-update-urls'); ?>" type="submit" />
      </p>
    </form>
    <p>&nbsp;<br/>
      <strong>
      <?php _e('Need help?','velvet-blues-update-urls'); ?>
      </strong> <?php printf(__("Get support at the %s plugin page%s.",'velvet-blues-update-urls'),'<a href="http://www.velvetblues.com/web-development-blog/wordpress-plugin-update-urls/" target="_blank">Velvet Blues Update URLs','</a>'); ?>
      <?php if( !isset( $empty ) ): ?>
      <br/>
      <strong>
      <?php _e('Want us to do it for you?','velvet-blues-update-urls'); ?>
      </strong>
      <?php _e('Contact us at','velvet-blues-update-urls'); ?>
      <a href="mailto:info@velvetblues.com?subject=Move%20My%20WP%20Site">info@velvetblues.com</a>.
      <?php _e('We will backup your website and move it for $75 OR update your URLs for only $29.','velvet-blues-update-urls'); ?>
      <?php endif; ?>
    </p>
    <?php
}
add_action('admin_menu', 'VelvetBluesUU_add_management_page');
add_action('admin_init','VelvetBluesUU_load_textdomain');
// END SHOW POST COUNT VIEWS


 
