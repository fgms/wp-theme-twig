<?php

# Adds a html title to the main column on the Post and Page edit screens:
function fgms_html_title_meta($post_type) {
    # Allowed post types to show meta box:
    $post_types = array('post', 'page');

    if (in_array($post_type, $post_types)) {
        # Add a meta box to the administrative interface:
        add_meta_box(
            'fgms-html-title-meta-box', // HTML 'id' attribute of the edit screen section.
            'HTML Title',              // Title of the edit screen section, visible to user.
            'fgms_html_title_meta_meta_box', // Function that prints out the HTML for the edit screen section.
            $post_type,          // The type of Write screen on which to show the edit screen section.
            'advanced',          // The part of the page where the edit screen section should be shown.
            'high'               // The priority within the context where the boxes should show.
        );

    }

}

# Callback that prints the box content:
function fgms_html_title_meta_meta_box($post) {

    # Use `get_post_meta()` to retrieve an existing value from the database and use the value for the form:
    $html_title = get_post_meta($post->ID, '_fgms_html_title_meta', true);

    # Form field to display:
    ?>
        <label class="screen-reader-text" for="fgms_html_title_meta">Deck</label>
        <input id="fgms_html_title_meta" type="text" autocomplete="off" class="large-text" value="<?=esc_attr($html_title)?>" name="fgms_html_title_meta" placeholder="">
    <?php

    # Display the nonce hidden form field:
    wp_nonce_field(
        plugin_basename(__FILE__), // Action name.
        'fgms_html_title_meta_meta_box'        // Nonce name.
    );

}

/**
 * @see http://wordpress.stackexchange.com/a/16267/32387
 */

# Save our custom data when the post is saved:
function fgms_html_title_meta_save_postdata($post_id) {

    # Is the current user is authorised to do this action?
    if (isset($_POST['post_type'])) {
      if ((($_POST['post_type'] === 'page') && current_user_can('edit_page', $post_id) || current_user_can('edit_post', $post_id))) { // If it's a page, OR, if it's a post, can the user edit it? 

          # Stop WP from clearing custom fields on autosave:
          if ((( ! defined('DOING_AUTOSAVE')) || ( ! DOING_AUTOSAVE)) && (( ! defined('DOING_AJAX')) || ( ! DOING_AJAX))) {

              # Nonce verification:
              if (wp_verify_nonce($_POST['fgms_html_title_meta_meta_box'], plugin_basename(__FILE__))) {

                  # Get the posted deck:
                  $deck = ($_POST['fgms_html_title_meta']);

                  # Add, update or delete?
                  if ($deck !== '') {

                      # Deck exists, so add OR update it:
                      add_post_meta($post_id, '_fgms_html_title_meta', $deck, true) OR update_post_meta($post_id, '_fgms_html_title_meta', $deck);

                  } else {

                      # Deck empty or removed:
                      delete_post_meta($post_id, '_fgms_html_title_meta');
                  }
              }
          }
      }      
    }

}

# Get the deck:

function get_fgms_html_title_meta($post_id = FALSE) {
    $post_id = ($post_id) ? $post_id : get_the_ID();
    return apply_filters('the_fgms_html_title_meta', get_post_meta($post_id, '_fgms_html_title_meta', TRUE));

}

# Display deck (this will feel better when OOP):
function the_fgms_html_title_meta() {
    echo get_fgms_html_title_meta(get_the_ID());
}



# Define the custom box:
add_action('add_meta_boxes', 'fgms_html_title_meta');
# Do something with the data entered:
add_action('save_post', 'fgms_html_title_meta_save_postdata');

/**
 * @see http://wordpress.stackexchange.com/questions/36600
 * @see http://wordpress.stackexchange.com/questions/94530/
 */

# Now move advanced meta boxes after the title:
function move_fgms_html_title_meta() {
    global $post, $wp_meta_boxes;
    do_meta_boxes(get_current_screen(), 'advanced', $post);
    # Remove the initial "advanced" meta boxes:
    unset($wp_meta_boxes['post']['advanced']);

}
//add_action('edit_form_after_title', 'move_fgms_html_title_meta');
?>