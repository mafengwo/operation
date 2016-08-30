<link href="http://{$IMG_DOMAIN}/css/plugins/select2/select2.css" rel="stylesheet" type="text/css" />
<link href="http://{$IMG_DOMAIN}/css/plugins/select2/select2-bootstrap.css" rel="stylesheet" type="text/css" />
<style type="text/css">
    .modal-dialog {
        width: 60%;
    }

    .h-50 {
        height: 50px;
    }

    .lh-30 {
        line-height: 30px;
    }

    .pd-5 {
        padding: 5px;
    }

    .tag-admin {
        cursor: pointer;
    }
</style>
<div>
    <div class="form-group">
        <a class="btn btn-default" href="/server/remote/index"><span class="glyphicon glyphicon-menu-left">返回</span></a>
        <!-- <a class="btn btn-default btn-new" href="javascript:void(0);"><span class="glyphicon glyphicon-plus">新增</span></a> -->
    </div>
    <section class="content wp-3">
        <div class="row">
            <div class="col-md-12 col-xs-12 wp-3">
                <div class="box">
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th>任务名称</th>
                                    <th>备注说明</th>
                                    <th>任务内容</th>
                                    <th style="width: 30%">授权用户</th>
                                    <th>任务状态</th>
                                    <th>操作</th>
                                </tr>
                                {foreach $list as $l} {assign var="task_id" value=$l.id}
                                <tr>
                                    <td>{$l.name}</td>
                                    <td>{$l.memo}</td>
                                    <td>
                                        <p>
                                            目标: <span class="label label-primary">{$l.ip}</span> 参数: <span class="label label-primary">{$l.command}</span> 类型: <span class="label label-primary">{$types[$l.type]}</span>
                                        </p>
                                        {if $l.vars!=''}
                                        <p>扩展参数: <span class="label bg-gray-active color-palette" style="color:#fff;">{$l.vars}</span></p>{/if}
                                    </td>
                                    <td>
                                        {if is_array($privacy[$task_id])} {foreach $privacy[$task_id] as $p}
                                        <span class="label label-primary tag-admin" data-id="{$p.id}" title="双击删除">{$users[$p.admin_uid]['username']}</span> {/foreach} {/if}
                                        <a class="btn btn-default btn-xs btn-adduser" href="javascript:void(0);" data-id="{$l.id}" title="增加授权用户"><i class="fa fa-user-plus"></i></a>
                                    </td>
                                    <td>
                                        {if $l.status}启用{else}停用{/if}
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);" data-id="{$task_id}" class="btn btn-default icn-only btn-edit" title="编辑"><span class="glyphicon glyphicon-edit"></span></a>
                                    </td>
                                </tr>
                                {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <div class="modal fade" tabindex="-1" id="_j_add_privacy">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h3>增加任务权限</h3>
                </div>
                <div class="modal-body">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="col-md-4 control-label">给【<span id="text_privacy"></span>】增加授权</label>
                            <div class="col-md-8">
                                <select class="form-control" id="admin_uid" style="width:90%">
                                    <option value=""></option>
                                    {foreach $users as $u}
                                    <option value="{$u.id}">{$u.username}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">取消</button>
                    <button type="button" class="btn btn-primary btn-save-privacy">保存</button>
                </div>
            </div>
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
                                <input type="text" placeholder="" class="form-control valid-data w-40p f-inline" data-field="name">
                                <span class="help-block f-inline">简短明确的任务名称</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">备注</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="" class="form-control valid-data w-40p f-inline" data-field="memo">
                                <span class="help-block f-inline">详细描述任务的属性</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">任务状态</label>
                            <div class="col-sm-9">
                                <select class="form-control valid-data" data-field="status" style="width:200px;">
                                    <option value="0">停用</option>
                                    <option value="1">启用</option>
                                </select>
                                <span class="help-block f-inline">停用后该任务无法被任何人使用</span>
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

    <script type="text/javascript" src="http://{$IMG_DOMAIN}/js/plugins/select2/select2.js"></script>
    <script>
        $(function() {
            $.fn.modal.Constructor.prototype.enforceFocus = function() {}; //fix bug at select2 4.x in modal use
            $('#admin_uid').select2({
                theme: 'bootstrap',
                placeholder: '选择用户'
            });
            $('.btn-adduser').click(function() {
                var self = $(this);
                $('#admin_uid').val('').trigger('change').data('id', self.data('id'));
                $('#text_privacy').text(self.closest('tr').find('td:eq(0)').text());
                $('#_j_add_privacy').modal();
            });
            //增加用户权限
            $('.btn-save-privacy').click(function() {
                var admin_uid = $('#admin_uid').val(),
                    task_id = $('#admin_uid').data('id');
                if (admin_uid && task_id) {
                    $('.btn-save-privacy').addClass('disabled');
                    $.post('/rest/server/taskprivacy/', {
                        'method': 'POST',
                        'post_style': 'user',
                        'update': {
                            admin_uid: admin_uid,
                            task_id: task_id
                        }
                    }, function(data, status) {
                        if (data.errno) {
                            alert('操作失败：' + data.error);
                        } else {
                            $('#_j_add_privacy').modal('hide');
                            alert('权限添加成功');
                            window.location.reload();
                        }
                        $('.btn-save-privacy').removeClass('disabled');
                    }, 'json');
                }
            });
            //删除用户权限
            $('.tag-admin').dblclick(function() {
                var self = $(this),
                    id = self.data('id');
                if (confirm('是否要删除这个权限？')) {
                    $.post('/rest/server/taskprivacy/' + id, {
                        'method': 'DELETE'
                    }, function(data) {
                        if (data.errno) {
                            alert('操作失败：' + data.error);
                        } else {
                            alert('删除成功');
                            self.remove();
                        }
                    }, 'json');
                }
            });
            //编辑task
            $('.btn-edit').click(function() {
                var self = $(this),
                    id = self.data('id');
                $.get('/rest/server/remotetask/' + id, {}, function(data) {
                    if (data.errno) {
                        alert('操作失败：' + data.error);
                    } else {
                        $('#edit_id').val(id);
                        $('#_j_task_edit .form-horizontal .valid-data:input').each(function() {
                            var self = $(this),
                                field = self.data('field');
                            if (typeof(data.data[field]) != 'undefined') {
                                self.val(data.data[field]);
                            } else self.val('');
                        });
                        $('#_j_task_edit').modal('show');
                    }
                }, 'json');
            });
            //保存task
            $('.btn-save-task').click(function() {
                var update = {
                    id: $('#edit_id').val()
                };
                $('#_j_task_edit .form-horizontal .valid-data:input').each(function() {
                    var self = $(this),
                        field = self.data('field');
                    update[field] = $.trim(self.val());
                });
                $.post('/rest/server/remotetask/', {
                    'method': 'POST',
                    'update': update
                }, function(data, status) {
                    if (data.errno) {
                        alert('操作失败：' + data.error);
                    } else {
                        $('#_j_task_edit').modal('hide');
                        window.location.reload();
                    }
                }, 'json');
            });
        });
    </script>
