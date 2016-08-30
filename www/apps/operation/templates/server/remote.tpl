<link href="http://{$IMG_DOMAIN}/css/plugins/select2/select2.css" rel="stylesheet" type="text/css" />
<link href="http://{$IMG_DOMAIN}/css/plugins/select2/select2-bootstrap.css" rel="stylesheet" type="text/css" />
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
    /* select2 style */

    .select2-container--bootstrap .select2-selection--single {
        background: #FFF;
        border: 1px solid #ccc;
        height: 50px;
    }

    .select2-container--bootstrap .select2-selection--single .select2-selection__rendered {
        line-height: 40px;
        color: #404040;
        text-align: left;
        font-size: 20px;
    }

    .select2-container--bootstrap .select2-selection--single .select2-selection__arrow {
        height: 26px;
        position: absolute;
        right: 1px;
        top: 10px;
        width: 20px;
    }
</style>
<div>
    <div class="row">
        <div class="col-xs-12 col-md-3 lh-row-head">
            <select class="form-control input-lg ipt-ip" tabindex="3">
                <option value=""></option>
                <optgroup label="按标签选择">
                    {foreach $tag_list as $v}
                    <option value="tag-{$v.id}">{$v.name}{if $v.type==$TAG_SERVICE}:{$v.port}{/if}</option>
                    {/foreach}
                </optgroup>
                <optgroup label="按IP选择">
                    {foreach $ip_list as $v}
                    <option value="ip-{$v}">{$v}</option>
                    {/foreach}
                    <option value="ip-all">All of valid IP</option>
                </optgroup>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-1 lh-row-head">
            <select class="form-control ipt-type input-lg " tabindex="1">
                <option value="1">Shell命令</option>
                <option value="2" selected="selected">Playbook</option>
                <option value="3">Shell脚本</option>
            </select>
        </div>
        <div class="col-xs-11 col-md-3 lh-row-head">
            <input type="text" placeholder="Command Line" tabindex="2" class="form-control input-lg ipt-command" style="display:none;" autocapitalize="off">
            <select tabindex="2" class="form-control input-lg ipt-command"></select>
        </div>
        <div class="col-xs-11 col-md-2 lh-row-head" style="padding-left: 30px;">
            <input type="text" placeholder="Script Parameter" tabindex="3" class="form-control input-lg ipt-parameter" style="display:none;">
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6 col-md-2 lh-row-head">
            <button type="button" class="btn btn-primary btn-lg btn-send btn-block">Run</button>
        </div>
        <div class="col-xs-6 col-md-1 lh-row-head checkbox sudo">
            <label style="display: inline;font-size:20px;">
                <input type="checkbox" class="cbx-sudo" value="1"> Sudo
            </label>
        </div>
        <div class="col-xs-6 col-md-2 lh-row-head">
            <button type="button" class="btn btn-success btn-lg btn-addtask btn-block">Add a Task</button>
        </div>
    </div>
</div>

<div class="well var-box">
    <h4 style="line-height:50px;margin:0;">Playbook 变量赋值</h4>
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
            <span style="color:#fff;"><a id="reply_host_num" href="javascript:void(0);">0</a> Host(s) Response,
                  Succ: <a id="host_num_succ" href="javascript:void(0);">0</a> / Failed: <a id="host_num_failed" href="javascript:void(0);">0</a>.</span>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <div class="box-body"></div>
</div>
</div>

<div class="modal fade" tabindex="-1" id="_j_task_edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h3>编辑任务信息</h3>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_id">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">任务名称</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="" class="form-control valid-data w-40p f-inline ipt-name">
                            <span class="help-block f-inline">简短明确的任务名称</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">备注</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="" class="form-control valid-data w-40p f-inline ipt-memo">
                            <span class="help-block f-inline">详细描述任务的属性</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">取消</button>
                <button type="button" class="btn btn-primary btn-save-task">保存</button>
            </div>
        </div>
    </div>
</div>

<i id="logininfo" data-username="{$logininfo.username}" style="display:none;"></i>
<script type="text/javascript" src="http://{$IMG_DOMAIN}/js/plugins/select2/select2.js"></script>
<script type="text/javascript" src="http://{$IMG_DOMAIN}/js/plugins/jquery.autosize.input.js"></script>
<script type="text/javascript" src="http://{$IMG_DOMAIN}/js/server_remote.js"></script>
<script>
    $(function() {
        //$('#loading').hide();
        var pb_list = {$play_json},
            script_list = {$script_json};
        pb_list.unshift('');
        script_list.unshift('')
        var script_type = ['', '', 'palybook', 'script'];
        var select2_ip = $('.ipt-ip').select2({
            theme: 'bootstrap',
            placeholder: '选择目标服务器(组)'
        });

        select2_ip.on('change', function(e) {
            auto_fill_form();
        });

        //解析变量
        function parse_vas(type, path) {
            if (path != '') {
                if (type == 2) { //pb文件检测
                    $('.btn-send').attr('disabled', true);
                    $.get('/rest/ansible/parameter/' + path, {
                        data_style: 'parsevars'
                    }, function(d) {
                        close_var_form();
                        if (d.errno == 0) { //检测到pb文件是否需要变量填充
                            if (d.data.length) {
                                fill_vars_form(d.data);
                                auto_fill_form();
                            }
                            $('.btn-send').removeAttr('disabled');
                        }
                    }, 'json');
                } else if (type == 3) { //sh文件检测
                    $('.btn-send').attr('disabled', true);
                    path = path.slice(-3) == '.sh' ? path : path + '.sh';
                    $.get('/rest/ansible/filexist/' + encodeURI('script|' + path), {}, function(d) {
                        if (d.errno == 0) {
                            $('.btn-send').removeAttr('disabled');
                        }
                    }, 'json');
                }
            }
        }

        $('select.ipt-command').hide();
        var select2_command = null;

        $('.ipt-type').change(function() {
            var type = $(this).val();
            $('.btn-send').removeAttr('disabled');
            select2_command && select2_command.data('select2') && $('select.ipt-command').select2('destroy').empty();
            $('.ipt-command').hide();
            $('.sudo').hide();
            $('.ipt-parameter').hide();
            close_var_form();
            if (type == 1) {
                $('.ipt-command:input[type="text"]').val('').show();
                $('.sudo').show();
            } else if (type == 2) {
                pb_list.unshift('')
                select2_command = $('select.ipt-command').select2({
                    theme: 'bootstrap',
                    placeholder: '选择Playbook',
                    data: pb_list
                });
                select2_command.on('change', function(e) {
                    parse_vas($('.ipt-type').val(), $(this).val());
                });
            } else if (type == 3) {
                select2_command = $('select.ipt-command').select2({
                    theme: 'bootstrap',
                    placeholder: '选择脚本',
                    data: script_list
                });
                $('.ipt-parameter').show();
                $('.sudo').show();
            }
        });

        $('.ipt-command:eq(0)').autosizeInput();

        //$('.ipt-type').trigger('change');
        $('.sudo').hide();
        //初始化playbook操作面板
        pb_list.unshift('')
        select2_command = $('select.ipt-command').select2({
            theme: 'bootstrap',
            placeholder: '选择Playbook',
            data: pb_list
        });
        select2_command.on('change', function(e) {
            parse_vas($('.ipt-type').val(), $(this).val());
        });

        $('.btn-send').click(function() {
            var self = $(this),
                ip = $('.ipt-ip').val(),
                command = $('.ipt-command:text').is(':visible') ? $(':text.ipt-command').val() : $('select.ipt-command').val(),
                type = $('.ipt-type').val();
            if (ip == '' || command == '' || type == '') {
                alert('请将内容填写完整!');
                return false;
            }
            if (type==3 && $.trim($('.ipt-parameter').val())!='') command += ' '+$.trim($('.ipt-parameter').val());
            var post_data = {
                'method': 'POST',
                'after_style': 'default',
                'post_style': 'default',
                'update': {
                    ip: ip,
                    command: command,
                    type: type,
                    sudo: $('.cbx-sudo').is(':checked') ? 1 : 0,
                    vars: {}
                }
            };
            if (type == 2) { //pb附加参数处理
                var rzt = checek_var_form();
                if (rzt.rc != 0) return;
                else post_data.update.vars = rzt.vars;
            }
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

        $('.btn-addtask').click(function() {
            var self = $(this),
                ip = $('.ipt-ip').val(),
                command = $('.ipt-command:text').is(':visible') ? $(':text.ipt-command').val() : $('select.ipt-command').val(),
                type = $('.ipt-type').val();
            if (ip == '' || command == '' || type == '') {
                alert('请先完整的运行一个任务!');
                return false;
            }
            if (type==3 && $.trim($('.ipt-parameter').val())!='') command += ' '+$.trim($('.ipt-parameter').val());
            var post_data = {
                'method': 'POST',
                'after_style': 'default',
                'update': {
                    ip: ip,
                    command: command,
                    type: type,
                    sudo: $('.cbx-sudo').is(':checked') ? 1 : 0,
                    vars: {}
                }
            };
            if (type == 2) { //pb附加参数处理
                var rzt = checek_var_form();
                if (rzt.rc != 0) return;
                else post_data.update.vars = rzt.vars;
            }
            $('#edit_id').data('post_data', post_data);
            $('#_j_task_edit .form-horizontal .valid-data:input').each(function() {
                $(this).val('');
            });
            $('#_j_task_edit').modal('show');
        });

        $('.btn-save-task').click(function() {
            self = $(this);
            var post_data = $('#edit_id').data('post_data');
            if (post_data) {
                self.attr('disabled');
                $('#edit_id').removeData('post_data');
                post_data.update.name = $('.ipt-name').val();
                post_data.update.memo = $('.ipt-memo').val();
                $.post('/rest/server/remotetask/', post_data, function(d) {
                    if (d.errno == 0) {
                        alert('任务保存成功，稍后请移步到『任务授权』中进行授权。');
                        $('#_j_task_edit').modal('hide');
                    } else {
                        alert(d.error);
                    }
                    self.removeAttr('disabled');
                }, 'json');
            } else {
                alert('系统错误，请刷新页面后重试');
            }
        });

    });
</script>
