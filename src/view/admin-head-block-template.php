<?php
/**
 * Admin head block template. Set up base JS URL's (for ajax requests) includes CSS, fonts and LiveChat script.
 * @category Admin pages
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL ?>/woocommerce-livechat/src/css/style.css">
<link href="//fonts.googleapis.com/css?family=Lato:300" rel="stylesheet" type="text/css">
<script type="text/javascript">
    var WcLcUrls = {
        checkLicense: '<?php echo $check_license_url ?>',
        setSettings: '<?php echo $set_settings_url ?>',
        newLicense: '<?php echo $new_license_url ?>'
    };
</script>
<script type="text/javascript">
var __lc = {};
__lc.license = 1520;
__lc.group = 77;
__lc.visitor = {
        name: "<?php echo $username ?>",
        email: "<?php echo $user_email ?>"
};

__lc.params = [{name: 'integration', value: 'WooCommerce'}];
(function() {
var lc = document.createElement('script'); lc.type = 'text/javascript'; lc.async = true;
lc.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'cdn.livechatinc.com/staging/tracking.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(lc, s);
})();
</script>
