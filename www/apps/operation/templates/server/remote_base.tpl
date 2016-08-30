<style type="text/css">
    .alert-heading {
        color: #999 !important;
        padding-bottom: 15px;
    }

    .h-40 {
        height: 40px;
    }

    .pb-20 {
        padding-bottom: 20px;
    }

    .lh-row-head {
        height: 60px;
    }

    .var-box {
        background-color: #d9edf7;
        margin-top: 10px;
        display: none;
    }

    .alert-default {
        background-color: #f5f5f5;
    }

    .alert-heading {
        padding-bottom: 0;
    }

    .wellx {
        margin: 10px 0;
    }

    .wellx p {
        font-family: menlo;
    }

    .task-name {
        color: #000;
        background-color: #fff;
        line-height: 30px;
        padding-left: 3px;
    }

    .output {
        color: #000;
        word-wrap: break-word;
    }

    .sudo {
        padding: 0 20px;
        display: none;
    }

    .layui-layer {
        background-color: #fff;
    }

    #loading {
        display: none;
    }
</style>
<div>

    <div class="row">
        <div class="col-xs-12 col-md-3 lh-row-head">
            <select class="form-control ipt-task input-lg" tabindex="1">
                <option value="">请选择任务名称</option>
                {foreach $task as $t}
                <option value="{$t.id}">{$t.name}</option>
                {/foreach}
            </select>
        </div>
        <div class="col-xs-12 col-md-2 lh-row-head">
            <button type="button" class="btn btn-primary btn-lg btn-send btn-block">Run</button>
        </div>
    </div>

    <div class="well var-box">
        <h4 style="line-height:50px;margin:0;">变量赋值</h4>
        <div id="vars"></div>
    </div>
    <div id="loading" class="box box-warning box-solid">
        <div class="box-header">
            <h3 class="box-title">处理中...</h3>
        </div>
        <div class="box-body">
            请耐心等待，不要关闭页面，详细结果稍后会显示在此处。
        </div>
        <div class="overlay">
            <i class="fa fa-refresh fa-spin"></i>
        </div>
    </div>

    <div class="result"></div>

    <div class="box box-warning box-solid fade" id="item">
        <div class="box-header with-border">
            <h3 class="box-title"><span class="hidden-xs">Task Report</span></h3>
            <div class="box-tools pull-right">
                <span style="color:#fff;"><b id="reply_host_num">0</b> Host(s) Response,
                  Succ: <b id="host_num_succ">0</b> / Failed: <b id="host_num_failed">0</b>.</span>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <div class="box-body"></div>
    </div>
</div>
<i id="logininfo" data-username="{$logininfo.username}" style="display:none;"></i>
<script type="text/javascript" src="http://{$IMG_DOMAIN}/js/server_remote.js"></script>
<script>
    $(function() {
        var fill_pb = true;
        $('.ipt-task').change(function() {
            var self = $(this),task_id = self.val();
            if (task_id != '') {
                $('.btn-send').attr('disabled', true);
                $.get('/rest/ansible/parameter/' + task_id, {
                    'data_style': 'basevars'
                }, function(d) {
                    close_var_form();
                    if (d.errno == 0) { //检测到pb文件是否需要变量填充
                        if (d.data.length) {
                            if(d.data.length ==1 && d.data[0] == '__parameter__'){
                                d.data[0] = 'parameter';
                                fill_pb = false;
                            } else {
                                fill_pb = true;
                            }
                            fill_vars_form(d.data);
                            auto_fill_form();
                        }
                        $('.btn-send').removeAttr('disabled');
                    }
                }, 'json');
            }
        });

        $('.btn-send').click(function() {
            var self = $(this),
                task_id = $('.ipt-task').val();
            if (task_id == '') {
                alert('请选择一个有效的任务!');
                return false;
            }
            var update = { 'task_id': task_id }, rzt = checek_var_form();
            if (rzt.rc == 0 ) {
                fill_pb ? update.vars = rzt.vars : update.parameter = rzt.vars.parameter;
            }
            var post_data = {
                'method': 'POST',
                'after_style': 'base',
                'post_style': 'base',
                'update': update
            };

            self.text('Processing ...').attr('disabled', true);
            $('.result').empty();
            $('#loading').show();

            var aj = $.ajax({
                url: '/rest/ansible/cmd/',
                data: post_data,
                type: 'post',
                cache: false,
                dataType: 'json',
                success: ServerRemote.parseResult,
                error: ServerRemote.parseError,
            });
        });
    });
</script>
