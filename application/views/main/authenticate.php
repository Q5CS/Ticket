<ol class="am-breadcrumb">
    <li><a href="/main/profile" class="am-icon-home">个人中心</a></li>
    <li><a href="/main/authenticate" class="am-active">实名认证</a></li>
</ol>

<div class="fly-content">
    <?php
    if (!$authed) {
        echo '
        <h2 class="am-text-center">认证须知</h2>
    
        <div class="login-info-form am-center" style="max-width:640px;margin-bottom:1rem">
            <div class="am-alert am-alert-warning" style="margin:0 0.625rem 1em 0.625rem">
            <p><small>
                1. 我们将通过校园网记录您的姓名、用户编码、年段、班级以及座位号以进行身份验证；
                <br>
                2. 我们不会以任何形式储存其他信息；
                <br>
                3. 一旦认证成功，无法修改或撤销，请确保使用本人帐号进行认证。
            </small></p>
            </div>

            <div class="login-info-form-content">
                <div class="am-g">
                    <!--<div class="am-u-lg-12" style="margin-bottom:10px;width:100%">
                        <select id="select-njid" data-am-selected="{btnWidth: \'100%\'}"><option value="-1">请选择年段</option><option value="31">初一年</option><option value="26">初二年</option><option value="23">初三年</option><option value="30">高一年</option><option value="25">高二年</option><option value="21">高三年</option><option value="32">国一年</option><option value="28">国二年</option><option value="24">国三年</option></select>
                    </div>
                    <div class="am-u-lg-12">
                        <div class="am-input-group">
                            <span class="am-input-group-label"><i class="am-icon-user am-icon-fw"></i></span>
                            <input id="input-name" type="text" class="am-form-field" placeholder="姓名 / 手机号">
                        </div>
                    </div>
                    <div class="am-u-lg-12">
                        <div class="am-input-group">
                            <span class="am-input-group-label"><i class="am-icon-key am-icon-fw"></i></span>
                            <input id="input-pwd" type="password" class="am-form-field" placeholder="五中官网的密码">
                        </div>
                    </div>
                </div>-->
                <div class="am-g am-margin-top-lg">
                    <div class="am-u-md-6 am-u-lg-6"><a class="login-link" data-am-modal="{target: \'#help-modal\'}" style="cursor:pointer">认证失败？</a></div>
                        <div class="am-u-md-6 am-u-lg-6">
                            <a href="'.$auth_link.'" type="button" class="am-btn am-btn-primary am-btn-block">立即认证</a>
                            <!--<button id="btn-auth" type="button" class="am-btn am-btn-primary am-btn-block">立即认证</button>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        ';
    } else {
        echo '
        <div class="am-alert am-alert-success am-alert-icon-lg" data-am-alert>
            <span class="am-icon-check-circle"></span>
            <div class="am-alert-bd">
                <h3>您已完成实名认证</h3>
                <p>认证信息：'.$user['name'].'</p>
            </div>
        </div>
        <a href="/" type="button" class="am-btn am-btn-primary am-btn-block">返回首页</a>
        ';
    }
    ?>
</div>

<div class="am-modal am-modal-no-btn" tabindex="-1" id="help-modal">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">无法登录？
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div class="am-modal-bd">
            1. 请再次确认年段、姓名和密码是否正确；
            <br>
            2. 该账号为五中官网的账号，不是什么其他的账号...
            <br>
            3. 如果还是有问题，请联系社联官Q。
        </div>
    </div>
</div>