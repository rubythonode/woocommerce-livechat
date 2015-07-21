<?php
/**
 * Inactive license template.
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
    <h1>Your LiveChat account has expired</h1>
    <div class="installation-desc">
        <p class="intro">
            Please <a href="https://my.livechatinc.com/subscription" target="_blank" class="a-important">log in to your LiveChat and subscribe</a> to continue chatting with your customers. Your LiveChat account email is: <?php echo $user_email ?>.
        </p>
        <p class="lc-submit multi-buttons">
            <a href="https://my.livechatinc.com/subscription" target="_blank">
                <button class="button green"><span>GO TO LiveChat</span></button>
            </a>
        </p>
    </div>
</div>
