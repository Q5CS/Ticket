<ol class="am-breadcrumb">
    <li><a class="am-active am-icon-home">首页</a></li>
</ol>

<div class="eidi">
    <img src="https://2019-cdn.qz5z.ren/assets/img/eidi.png">
</div>

<?php
if ($authed) {
    echo "
    <div class=\"am-alert am-alert-success am-alert-icon-sm\" data-am-alert>
        <button type=\"button\" class=\"am-close\">&times;</button>
        <span class=\"am-icon-check-circle\"></span>
        <p>您已通过实名认证，可提前 $delta_days 天预约！</p>
    </div>
    ";
} else {
    echo '
    <div class="am-alert am-alert-success am-alert-icon-sm" data-am-alert>
        <button type="button" class="am-close">&times;</button>
        <span class="am-icon-exclamation-circle"></span>
        <p>在校学生可 <a href="/main/authenticate" type="button" class="am-btn am-btn-warning am-btn-xs">实名认证</a> ，在线预约快人一步！</p>
    </div>
    ';
}
?>

<div class="fly-content">
    <p class="am-text-center">
        <?php
        echo "<b>Welcome, ".$user['username']." !</b>".PHP_EOL;
        ?>
    </p>

    <p class="am-text-center">
        <b>您的预约时间</b> <br>
        <?php echo $startTime ?> <br class="fbr">至<br class="fbr">
        <?php echo $endTime ?>
    </p>

    <div class="am-g doc-am-g" style="margin-bottom:0.5rem">
        <div class="am-u-lg-12" style="margin-bottom:0.5rem">
            <p class="am-text-center"><b>今日门票剩余</b></p>
            <div data-am-progressbar="{percentage:'<?php echo $remainPercent; ?>', textInner: true}"></div>
        </div>
    </div>

    <hr>

    <?=$notice?>

    <div class="am-g doc-am-g" style="margin-bottom:0.5rem">
        <div class="am-u-lg-6" style="margin-bottom:0.5rem">
            <a href="/main/book" type="button" class="am-btn am-btn-primary am-btn-block">立即预约</a>
        </div>
        <div class="am-u-lg-6">
            <a href="/main/myTicket" type="button" class="am-btn am-btn-primary am-btn-block">我的邀请函</a>
        </div>
    </div>

</div>