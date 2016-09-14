<?php
/*
Plugin Name: Test Download
Plugin URI: #
Description: Test download
Author: famethemes
Author URI:  http://www.famethemes.com/
Version: 1.0.1
Text Domain: screenr-plus
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/


class Test_Download {


    /**
     * Download image form url
     *
     * @return bool
     */
    static function download_file( $url, $name = ''){
        if ( ! $url || empty ( $url ) ) {
            return false;
        }
        // These files need to be included as dependencies when on the front end.
        require_once (ABSPATH . 'wp-admin/includes/image.php');
        require_once (ABSPATH . 'wp-admin/includes/file.php');
        require_once (ABSPATH . 'wp-admin/includes/media.php');
        $file_array = array();
        // Download file to temp location.
        $file_array['tmp_name'] = download_url( $url );

        // If error storing temporarily, return the error.
        if ( empty( $file_array['tmp_name'] ) || is_wp_error( $file_array['tmp_name'] ) ) {
            return false;
        }

        if ( $name ) {
            $file_array['name'] = $name;
        }
        $file_array['type'] = 'image/jpeg';
        // Do the validation and storage stuff.
        $id = media_handle_sideload( $file_array, 0 );

        // If error storing permanently, unlink.
        if ( is_wp_error( $id ) ) {
            @unlink( $file_array['tmp_name'] );
            return false;
        }

        return $id;
    }

    static  function test(){
        $url = 'http://fh1k9e2mgp47mzv028skubvh.wpengine.netdna-cdn.com/boston/wp-content/uploads/sites/23/2016/08/pexels-photo-54200-500x350.jpeg';
        $name = 'test-image.jpeg';
        self::download_file( $url,  $name );
    }
}

function test_download_image(){
    if ( isset( $_GET['test_download'] )  ) {
        Test_Download::test();
    }
}

add_action( 'init', 'test_download_image' );
