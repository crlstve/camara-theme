<?php
/**
 * Custom Nav Menu Fields - Alternative to ACF for nav menus
 * This bypasses ACF validation issues by creating native WordPress nav menu fields
 */

// Add custom fields to nav menu items
function add_custom_nav_menu_fields($item_id, $item, $depth, $args) {
    // Get saved values
    $mega_icon = get_post_meta($item_id, '_menu_item_mega_icon', true);
    $mega_bg_color = get_post_meta($item_id, '_menu_item_mega_bg_color', true);
    $mega_bg_image = get_post_meta($item_id, '_menu_item_mega_bg_image', true);
    $mega_text = get_post_meta($item_id, '_menu_item_mega_text', true);
    ?>
    <div class="menu-item-megamenu" id="menu-item-megamenu-<?php echo $item_id; ?>">
        <p class="field-custom description description-wide">
            <label for="edit-menu-item-mega-icon-<?php echo $item_id; ?>">
                <?php _e('Mega Menú - Icono'); ?><br />
                <input type="text" id="edit-menu-item-mega-icon-<?php echo $item_id; ?>" 
                       class="widefat code edit-menu-item-mega-icon" 
                       name="menu-item-mega-icon[<?php echo $item_id; ?>]" 
                       value="<?php echo esc_attr($mega_icon); ?>" 
                       placeholder="ID de imagen o URL" />
                <button type="button" class="button mega-icon-select" data-target="edit-menu-item-mega-icon-<?php echo $item_id; ?>">
                    Seleccionar Imagen
                </button>
            </label>
        </p>
        
        <p class="field-custom description description-wide">
            <label for="edit-menu-item-mega-bg-color-<?php echo $item_id; ?>">
                <?php _e('Mega Menú - Color de fondo'); ?><br />
                <input type="color" id="edit-menu-item-mega-bg-color-<?php echo $item_id; ?>" 
                       class="edit-menu-item-mega-bg-color" 
                       name="menu-item-mega-bg-color[<?php echo $item_id; ?>]" 
                       value="<?php echo esc_attr($mega_bg_color); ?>" />
            </label>
        </p>
        
        <p class="field-custom description description-wide">
            <label for="edit-menu-item-mega-bg-image-<?php echo $item_id; ?>">
                <?php _e('Mega Menú - Imagen de fondo'); ?><br />
                <input type="text" id="edit-menu-item-mega-bg-image-<?php echo $item_id; ?>" 
                       class="widefat code edit-menu-item-mega-bg-image" 
                       name="menu-item-mega-bg-image[<?php echo $item_id; ?>]" 
                       value="<?php echo esc_attr($mega_bg_image); ?>" 
                       placeholder="ID de imagen o URL" />
                <button type="button" class="button mega-bg-image-select" data-target="edit-menu-item-mega-bg-image-<?php echo $item_id; ?>">
                    Seleccionar Imagen
                </button>
            </label>
        </p>
        
        <p class="field-custom description description-wide">
            <label for="edit-menu-item-mega-text-<?php echo $item_id; ?>">
                <?php _e('Mega Menú - Texto'); ?><br />
                <input type="text" id="edit-menu-item-mega-text-<?php echo $item_id; ?>" 
                       class="widefat code edit-menu-item-mega-text" 
                       name="menu-item-mega-text[<?php echo $item_id; ?>]" 
                       value="<?php echo esc_attr($mega_text); ?>" />
            </label>
        </p>
    </div>
    
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // Media uploader for icons and background images
        $('.mega-icon-select, .mega-bg-image-select').click(function(e) {
            e.preventDefault();
            
            var button = $(this);
            var targetInput = $('#' + button.data('target'));
            
            var mediaUploader = wp.media({
                title: 'Seleccionar Imagen',
                button: {
                    text: 'Usar esta imagen'
                },
                multiple: false
            });
            
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                targetInput.val(attachment.id);
            });
            
            mediaUploader.open();
        });
    });
    </script>
    <?php
}
add_action('wp_nav_menu_item_custom_fields', 'add_custom_nav_menu_fields', 10, 4);

// Save custom nav menu fields
function save_custom_nav_menu_fields($menu_id, $menu_item_db_id, $args) {
    // Save mega icon
    if (isset($_POST['menu-item-mega-icon'][$menu_item_db_id])) {
        $mega_icon = sanitize_text_field($_POST['menu-item-mega-icon'][$menu_item_db_id]);
        update_post_meta($menu_item_db_id, '_menu_item_mega_icon', $mega_icon);
    }
    
    // Save mega background color
    if (isset($_POST['menu-item-mega-bg-color'][$menu_item_db_id])) {
        $mega_bg_color = sanitize_hex_color($_POST['menu-item-mega-bg-color'][$menu_item_db_id]);
        update_post_meta($menu_item_db_id, '_menu_item_mega_bg_color', $mega_bg_color);
    }
    
    // Save mega background image
    if (isset($_POST['menu-item-mega-bg-image'][$menu_item_db_id])) {
        $mega_bg_image = sanitize_text_field($_POST['menu-item-mega-bg-image'][$menu_item_db_id]);
        update_post_meta($menu_item_db_id, '_menu_item_mega_bg_image', $mega_bg_image);
    }
    
    // Save mega text
    if (isset($_POST['menu-item-mega-text'][$menu_item_db_id])) {
        $mega_text = sanitize_text_field($_POST['menu-item-mega-text'][$menu_item_db_id]);
        update_post_meta($menu_item_db_id, '_menu_item_mega_text', $mega_text);
    }
}
add_action('wp_update_nav_menu_item', 'save_custom_nav_menu_fields', 10, 3);

// Helper functions to retrieve the values
function get_nav_menu_mega_icon($menu_item_id) {
    return get_post_meta($menu_item_id, '_menu_item_mega_icon', true);
}

function get_nav_menu_mega_bg_color($menu_item_id) {
    return get_post_meta($menu_item_id, '_menu_item_mega_bg_color', true);
}

function get_nav_menu_mega_bg_image($menu_item_id) {
    return get_post_meta($menu_item_id, '_menu_item_mega_bg_image', true);
}

function get_nav_menu_mega_text($menu_item_id) {
    return get_post_meta($menu_item_id, '_menu_item_mega_text', true);
}

// Add styles for the admin interface
function custom_nav_menu_admin_styles() {
    if (get_current_screen()->id === 'nav-menus') {
        ?>
        <style>
        .menu-item-megamenu {
            background: #f9f9f9;
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px 0;
            border-radius: 3px;
        }
        .menu-item-megamenu .field-custom {
            margin: 8px 0;
        }
        .menu-item-megamenu .button {
            margin-left: 5px;
        }
        .menu-item-megamenu input[type="color"] {
            width: 60px;
            height: 30px;
            border: none;
            border-radius: 3px;
        }
        </style>
        <?php
    }
}
add_action('admin_head', 'custom_nav_menu_admin_styles');
