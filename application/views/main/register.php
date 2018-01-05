<script src='https://www.recaptcha.net/recaptcha/api.js'></script>
<div id='recaptcha' class="g-recaptcha" data-sitekey="<?=$retaptcha_sitekey?>"
    data-callback="postAuth" data-size="invisible"></div>

<ol class="am-breadcrumb">
    <li><a href="/" class="am-active am-icon-home">首页</a></li>
    <li><a class="am-active">注册</a></li>
</ol>

<div class="fly-content">
    <h2 class="am-text-center">注册账号</h2>
    <div class="am-g">
        <form id="form-reg" class="am-form" onsubmit="return false">
            <fieldset>
                <div class="am-u-lg-12">
                    <div class="am-form-group">
                        <div class="am-input-group">
                            <span class="am-input-group-label"><i class="am-icon-at am-icon-fw"></i></span>
                            <input id="input-email" type="email" class="am-form-field" placeholder="邮箱"
                                data-validation-message="请输入正确的邮箱地址" required>
                        </div>
                    </div>
                </div>
                <div class="am-u-lg-12">
                    <div class="am-form-group">
                        <div class="am-input-group">
                            <span class="am-input-group-label"><i class="am-icon-phone am-icon-fw"></i></span>
                            <input id="input-phone" type="text" class="am-form-field js-pattern-mobile" placeholder="手机号"
                                data-validation-message="请输入11位大陆手机号" required>
                        </div>
                    </div>
                </div>
                <div class="am-u-lg-12">
                    <div class="am-form-group">
                        <div class="am-input-group">
                            <span class="am-input-group-label"><i class="am-icon-shield am-icon-fw"></i></span>
                            <input id="input-verify" type="text" class="am-form-field" placeholder="6 位验证码"
                                data-validation-message="请输入 6 位验证码" maxlength="6" required>
                            <span class="am-input-group-btn">
                                <button id="btn-send-verify" class="am-btn am-btn-default" type="button">发送验证码</button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="am-u-lg-12">
                    <div class="am-form-group">
                        <div class="am-input-group">
                            <span class="am-input-group-label"><i class="am-icon-user am-icon-fw"></i></span>
                            <input id="input-username" type="text" class="am-form-field" minlength="3" maxlength="16"
                                placeholder="用户名 (3-16位字符)" required>
                        </div>
                    </div>
                </div>
                <div class="am-u-lg-12">
                    <div class="am-form-group">
                        <div class="am-input-group">
                            <span class="am-input-group-label"><i class="am-icon-key am-icon-fw"></i></span>
                            <input id="input-pwd" type="password" class="am-form-field" minlength="6" maxlength="20"
                                placeholder="密码 (6-20位字符)" required>
                        </div>
                    </div>
                </div>
                <div class="am-u-lg-12">
                    <div class="am-form-group">
                        <div class="am-input-group">
                            <span class="am-input-group-label"><i class="am-icon-key am-icon-fw"></i></span>
                            <input id="input-pwd-repeat" type="password" class="am-form-field" minlength="3" maxlength="20"
                                data-equal-to="#input-pwd" placeholder="重复密码 (同上)" data-validation-message="两次输入的密码不一致"
                                required>
                        </div>
                    </div>
                </div>
            </fieldset>
            <button id="btn-reg" type="submit" class="am-btn am-btn-primary am-btn-block">立即注册</button>
        </form>
    </div>

</div>