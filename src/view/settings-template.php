<?php
/**
 * Settings template. Allows admin to manage LiveChat window settings.
 *
 * @category Admin pages
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div id="woocommerce-livechat-container">
    <div class="login-box-header">
        <span class="logo-woo"></span>
        <span class="logo"></span>
    </div>
    <h2>LiveChat is installed properly</h2>
    <div class="installation-desc">
        Sign in to LiveChat and start chatting with your customers!
    </div>
    <p class="lc-submit multi-buttons">
        <a href="https://my.livechatinc.com/" target="_blank">
            <button class="button"><span>Launch web app</span></button>
        </a>
        <a href="http://www.livechatinc.com/product" target="_blank">
            <button class="button green"><span>Download desktop app</span></button>
        </a>
    </p>
    <h2>Settings</h2>
    <div class="installation-desc">
        Custom variables configuration. Those parameters will be sent as a <a class="a-important" href="http://www.livechatinc.com/kb/custom-variables-configuration/" target="_blank">Custom Parameters</a>.
    </div>
    <div id="settings">
        <div>
            <span class="title">Products details</span>
            <div class="onoffswitch">
                <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="<?php echo $settings_products_key ?>" <?php echo (isset($settings[$settings_products_key]) && $settings[$settings_products_key]) ? 'checked': '' ?>>
                <label class="onoffswitch-label" for="<?php echo $settings_products_key ?>">
                    <span class="onoffswitch-inner"></span>
                    <span class="onoffswitch-switch"></span>
                </label>
            </div>
        </div>
        <div>
            <span class="title">Products count</span>
            <div class="onoffswitch">
                <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="<?php echo $settings_products_count_key ?>" <?php echo (isset($settings[$settings_products_count_key]) && $settings[$settings_products_count_key]) ? 'checked': '' ?>>
                <label class="onoffswitch-label" for="<?php echo $settings_products_count_key ?>">
                    <span class="onoffswitch-inner"></span>
                    <span class="onoffswitch-switch"></span>
                </label>
            </div>
        </div>
        <div>
            <span class="title">Total value</span>
            <div class="onoffswitch">
                <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="<?php echo $settings_total_value_key ?>" <?php echo (isset($settings[$settings_total_value_key]) && $settings[$settings_total_value_key]) ? 'checked': '' ?>>
                <label class="onoffswitch-label" for="<?php echo $settings_total_value_key ?>">
                    <span class="onoffswitch-inner"></span>
                    <span class="onoffswitch-switch"></span>
                </label>
            </div>
        </div>
        <div>
            <span class="title">Shipping address</span>
            <div class="onoffswitch">
                <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="<?php echo $settings_shipping_address_key ?>" <?php echo (isset($settings[$settings_shipping_address_key]) && $settings[$settings_shipping_address_key]) ? 'checked': '' ?>>
                <label class="onoffswitch-label" for="<?php echo $settings_shipping_address_key ?>">
                    <span class="onoffswitch-inner"></span>
                    <span class="onoffswitch-switch"></span>
                </label>
            </div>
        </div>
        <div>
            <span class="title">Last order info</span>
            <div class="onoffswitch">
                <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="<?php echo $settings_last_order_key ?>" <?php echo (isset($settings[$settings_last_order_key]) && $settings[$settings_last_order_key]) ? 'checked': '' ?>>
                <label class="onoffswitch-label" for="<?php echo $settings_last_order_key ?>">
                    <span class="onoffswitch-inner"></span>
                    <span class="onoffswitch-switch"></span>
                </label>
            </div>
        </div>
        <div>
            <span class="title" title="Click for more information..."><a href="http://www.livechatinc.com/kb/dividing-live-chat-by-group/" target="_blank" class="help">Group</a></span>
            <div class="onoffswitch">
                <input type="text" pattern="[0-9]{,3}" id="customDataGroup" placeholder="0" value="<?php echo $group ?>"/>
            </div>
        </div>
    </div>
    <div class="links">
        Your current LiveChat account is: <?php echo $user_email ?>. <a href="?page=wc-livechat&amp;reset=1" id="resetAccount">Change account.</a>
    </div>
</div>
