<?php
/*
  Plugin Name: Pinterest Image Pinner From Collective Bias
  Plugin URI: http://collectivebias.com
  Description: Adds Pin this to all post images
  Version: 1.93
  Author: chriswhittle
  Author URI: http://collectivebias.com
  License: GPL
 */
if (!class_exists("CBPinterestImagePinner")) {

    class CBPinterestImagePinner {

        var $option_name = "cb_pinterest_image_pinner";
        var $js_ver = 1.91;

        function CBPinterestImagePinner() {//constructor

            /* What to do when the plugin is activated? */
            register_activation_hook(__FILE__, array(&$this, 'plugin_install'));

            /* What to do when the plugin is deactivated? */
            register_deactivation_hook(__FILE__, array(&$this, 'plugin_remove'));

            add_action('wp_enqueue_scripts', array(&$this, 'scripts_style_init'));
            add_action('admin_menu', array(&$this, 'admin_menu'));
            add_action('init', array(&$this, 'move_defines_to_js'));
            add_filter('plugin_action_links_' . plugin_basename(__FILE__), array(&$this, 'plugin_settings_link'));
        }

        function scripts_style_init() {
            //Scripts
            wp_enqueue_script('jquery');
            wp_enqueue_script('cb_pinterest', plugins_url( '/scripts/main.js', __FILE__ ) , array('jquery'),$this->js_ver);
            //Styles
            wp_enqueue_style('cb_pinterest', plugins_url( '/styles/style.css', __FILE__ ));
        }

        function plugin_install() {
            /* Create a new database field */
            $admin_options = array('cb_pinterest_plugin_selector' => '.entry img,.advanced-recent-posts img',
                'cb_pinterest_plugin_not_selector' => '#pins-feed-follow img',
                'cb_pinterest_plugin_min_width' => 25,
                'cb_pinterest_plugin_min_height' => 25);
            add_option($this->option_name, $admin_options);
        }

        // Add settings link on plugin page
        function plugin_settings_link($links) {
            $settings_link = '<a href="options-general.php?page=cb-pinterest">Settings</a>';
            array_unshift($links, $settings_link);
            return $links;
        }

        function move_defines_to_js() {
            $defines = "";
            $options = get_option($this->option_name);
            $selector = $options['cb_pinterest_plugin_selector'];
            if (!empty($selector)) {
                $non_selector = $options['cb_pinterest_plugin_not_selector'];
                $min_width = $options['cb_pinterest_plugin_min_width'];
                if (empty($min_width)) {
                    $min_width = 25;
                }
                $min_height = $options['cb_pinterest_plugin_min_height'];
                if (empty($min_height)) {
                    $min_height = 25;
                }

                $defines .= "jQuery(document).ready(function() { " . PHP_EOL;
                $defines .= "   jQuery('body').bind('cb_pinner_reload',function(){ " . PHP_EOL;
                $defines .= "       new CB_Pinterest_Pinner('" . $selector . "','" . $non_selector . "'," . $min_width . "," . $min_height . "); " . PHP_EOL;
                $defines .= "   }); " . PHP_EOL;
                $defines .= "   jQuery('body').trigger('cb_pinner_reload'); " . PHP_EOL;
                $defines .= "}); " . PHP_EOL;
                $js_defined = 'echo "<script>' . $defines . '</script>";';

                add_action('wp_head', create_function('', $js_defined));
            }
        }

        function plugin_remove() {
            /* Delete the database field */
            delete_option($this->option_name);
        }

        function admin_menu() {
            add_options_page('CB Pinterest Options', 'CB Pinterest Settings', 'manage_options', 'cb-pinterest', array(&$this, 'admin_options_page'));
        }

        function admin_options_page() {
            $options = get_option($this->option_name);
            ?>
            <style type="text/css">
                .wrap_<?php echo $this->option_name; ?> {
                    width:100%;
                    clear:both;
                }
                .left_<?php echo $this->option_name; ?> {
                    width:70%;
                    float:left;
                    display:block;
                }
                .right_<?php echo $this->option_name; ?> {
                    width:25%;
                    float:left;
                    display:block;
                    height:325px;
                }
                .right_<?php echo $this->option_name; ?> li{
                    margin:0px;
                    margin-bottom:5px;
                    margin-left:10px;
                    padding:0px;
                }
                .right_<?php echo $this->option_name; ?> a,.right_<?php echo $this->option_name; ?> li{
                    color:#000 !important;
                    margin-left:10px;
                }
                .<?php echo $this->option_name; ?>_logo {
                    width:100%;
                }
            </style>
            <div class="wrap_<?php echo $this->option_name; ?>">

                <h2>Collective Bias Pinterest Plugin Settings</h2>
                <?php
                if (isset($_POST['update_' . $this->option_name])) {
                    if (isset($_POST['cb_pinterest_plugin_selector'])) {
                        $options['cb_pinterest_plugin_selector'] = $_POST['cb_pinterest_plugin_selector'];
                    }
                    if (isset($_POST['cb_pinterest_plugin_not_selector'])) {
                        $options['cb_pinterest_plugin_not_selector'] = $_POST['cb_pinterest_plugin_not_selector'];
                    }
                    if (isset($_POST['cb_pinterest_plugin_min_width'])) {
                        $options['cb_pinterest_plugin_min_width'] = $_POST['cb_pinterest_plugin_min_width'];
                    }
                    if (isset($_POST['cb_pinterest_plugin_min_height'])) {
                        $options['cb_pinterest_plugin_min_height'] = $_POST['cb_pinterest_plugin_min_height'];
                    }
                    update_option($this->option_name, $options);
                    ?>
                    <div class="updated">
                        <p>
                            <strong><?php _e("Settings Updated.", "CBPinterestImagePinner"); ?></strong>
                        </p>
                    </div>
                    <?php
                }

                $selector = $options['cb_pinterest_plugin_selector'];
                $non_selector = $options['cb_pinterest_plugin_not_selector'];
                $min_width = $options['cb_pinterest_plugin_min_width'];
                $min_height = $options['cb_pinterest_plugin_min_height'];
                ?>
                <div class="left_<?php echo $this->option_name; ?>">
                    <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
                        <ul>
                            <li>
                                <label for="cb_pinterest_plugin_selector"> JQuery Selector For What Images Are to Be Pinned <a href="http://api.jquery.com/category/selectors/" target="_blank">see here</a> </label>
                                <br/>
                                <input name="cb_pinterest_plugin_selector" type="text" id="cb_pinterest_plugin_selector" size="100" value="<?php echo $selector; ?>" />
                            </li>
                            <li>
                                <label for="cb_pinterest_plugin_selector"> JQuery Selector For What Images Are Not to Be Pinned <a href="http://api.jquery.com/not/" target="_blank">see here</a> </label>
                                <br/>
                                <input name="cb_pinterest_plugin_not_selector" type="text" id="cb_pinterest_plugin_not_selector" size="100" value="<?php echo $non_selector; ?>" />
                            </li>
                            <li>
                                <label>Min Width of Image To Be Pinned(in pixels)</label>
                                <br/>
                                <input name="cb_pinterest_plugin_min_width" type="text" id="cb_pinterest_plugin_min_width" maxlength="5" size="5" value="<?php echo $min_width; ?>" />
                            </li>
                            <li>
                                <label>Min Height of Image To Be Pinned(in pixels)</label>
                                <br/>
                                <input name="cb_pinterest_plugin_min_height" type="text" id="cb_pinterest_plugin_min_height" maxlength="5" size="5" value="<?php echo $min_height; ?>" />
                            </li>
                        </ul>
                        <input type="hidden" name="update_<?php echo $this->option_name; ?>" value="true" />
                        <input type="submit" value="Save Changes" />
                    </form>
                </div>
                <div class="right_<?php echo $this->option_name; ?>">
                    <img src="http://cbi.as/pinterestpluginlogo" class="<?php echo $this->option_name; ?>_logo" />
                    <ul>
                        <li>Links</li>
                        <li><a href="http://collectivebias.com" target="_blank">Collective Bias</a></li>
                        <li><a href="http://cbi.as" target="_blank">Url Shortener</a></li>
                    </ul>
                </div>
            </div>
            <?php
        }

    }

}
if (class_exists("CBPinterestImagePinner")) {
    $cb_pinterest_image_pinner = new CBPinterestImagePinner();
}
