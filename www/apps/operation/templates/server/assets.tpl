<link href="http://{$IMG_DOMAIN}/css/plugins/select2/select2.css" rel="stylesheet" type="text/css" />
<link href="http://{$IMG_DOMAIN}/css/plugins/select2/select2-bootstrap.css" rel="stylesheet" type="text/css" />
<style type="text/css">
    .w-20p {
        width: 20%;
    }

    .w-30p {
        width: 30%;
    }

    .w-40p {
        width: 40%;
    }

    .div-slct-ip{
        display: none;
    }
</style>
<div class="content wp-3 bp-30">
    <div class="row query-header">
        <div class="col-xs-12 col-md-2 lh-row-head">
            <input type="text" class="form-control s-ip s-data" data-field="ip" placeholder="IP" value="{$query.ip}">
        </div>
        <div class="col-xs-12 col-md-1 lh-row-head">
            <select class="form-control s-idc s-data" data-field="idc">
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
            <button type="button" class="btn btn-primary btn-add btn-block" alt="新增服务器配置">新增</button>
        </div>
        <div class="pull-right"><span class="text-primary v-middle">Find <b>{$total}</b> item(s)</span></div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xs-12 wp-3">
            <div class="box">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>IP地址</th>
                                <th>机房</th>
                                <th>机柜</th>
                                <th>型号</th>
                                <th>cpu</th>
                                <th>内存</th>
                                <th>磁盘</th>
                                <th>Raid</th>
                                <th>上架日期</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach $assets_data as $v}
                            <tr data-id="{$v.id}" data-pid="{$v.pid}">
                                <td>{$v.ip}</td>
                                <td>{$idc[$v.idc]}</td>
                                <td>{$v.cabinet}</td>
                                <td>{$v.type}</td>
                                <td>{$v.cpu}</td>
                                <td>{$v.mem}</td>
                                <td>{$v.disk}</td>
                                <td>{$v.raid}</td>
                                <td>{$v.date}</td>
                                <td>
                                    <a href="javascript:void(0);" class="btn btn-warning icn-only btn-edit" title="编辑"><span class="glyphicon glyphicon-edit"></span></a>
                                    <a href="javascript:void(0);" class="btn btn-danger icn-only btn-remove" title="删除"><span class="glyphicon glyphicon-remove"></span></a>
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

<div class="modal fade" tabindex="-1" id="server_config">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>添加/编辑服务器信息</h3>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <input type="hidden" id="edit_id">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">IP地址</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="" class="form-control valid-data w-30p number f-inline ipt-ip" data-field="ip">
                            <div class="div-slct-ip">
                                <select class="add-idc form-control valid-data" data-field="ip" style="width:100%;">
                                    <option value="">请输入IP地址</option>
                                    {foreach $holdon_data as $v}
                                    <option value="{$v.ip}">{$v.ip}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-sm-3 control-label">型号</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="" class="form-control valid-data w-20p number f-inline ipt-type" data-field="type" data-format="model">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">CPU</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="" class="form-control valid-data w-40p number f-inline ipt-cpu" data-field="cpu" data-format="cpu">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">内存</label>

                        <div class="col-sm-9">
                            <input type="text" placeholder="" class="form-control valid-data w-40p number f-inline ipt-mem" data-field="mem" data-format="mem">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">硬盘</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="" class="form-control valid-data w-40p number f-inline ipt-disk" data-field="disk" data-format="disk">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Raid</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="" class="form-control valid-data w-40p number f-inline ipt-raid" data-field="raid" data-format="raid">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">上架日期</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="yyyy-mm-dd" class="form-control valid-data w-40p number f-inline ipt-disk" data-field="date" data-format="date">
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

<script type="text/javascript" src="http://{$IMG_DOMAIN}/js/plugins/select2/select2.js"></script>
<script type="text/javascript">
    $(function() {
        $.fn.modal.Constructor.prototype.enforceFocus = function() {}; //fix bug at select2 4.x in modal use

        var slct_idc = $('.s-idc').select2({
            theme: 'bootstrap',
            placeholder: '筛选机房'
        });

        slct_idc.val({$query.idc}).trigger('change');

        var add_slct = $('.add-idc').select2({
            theme: 'bootstrap',
            placeholder: '请输入IP地址'
        });

        $('.btn-add').click(function() {
            $('.div-slct-ip').show();
            $('.ipt-ip').hide();
            $('#server_config .form-horizontal .valid-data:input').each(function(){
                $(this).val('');
            });
            $('#server_config').modal('show');
        });

        //保存
        $('.btn-save').click(function() {
            var update = { id : $('#edit_id').val() };
            $('#server_config .form-horizontal .valid-data:visible').each(function() {
                var self = $(this),field = self.data('field');
                update[field] = $.trim(self.val());
            });
            //console.log(update);return;
            $.post('/rest/server/assets/', {
                'method': 'POST',
                'update': update
            }, function(data, status) {
                if (data.errno) {
                    alert(data.error);
                } else {
                    window.location.reload();
                }
            }, 'json');
        });

        //修改
        $('.btn-edit').click(function () {
            $('.div-slct-ip').hide();
            $('.ipt-ip').show();
            $.get('/rest/server/assets/'+$(this).closest('tr').data('id'),{ },function(d){
                if(d.errno==0 && d.data){
                    $('#edit_id').val(d.data.id);
                    $('#server_config .form-horizontal .valid-data:input').each(function(){
                        var self = $(this), field = self.data('field');
                        self.val(d.data[field]);
                        field == 'ip' && self.attr('readonly',true);
                    });
                    $('#server_config').modal('show');
                } else {
                    alert(d.error);
                }
            },'json');
        });

        //删除
        $('.btn-remove').click(function() {
            var item = $(this).closest('tr');
            var msg = '确定要删除这条配置信息吗？';
            if (confirm(msg)) {
                $.post('/rest/server/assets/' + item.data('id'), {
                    'method': 'DELETE',
                }, function(data, status) {
                    item.remove();
                }, 'json');
            }
        });

        //查询
        $('.btn-search').click(function () {
            var update = { };
            update.ip = $('.s-ip').val();
            update.idc = $('.s-idc').val();
            window.location = '?ip=' + update.ip + '&idc=' + update.idc;
        });
    });
</script>
