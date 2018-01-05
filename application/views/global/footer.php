</div>
<div class="copyright">
    Made with <span class="am-icon-heart"></span> by 泉五社联 X <a href="https://www.qz5z.tech" target="_blank">泉五电研</a>
    <br>
    &copy; 2017- 换届实行委员会. All rights reserved.
</div>

</div>

<!--<a href="#" class="am-icon-btn am-icon-th-list am-show-sm-only admin-menu" data-am-offcanvas="{target: '#admin-offcanvas'}"></a>-->

<!--[if lt IE 9]>
<script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/modernizr/2.8.3/modernizr.min.js"></script>
<script src="assets/js/amazeui.ie8polyfill.min.js"></script>
<![endif]-->

<!--[if (gte IE 9)|!(IE)]><!-->
<script src="assets/js/jquery-3.2.1.min.js"></script>
<!--<![endif]-->
<script src="assets/js/amazeui.js"></script>
<script src="assets/js/global.js"></script>
<?php
    for ($i=1;$i<=count($add_js);$i++) {
        echo '<script src="assets/js/'. $add_js[$i-1] . '"></script>' . PHP_EOL;
    }
?>
<!--
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-78734040-11"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'UA-78734040-11');
</script>
-->
</body>

</html>