<link href="http://{$IMG_DOMAIN}/css/plugins/select2/select2.css" rel="stylesheet" type="text/css" />
<link href="http://{$IMG_DOMAIN}/css/plugins/select2/select2-bootstrap.css" rel="stylesheet" type="text/css" />
<style type="text/css">
    .tag {
        font-size: large;
        color: #005384;
        font-family: Comic Sans MS;
        line-height: 100%;
    }

    .font-ip {
        font-size: large;
        color: #005384;
        font-family: Comic Sans MS;
        line-height: 100%;
    }

    .w-100p {
        width: 100%;
        margin: 1px;
        display: block;
    }

    .w-30p {
        width: 30%;
    }

    .w-20p {
        width: 20%;
    }

    .height {
        height: 30px;
    }

    .col {
        padding-top: 3px;
        padding-bottom: 5px;
    }
</style>
<div>
    <div class="row">
        <div class="col-xs-12 col-md-2 lh-row-head">
            <input type="text" class="form-control s-ip" placeholder="IP" value="{$query.ip}"/>
        </div>
        <div class="col-xs-12 col-md-1 lh-row-head">
            <select class="form-control s-idc">
                <option value="">请选择</option>
                {foreach $idc as $key=>$val}
                <option value="{$key}">{$val}</option>
                {/foreach}
            </select>
        </div>
        <div class="col-xs-12 col-md-1 lh-row-head">
            <button type="button" class="btn btn-info btn-search btn-block">查询</button>
        </div>
        <div class="col-xs-12 col-md-1 lh-row-head">
            <button type="button" class="btn btn-primary btn-add btn-block">新增</button>
        </div>
        <div class="col-xs-12 col-md-1 lh-row-head">
            <button type="button" class="btn btn-danger btn-delete btn-block">删除</button>
        </div>
        <div class="col-xs-12 col-md-1 lh-row-head">
            <button type="button" class="btn btn-warning btn-alter btn-block">修改</button>
        </div>
        <div class="pull-right"><span class="text-primary v-middle">Find <b>{$hosts_nums}</b> ips,
        <b>{$cabinet_nums}</b> cabinets </span></div>
    </div>
    <div class="content">
        <div class="row">
            {if $data && $data.cabinet} {foreach $data.cabinet as $k=>$v}
            <div class="col-md-2 col">
                <label class="btn btn-primary height w-100p">
                    <input type="radio" class="cabinet_tag" value="{$v}" name="cabinet_tag" data-id="{$data['id'][$v]}">
                    <span class="tag">{$v}</span>
                </label> {for $p=1 to $MAX_IP_PER_CABINET} {if !empty($query.ip) && strpos($data['ip'][$v][$p],$query.ip)!==false}
                <a class="btn btn-warning height w-100p font-ip btn-ip{$v}" data-position="{$p}" data-val="{$data['ip'][$v][$p]}">{$data['ip'][$v][$p]}</a> {else}
                <a class="btn btn-info height w-100p font-ip btn-ip{$v}" data-position="{$p}" data-val="{$data['ip'][$v][$p]}">{$data['ip'][$v][$p]}</a> {/if} {/for}
            </div>
            {/foreach} {/if}
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="add_cabinet">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>添加/编辑服务器信息</h3>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <input type="hidden" class="valid-data" data-field="id">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">机房</label>
                        <div class="col-sm-9">
                            <select class="form-control valid-data add-idc" data-field="idc">
                                <option value="">请选择</option>
                                {foreach $idc as $key=>$val}
                                <option value="{$key}">{$val}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">机柜编号</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control valid-data w-20p" data-field="cabinet">
                        </div>
                    </div>
                    {for $p = 1 to $MAX_IP_PER_CABINET }
                    <div class="form-group">
                        <label class="col-sm-3 control-label">IP地址</label>
                        <div class="col-sm-8 input-group">
                            <span class="input-group-addon">机位{$p}</span>
                            <input type="text" class="form-control valid-data w-30p number f-inline" data-position="{$p}" data-field="ip">
                        </div>
                    </div>
                    {/for}
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">取消</button>
                <button type="button" class="btn btn-primary btn-save">保存</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="http://{$IMG_DOMAIN}/js/plugins/select2/select2.js"></script>
<script type="text/javascript">
    $(function() {
        $('.add-idc').select2({
            theme: 'bootstrap',
            placeholder: '选择机房'
        });
        $('.s-idc').select2({
            theme: 'bootstrap',
            placeholder: '筛选机房'
        }).val({$query.idc}).trigger('change');
        //新增界面
        $('.btn-add').click(function() {
            $('#add_cabinet .form-horizontal .valid-data:input').each(function() {
                var self = $(this),
                    field = self.data('field');
                self.val('');
                field == 'idc' && self.trigger('change');
            });
            $('#add_cabinet').modal('show');
        });
        //录入保存
        $('.btn-save').click(function() {
            var update = {},
                ips = {};
            $('#add_cabinet .form-horizontal .valid-data:input').each(function() {
                var self = $(this),
                    field = self.data('field');

                if (field == 'ip' && $.trim(self.val()) != '') {
                    var pid = self.data('position');
                    ips[pid] = $.trim(self.val());
                } else update[field] = $.trim(self.val());
            });
            update.ip = ips;

            $.post('/rest/server/cabinet/', {
                'method': 'POST',
                'post_style': 'add_cabinet',
                'update': update
            }, function(data, status) {
                if (data.errno) {
                    alert(data.error);
                } else {
                    $('#add_cabinet').modal('hide');
                    window.location.reload();
                }
            }, 'json');
        });
        //修改界面
        $('.btn-alter').click(function() {
            var item = $('.cabinet_tag:checked');
            if (item.length == 1) {
                $.get('/rest/server/cabinet/' + item.data('id'), {}, function(d) {
                    if (d.errno == 0 && d.data) {
                        $('#add_cabinet .form-horizontal .valid-data:input').each(function() {
                            var self = $(this),
                                field = self.data('field');
                            if (field == 'idc') self.val(d.data[field]).trigger('change');
                            else if (field == 'ip') {
                                var pid = self.data('position');
                                d.data[field][pid] ? self.val(d.data[field][pid]['ip']) : self.val('');
                            } else self.val(d.data[field]);
                        });
                        $('#add_cabinet').modal('show');
                    } else {
                        alert('修改请求失败，请刷新页面后重试!');
                    }
                }, 'json');
            } else {
                alert('请选择要修改的机柜');
            }
        });
        //删除
        $('.btn-delete').click(function() {
            var item = $('.cabinet_tag:checked');
            lconfirm('确定要删除这个机柜以及内部所有数据么？',function(){
                $.post('/rest/server/cabinet/', {
                    'method': 'POST',
                    'post_style': 'delete_cabinet',
                    'update': { 'id': item.data('id') }
                }, function(d) {
                    if(d.errno==0) window.location.reload();
                    else alert(d.error)
                }, 'json');
            });
        });
        //查询
        $('.btn-search').click(function() {
            var update = {};
            update.ip = $('.s-ip').val();
            update.idc = $('.s-idc').val();
            window.location = '?ip=' + update.ip + '&idc=' + update.idc;
        });
    });
</script>
