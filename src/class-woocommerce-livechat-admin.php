<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once( 'interface-woocommerce-livechat.php' );
require_once( 'class-woocommerce-livechat.php' );

/**
 * WooCommerce_LiveChat_Admin class for managing LiveChat settings.
 */
class WooCommerce_LiveChat_Admin extends WooCommerce_LiveChat implements WooCommerce_LiveChat_Interface
{
    /**
     * API url's
     */
    const LC_ACOUNT_DETAILS_URL_PATTERN = 'https://api.livechatinc.com/v2/license/%licenseId%';
    const LC_CHECK_LICENSE              = 'https://api.livechatinc.com/licence/operator/';
    const LC_NEW_LICENSE                = 'https://api.livechatinc.com/v2/license/';

    /**
     * @var object current user info
     */
    private $user_info;
    /**
     * @var string plugin url
     */
    private $plugin_url;
    /**
     * @var string plugin version
     */
    private $plugin_version;
    /**
     * @var string force template name (for rendering errors)
     */
    private $force_template;

    /**
     * Set up base plugin info.
     */
    public function __construct() {
        $this->set_up_plugin_url();
        $this->set_up_plugin_version();
    }

    /**
     * Set up base actions.
     */
    public function init() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'wp_ajax_wc-livechat-update-settings', array( $this, 'update_settings' ) );
        add_action( 'wp_ajax_wc-livechat-check-cart', array( $this, 'check_cart' ) );

        if ( 'wc-livechat' == $_GET['page'] ) {
            add_action( 'admin_head', array( $this, 'admin_head_block' ) );
        }
    }

    /**
     * Render admin head block.
     */
    public function admin_head_block() {
        $this->get_renderer()->render( 'admin-head-block-template.php', array(
            'username'          => $this->get_user_property('user_login'),
            'user_email'        => $this->get_user_property('user_email'),
            'check_license_url' => self::LC_CHECK_LICENSE,
            'set_settings_url'  => admin_url() . 'admin-ajax.php',
            'new_license_url'   => self::LC_NEW_LICENSE,
        ));
        wp_enqueue_script( 'wc-livechat', $this->plugin_url . '/js/wc-livechat.js', 'jquery', $this->plugin_version, true );
    }

    /**
     * Add plugin to admin menu.
     */
    public function admin_menu() {
        add_submenu_page( 'woocommerce', 'LiveChat', 'LiveChat', 'manage_options', 'wc-livechat', array( $this, 'settings_action' ) );
    }

    /**
     * Update user settings (license id, group and custom params)
     */
    public function update_settings() {
        if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
            if ( array_key_exists( 'group', $_POST ) ) {
                $updated = $this->update_group( (int) $_POST['group'] );
            } else if ( array_key_exists( 'licenseId', $_POST ) ) {
                $updated = $this->set_up_license_id( (int) $_POST['licenseId'] );
            } else if ( array_key_exists( 'customDataSettings', $_POST ) ) {
                $settings = explode( ':', $_POST['customDataSettings'] );
                if ( 2 === count( $settings ) ) {
                    $updated = $this->update_custom_data_settings( $settings[0], $settings[1] );
                }
            }
        }

        echo ( $updated === true ) ? json_encode( 'ok' ) : json_encode( 'err' );
        wp_die();
    }

    /**
     * Returns plugin settings template depends on LiveChat user details.
     *
     * @return string
     */
    public function settings_action() {
        if ( array_key_exists( 'reset', $_GET ) && 1 == $_GET['reset'] ) {
            // Reset user email and redirect to plugin login/register page.
            $this->reset_settings();
            $redirect_url = ( false === strpos(wp_get_referer(), '&reset=1') ) ?
                wp_get_referer() : str_replace( '&reset=1', '', wp_get_referer() );
            if ( headers_sent() ) {
                die( '<script> location.replace("' . $redirect_url . '"); </script>' );
            }
            wp_redirect( $redirect_url );
            wp_die();
        }

        if ( null === ( $license_id = $this->get_license() ) ) {
            // If there is no give license, render plugin login/register page.
            return $this->get_renderer()->render(
                'create-new-account-template.php',
                array(
                    'username'  => $this->get_user_property( 'user_login' ),
                    'useremail' => $this->get_user_property( 'user_email' ),
                )
            );
        }

        if ( false === $this->is_license_active( $license_id ) ) {
            // If license is not active, render inactive license page.
            if ( null !== $this->force_template ) {
                return $this->get_renderer()->render( $this->force_template );
            }
            return $this->get_renderer()->render( 'inactive-license-template.php' );
        }
        // By default, render settings page.
        return $this->get_renderer()->render(
            'settings-template.php',
            array(
                'username'                      => $this->get_user_property( 'user_login' ),
                'settings'                      => $this->get_custom_data_settings(),
                'group'                         => $this->get_group(),
                'settings_products_count_key'   => self::LC_S_PRODUCST_COUNTS_KEY,
                'settings_products_key'         => self::LC_S_PRODUCTS_KEY,
                'settings_shipping_address_key' => self::LC_S_SHIPPING_ADDRESS_KEY,
                'settings_total_value_key'      => self::LC_S_TOTAL_VALUE_KEY,
                'settings_last_order_key'       => self::LC_S_LAST_ORDER_KEY,
                'user_email'                    => $this->get_user_property('user_email'),
            )
        );
    }

    /**
     * Update custom data settings by given key and value.
     *
     * @param string $key
     * @param string $value
     * @return boolean
     */
    private function update_custom_data_settings( $key, $value ) {
        $current_settings       = $this->get_custom_data_settings();
        $current_settings[$key] = (boolean) $value;

        return update_option( self::LC_SETTINGS, $current_settings );
    }

    /**
     * Checks if license is active.
     * @param string $license_id
     * @return boolean
     */
    private function is_license_active( $license_id ) {
        $res = wp_remote_get( str_replace( '%licenseId%', $license_id, self::LC_ACOUNT_DETAILS_URL_PATTERN ), array( 'timeout' => 30 ) );

        if ( $res instanceof WP_Error ) {
            $this->force_template = 'connection-error-template.php';

            return false;
        }

        $body = json_decode( $res['body'], true );

        if ( array_key_exists( 'license_active', $body ) && true === $body['license_active'] ) {
            return true;
        }

        return false;
    }

    /**
     * Reset user license id and group.
     */
    private function reset_settings() {
        delete_option( self::LC_GROUP_KEY );
        delete_option( self::LC_LICENSE_ID );
        delete_option( self::LC_SETTINGS );
    }

    /**
     * Update user group.
     *
     * @param integer $group_id
     * @return boolean
     */
    private function update_group( $group_id ) {
        // valid group
        if ( is_int( $group_id ) && $group_id >= 0) {
            return update_option( self::LC_GROUP_KEY, $group_id );
        }

        return false;
    }

    /**
     * Set up user license id and turn on all custom data settings.
     *
     * @param integer $license_id
     * @return boolean
     */
    private function set_up_license_id( $license_id ) {
        // valid licenseId
        if (is_int( $license_id ) && $license_id >= 0) {
            // Turn on all custom data settings.
            $default_settings = array(
                self::LC_S_PRODUCST_COUNTS_KEY  => 1,
                self::LC_S_PRODUCTS_KEY         => 1,
                self::LC_S_SHIPPING_ADDRESS_KEY => 1,
                self::LC_S_TOTAL_VALUE_KEY      => 1,
                self::LC_S_LAST_ORDER_KEY       => 1,
            );

            update_option( self::LC_SETTINGS, $default_settings );
            // Set up license ID
            update_option( self::LC_LICENSE_ID, $license_id );
            return true;
        }

        return false;
    }

    /**
     * Returns user property.
     *
     * @param string $property_name
     * @param string $default
     * @return string
     */
    private function get_user_property( $property_name, $default = null ) {
        if ( null === $this->user_info ) {
            $this->user_info = wp_get_current_user();
        }

        return ( null !== ($res = $this->user_info->get( $property_name ) ) ) ? $res : $default;
    }

    /**
     * Set up plugin version.
     */
    private function set_up_plugin_version() {
        if ( ! function_exists( 'get_plugins' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }

        $plugin_dir = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) . '/..' ) );
        $this->plugin_version = $plugin_dir['livechat.php']['Version'];
    }

    /**
     * Set up plugin url.
     */
    private function set_up_plugin_url() {
        $this->plugin_url = WP_PLUGIN_URL . '/woocommerce-livechat/src';
    }
}
