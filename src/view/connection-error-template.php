<?php
/**
 * Connection error template.
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
    <h1>Something went wrong...</h1>
    <div class="installation-desc">
        <p class="intro">
            Please <a class="a-important" href="#" onClick="window.location.reload()">reload this page</a> or contact our support team.
        </p>
    </div>
</div>
<script type="text/javascript">
    setTimeout(function() { window.location.reload() }, 5000);
</script>
