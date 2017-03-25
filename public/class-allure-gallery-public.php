<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://allurewebsolutions.com
 * @since      1.0.0
 *
 * @package    Allure_Gallery
 * @subpackage Allure_Gallery/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Allure_Gallery
 * @subpackage Allure_Gallery/public
 * @author     Allure Web Solutions <info@allurewebsolutions.com>
 */
class Allure_Gallery_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Allure_Gallery_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Allure_Gallery_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/allure-gallery-public.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Allure_Gallery_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Allure_Gallery_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/allure-gallery-public.js', array('jquery'), $this->version, false);

    }


    /**
     * Allure Gallery Function
     */
    public function allure_gallery()
    { ?>

        <script>
            jQuery(document).ready(function ($) {
                var allureGallery = $('.allure-gallery-yes');
                allureGallery.prepend('<div class="gallery-big"></div>');


                function getMeta(url) {
                    var promise = $.Deferred();
                    var img = new Image();
                    img.src = url;
                    img.onload = function () {
                        promise.resolve({w: this.width, h: this.height});
                    };
                    img.onerror = function () {
                        promise.reject();
                    };

                    return promise;
                }

                if (allureGallery.length > 0) {
                    var intSrc = allureGallery.find('img:first-child').attr('src').replace('-150x150', '');

                    getMeta(intSrc).then(function (data) {
                        var ratio = data.h / data.w;
                        var height = $('.gallery-big').width() * ratio;
                        $('.gallery-big').css('background-image', 'url(' + intSrc + ')').css('height', height + 'px');
                    });

                    allureGallery.on('click', 'img', function (e) {
                        e.preventDefault();
                        var imgSrc = $(this).attr('src').replace('-150x150', ''); // Stores the img's src into a var
                        getMeta(imgSrc).then(function (data) {
                            var ratio = data.h / data.w;
                            var height = $('.gallery-big').width() * ratio;
                            $('.gallery-big').fadeIn().css('background-image', 'url(' + imgSrc + ')').css('height', height + 'px');
                        });

                    });
                }

            });
        </script>

        <style>
            .allure-gallery-yes .gallery-item {
                display: inline-block;
                margin: 0;
                padding: 0;
                cursor: pointer;
                box-sizing: border-box;
            }

            .gallery-big {
                display: block;
                width: 100%;
                background-size: contain;
                background-position: center center;
                background-repeat: no-repeat;
            }

            .gallery a img {
                width: 100%;
                max-width: 80%;
                height: auto;
                max-height: 80%;
                padding: 5px;
                background-color: #fff;
                border: 1px solid #e5e5e5!important;
                transition: all 0.2s linear;
            }
        </style>

    <?php }

    public function allure_gallery_styling()
    {

        // define your backbone template;
        // the "tmpl-" prefix is required,
        // and your input field should have a data-setting attribute
        // matching the shortcode name
        ?>
        <script type="text/html" id="tmpl-allure-gallery-settings">
            <label class="setting">
                <span><?php _e('Activate Allure Gallery'); ?></span>
                <select data-setting="allure_gallery">
                    <option value="yes"> Yes</option>
                    <option value="no"> No</option>
                </select>
            </label>
        </script>

        <script>

            jQuery(document).ready(function () {

                // add your shortcode attribute and its default value to the
                // gallery settings list; $.extend should work as well...
                _.extend(wp.media.gallery.defaults, {
                    allure_gallery: 'no'
                });

                // merge default gallery settings template with yours
                wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
                    template: function (view) {
                        return wp.media.template('gallery-settings')(view)
                            + wp.media.template('allure-gallery-settings')(view);
                    }
                });

            });

        </script>

    <?php }

    /**
     * Shortcode for Allure Gallery
     *
     * @param $output
     * @param $attr
     * @return mixed|string|void
     */
    public function allure_gallery_shortcode($output, $attr)
    {
        global $post, $wp_locale;

        static $instance = 0;
        $instance++;

        // We're trusting author input, so let's at least make sure it looks like a valid orderby statement
        if (isset($attr['orderby'])) {
            $attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
            if (!$attr['orderby'])
                unset($attr['orderby']);
        }

        extract(shortcode_atts(array(
            'order' => 'ASC',
            'orderby' => 'menu_order ID',
            'id' => $post->ID,
            'itemtag' => 'dl',
            'icontag' => 'dt',
            'captiontag' => 'dd',
            'columns' => 3,
            'size' => 'thumbnail',
            'include' => '',
            'exclude' => '',
            'allure_gallery' => 'no'
        ), $attr));

        $id = intval($id);
        if ('RAND' == $order)
            $orderby = 'none';

        if (!empty($include)) {
            $include = preg_replace('/[^0-9,]+/', '', $include);
            $_attachments = get_posts(array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));

            $attachments = array();
            foreach ($_attachments as $key => $val) {
                $attachments[$val->ID] = $_attachments[$key];
            }
        } elseif (!empty($exclude)) {
            $exclude = preg_replace('/[^0-9,]+/', '', $exclude);
            $attachments = get_children(array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
        } else {
            $attachments = get_children(array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
        }

        if (empty($attachments))
            return '';

        if (is_feed()) {
            $output = "\n";
            foreach ($attachments as $att_id => $attachment)
                $output .= wp_get_attachment_link($att_id, $size, true) . "\n";
            return $output;
        }

        $itemtag = tag_escape($itemtag);
        $captiontag = tag_escape($captiontag);
        $columns = intval($columns);
        $itemwidth = $columns > 0 ? floor(100 / $columns) : 100;
        $float = is_rtl() ? 'right' : 'left';

        $selector = "gallery-{$instance}";

        $output = apply_filters('gallery_style', "
        <style type='text/css'>
            #{$selector} {
                max-width: 600px;
                margin: auto;
            }
            #{$selector} .gallery-item {
                float: {$float};
                margin-top: 10px;
                text-align: center;
                width: {$itemwidth}%;           
                }
            #{$selector} img {
                width: 100%;
                height: auto;
            }
            #{$selector} .gallery-caption {
                margin-left: 0;
            }
        </style>
        <!-- see gallery_shortcode() in wp-includes/media.php -->
        <div id='$selector' class='gallery galleryid-{$id} allure-gallery-$allure_gallery'>");

        $i = 0;

        foreach ($attachments as $id => $attachment) {
            $link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) :
                wp_get_attachment_link($id, $size, true, false);

            $output .= "<{$itemtag} class='gallery-item'>";
            $output .= "<{$icontag} class='gallery-icon'>$link</{$icontag}>";
            if ($captiontag && trim($attachment->post_excerpt)) {
                $output .= "<{$captiontag} class='gallery-caption'>" . wptexturize($attachment->post_excerpt) . "</{$captiontag}>";
            }

            $output .= "</{$itemtag}>";

            if ($columns > 0 && ++$i % $columns == 0)
                $output .= '<br style="clear: both"/>';
        }

        $output .= "<br style='clear: both;'/></div>\n";

        return $output;
    }

}
