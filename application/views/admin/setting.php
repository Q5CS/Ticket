<ol class="am-breadcrumb">
    <li><a class="am-icon-home">管理面板</a></li>
    <li><a class="am-active">系统设置</a></li>
</ol>

<div class="fly-content">
    <h2 class="am-text-center">系统设置</h2>
    <div class="am-form-group">
        <label class="am-checkbox am-danger">
            <input id="cbox-no-reg" type="checkbox" data-am-ucheck <?php
            if ($noReg) {
                echo"checked";
            }
            ?>
            > 关闭注册
        </label>
        <div class="am-input-group">
            <span class="am-input-group-label">总票数（不含内部）</span>
            <input id="input-alltnum" type="number" class="am-form-field" value="<?=$alltnum?>"
                required>
        </div>
        <div class="am-input-group">
            <span class="am-input-group-label">领取时间（未认证）</span>
            <input id="input-starttime" type="text" class="am-form-field" required>
        </div>
        <div class="am-input-group">
            <span class="am-input-group-label">领取时间（已认证）</span>
            <input id="input-starttime-stu" type="text" class="am-form-field" required>
        </div>
        <div class="am-input-group">
            <span class="am-input-group-label">结束时间（未认证）</span>
            <input id="input-finaltime" type="text" class="am-form-field" required>
        </div>
        <div class="am-input-group">
            <span class="am-input-group-label">结束时间（已认证）</span>
            <input id="input-finaltime-stu" type="text" class="am-form-field" required>
        </div>
        <div class="am-input-group">
            <span class="am-input-group-label">最多领取（未认证）</span>
            <input id="input-pertnum" type="number" class="am-form-field" value="<?=$pertnum?>"
                required>
        </div>
        <div class="am-input-group">
            <span class="am-input-group-label">最多领取（已认证）</span>
            <input id="input-pertnum-stu" type="number" class="am-form-field" value="<?=$pertnum_stu?>"
                required>
        </div>
        <h4 style="text-align:center">首页通知内容</h4>
        <div id="editor">
            <?=$notice?>
        </div>
    </div>
    <a id="btn-save" type="button" class="am-btn am-btn-primary am-btn-block">保存</a>
</div>

<script>
    var starttime = "<?=$starttime?>";
    var starttime_stu = "<?=$starttime_stu?>";
    var finaltime = "<?=$finaltime?>";
    var finaltime_stu = "<?=$finaltime_stu?>";
</script>
<script src="https://cdn.jsdelivr.net/npm/wangeditor@3.1.1/release/wangEditor.min.js" integrity="sha256-dMpIg80Q6UQtegABhQBabLoWlHinsb+bPK7nzq8Jk6c="
    crossorigin="anonymous"></script>