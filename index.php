<?php
/**
 * Plugin Name: Allure Gallery
 * Plugin URI: http://allurewebsolutions.com
 * Description: Turns the native WordPress media gallery into a gallery with one large image and thumbnails underneath.
 * Version: 1.0.0
 * Author: Allure Web Solutions
 * Author URI: http://allurewebsolutions.com
 * License: GPL2
 */

add_action('print_media_templates', function () {

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
    <?php

});

// Styling hook for Allure Gallery
function allure_gallery()
{ ?>

    <script>
        $('.allure-gallery-yes').before('<div class="gallery-big"></div>');
        var intSrc = $('.allure-gallery-yes img:first-child').attr('src');
        $('.gallery-big').css('background-image', 'url(' + intSrc + ')');

        $('.allure-gallery-yes img').on('click', function (e) {
            e.preventDefault();
            var imgSrc = $(this).attr('src'); // Stores the img's src into a var
            $('.gallery-big').fadeIn().css('background-image', 'url(' + imgSrc + ')'); // Adds the stored var to the overlay class and fades it in
        });
    </script>

    <style>
        .allure-gallery-yes .gallery-item {
            display: inline-block;
            width: 100px;
            height: 100px;
            margin: 0;
            padding: 0;
            cursor: pointer;
        }

        .gallery-big {
            display: block;
            width: 300px;
            height: 300px;
            display: block; /*none*/
            background-size: cover;
            background-position: center center;
        }
    </style>
<?php }

include "shortcode.php";

add_action('wp_footer', 'allure_gallery');