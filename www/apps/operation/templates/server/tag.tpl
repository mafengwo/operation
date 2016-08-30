<style type="text/css">
    .status-words {
        padding-right: 8px;
    }

    .w-30p {
        width: 30%;
    }

    .f-inline {
        display: inline !important;
    }

    .number {
        font-size: 20px;
        font-family: sans-serif;
    }

    .pull-right {
        float: right;
        margin-right: 5px;
        margin-top: 10px;
    }

    .service-type {
        padding: 6px;
    }

    .div-port {
        display: none;
    }
</style>
<div class="content wp-3 bp-30">
    <blockquote class="hidden-xs">
        <p>即Tag，用来修饰标识一个节点的工具。一个节点可以拥有多个标签。特别要说明的是：标签根据使用场景会分为不同类别。</p>
        <footer>配置合适的标签类型，可以让工作得心应手。</footer>
    </blockquote>

    <div class="row query-header">
        <div class="hidden-xs col-md-1">查找标签：</div>
        <div class="col-xs-6 col-md-3">
            <input type="text" placeholder="标签名称" class="form-control q-name" value="{$query.name}">
        </div>

        <div class="col-xs-6 col-md-2">
            <select class="form-control q-type">
                <option value="0">全部标签</option>
                {foreach $tag_type as $k => $v}
                <option value="{$k}">{$v}</option>
                {/foreach}
            </select>
        </div>
        <div class="col-xs-offset-5 col-xs-7 col-md-offset-0 col-md-4">
            <button type="button" class="btn btn-primary btn-query">查询</button>
            <button type="button" class="btn btn-default btn-add" style="margin-left: 2%;">新增标签</button>
        </div>
        <div class="col-md-2">
            <span class="pull-right text-primary v-middle">Tag total: <b>{$total}</b> </span>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xs-12 wp-3">
            <div class="box">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>标签名称</th>
                                <th>类型</th>
                                <th>端口/参数</th>
                                <th>说明</th>
                                <th>节点分布</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach $tag_list as $s}
                            <tr data-id="{$s.id}" data-name="{$s.name}">
                                <th><a href="nodes?tag_id={$s.id}">{$s.name}</a></th>
                                <td><a href="tag?type={$s.type}">{$tag_type[$s.type]}</a></td>
                                {if !empty($s.port)}
                                <td class="number">{$s.port}</td>
                                {else}
                                <td>{$s.process_path}</td>
                                {/if}
                                <td>{$s.memo}</td>
                                <td>
                                    <span class="badge bg-green" alt="在线状态" title="在线状态">{$stat_num[$s.id]['online']}</span>
                                    <span class="badge bg-red" alt="离线状态" title="离线状态">{$stat_num[$s.id]['offline']}</span>
                                </td>
                                <td>
                                    <a href="javascript:void(0);" class="btn btn-default icn-only btn-edit" title="编辑"><span class="glyphicon glyphicon-edit"></span></a>
                                    <a href="javascript:void(0);" class="btn btn-default icn-only btn-iplist" title="节点IP列表编辑"><span class="glyphicon glyphicon-th-list"></span></a>
                                    <a href="javascript:void(0);" class="btn btn-default icn-only btn-remove" title="删除"><span class="glyphicon glyphicon-remove"></span></a>
                                </td>
                            </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="page-bar pull-right">
        {$pagebar}
    </div>

</div>
<div class="modal fade" tabindex="-1" id="_j_menu_edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h3>添加/编辑标签信息</h3>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_id">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">标签名称</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="" class="form-control" data-field="name">
                            <span class="help-block">填写标签名称。请使用英文、数字或英文半角符号</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">标签类型</label>
                        <div class="col-sm-9">
                            <select class="form-control w-30p f-inline slct-type" data-field="type">
                                <option value="">请选择 (必填)</option>
                                {foreach $tag_type as $k => $v}
                                <option value="{$k}">{$v}</option>
                                {/foreach}
                            </select>
                            <span class="help-block f-inline tips-type">通常的，您可能需要使用业务标签</span>
                        </div>
                    </div>
                    <div class="form-group div-port">
                        <label class="col-sm-3 control-label">端口</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="" class="form-control f-inline w-30p" data-field="port">
                            <span class="help-block f-inline">该服务所守候的端口号</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">标签注解</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="" class="form-control" data-field="memo">
                            <span class="help-block">简短的文字描述 刻画标签的重要特征 (可选)</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">取消</button>
                <button type="button" class="btn btn-primary btn-save">保存</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="_j_iplist_edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h3>编辑节点IP列表</h3>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_id">
                <div class="form-horizontal">
                    <div class="form-group nodes-list">
                        <label class="col-sm-3 control-label btn-list">节点IP列表</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="nodes_list" rows="10"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">取消</button>
                <button type="button" class="btn btn-primary btn-list-save">保存</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        var type_tips = [
            '通常的，您可能需要使用业务标签',
            '与业务无关，比如 master,salve 等',
            '标签名称为服务名，并需要在下方填写端口号',
            '业务名称，如 admin,redis_abc,file_group_1',
            '计划任务标签,名称为schedule调用名称',
            '虚拟IP的标示，用来快速区分及获取虚IP'
        ];

        //选择标签类型
        $('.slct-type').change(function() {
            var self = $(this),
                type = self.val() * 1;
            if ($(this).val() == 2) {
                $('.div-port').show();
            } else {
                $('.div-port').hide();
            }
            type_tip(type);
        });

        //信息提示
        function type_tip(type) {
            type_tips[type] && $('.tips-type').text(type_tips[type]);
        }

        //新建服务-界面
        $('.btn-add').click(function() {
            //$('.service-type').removeAttr('disabled');
            //$('.service-type[data-type="port"]').trigger('click');
            $('#_j_menu_edit .form-horizontal .form-control:input').each(function() {
                var self = $(this);
                self.val('');
                self.data('field') == 'name' && self.removeAttr('disabled');
            });
            $('#edit_id').val('');
            $('#_j_menu_edit').modal('show');
        });

        //编辑服务-界面
        $('.btn-edit').click(function() {
            var id = $(this).closest('tr').data('id');
            if (id) {
                $.get('/rest/server/tag/' + id, {}, function(data, status) {
                    if (status == 'success' && data.errno == 0) {
                        $('#edit_id').val(id);
                        $('#_j_menu_edit .form-horizontal .form-control:input').each(function() {
                            var self = $(this),
                                field = self.data('field');
                            if (typeof(data.data[field]) != 'undefined') {
                                self.val(data.data[field]);
                            } else self.val('');
                            field == 'name' && self.attr('disabled', true);
                        });
                        type_tip(data.data.type);
                        if (data.data.type == 2) $('.div-port').show();
                        else $('.div-port').hide();
                        $('#_j_menu_edit').modal('show');
                    } else {
                        alert('操作失败：' + data.error);
                    }
                }, 'json');
            }
        });

        //保存服务
        $('.btn-save').click(function() {
            var update = {
                id: $('#edit_id').val()
            };
            $('#_j_menu_edit .form-horizontal .form-control:input').each(function() {
                var self = $(this),
                    field = self.data('field');
                update[field] = self.val();
            });
            $.post('/rest/server/tag/', {
                'method': 'POST',
                'update': update
            }, function(data, status) {
                if (data.errno) {
                    alert('操作失败：' + data.error);
                } else {
                    $('#_j_menu_edit').modal('hide');
                    window.location.reload();
                }
            }, 'json');
        });

        //删除服务
        $('.btn-remove').click(function() {
            var self = $(this),
                item = self.closest('tr'),
                msg = '确定要删除 ' + item.data('name') + ' 标签吗?';
            if (confirm(msg)) {
                $.post('/rest/server/tag/' + item.data('id'), {
                    'method': 'DELETE'
                }, function(data, status) {
                    if (data.errno) {
                        alert('操作失败：' + data.error);
                    } else {
                        window.location.reload();
                    }
                }, 'json');
            }
        });

        //ip列表编辑-查看
        $('.btn-iplist').click(function() {
            var self = $(this),
                item = self.closest('tr');
            $.get('/rest/server/tagnodes/' + item.data('id'), {}, function(d) {
                if (d.errno == 0 && d.data != '') {
                    $('#nodes_list').val(d.data);
                } else {
                    $('#nodes_list').val('');
                }
                $('#nodes_list').data('change', 0).data('id', item.data('id'));
                $('#_j_iplist_edit').modal('show');
            }, 'json');
        });

        //ip列表编辑-保存
        $('.btn-list-save').click(function() {
            var target = $('#nodes_list');
            if (target.data('change') && target.data('id')) {
                $.post('/rest/server/tagnodes/', {
                    'update': {
                        id: target.data('id'),
                        list: target.val()
                    }
                }, function(d) {
                    if (d.errno == 0) {
                        alert('修改成功！');
                        $('#_j_iplist_edit').modal('hide');
                    } else {
                        alert('操作失败：' + data.error);
                    }
                }, 'json');
            }
        });

        //更改标识
        $('#nodes_list').change(function() {
            $(this).data('change', 1);
        })

        //锚点指令
        window.location.hash == '#add' && $('.btn-add').trigger('click');

        //初始化查询条件
        $('.q-type').val({$query.type});

        //查找标签
        $('.btn-query').click(function() {
            window.location.href = 'tag?type=' + $('.q-type').val() + '&name=' + $('.q-name').val();
        });

    });
</script>
