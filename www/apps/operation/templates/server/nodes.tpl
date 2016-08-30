<link href="http://{$IMG_DOMAIN}/css/plugins/select2/select2.css" rel="stylesheet" type="text/css" />
<link href="http://{$IMG_DOMAIN}/css/plugins/select2/select2-bootstrap.css" rel="stylesheet" type="text/css" />
<style type="text/css">
    .status-words {
        margin-right: 5px;
    }

    {if !$mulit_mode}
    .multi-mode {
        display: none;
    }

    .multi-mode-div {
        display: none;
    }
    {else}

    .multi-mode-div {
        display:inline-flex;
    }
    {/if}

    .label,
    .badge {
        font-size: 14px;
    }

    .f-inline {
        display: inline !important;
    }

    .number {
        font-size: 20px;
        font-family: sans-serif;
    }

    .w-80p {
        width: 80%;
    }

    .w-40p {
        width: 40%;
    }

    .wp-0 {
        padding-left: 0;
        padding-right: 0;
    }

    .tags {
        cursor: pointer;
        line-height: 35px;
    }

    .td-tags {
        width: 180px;
    }

    .v-middle {
        line-height: 30px;
        padding-right: 8px;
    }

    .tag-filter {
        margin-right: 5px;
    }

    .tag-filter-del {
        cursor: pointer;
    }

    .tag-remove {
        display: none;
    }

    .select2-option {
        font-size: 14px;
        display: inline;
        padding: 0.3em 0.6em;
        font-weight: bold;
        line-height: 1;
        color: #fff;
    }

    .labeltype-font{
        font-weight: bold;
    }

    .labeltype-1 {
        background-color: #f39c12 !important;
    }

    .labeltype-font-1{
        color: #db8b0b;
    }

    .labeltype-2 {
        background-color: #00c0ef !important;
    }

    .labeltype-font-2{
        color: #00a7d0;
    }

    .labeltype-3 {
        background-color: #00a65a !important;
    }

    .labeltype-font-3{
        color: #008d4c;
    }

    .labeltype-4 {
        background-color: #b5bbc8 !important;
    }

    .labeltype-font-4{
        color: #666;
    }

    .labeltype-5 {
        background-color: #605ca8 !important;
    }

    .labeltype-font-5{
        color: #555299;
    }
</style>
<div class="content wp-3 bp-30">
    <div class="pull-right lh-row-head">
        <span class="text-primary v-middle"><b>{$sum[0]}</b> host(s), <b>{$sum[1]}</b> node(s) {if $total}on this page, <b>{$total}</b> nodes total.{/if}</span>
        <button type="button" class="btn btn-default btn-flat btn-add" title="新增服务器节点" alt="新增服务器节点">新增节点</button>
        {if $master_mode}
        <button type="button" class="btn btn-warning btn-flat btn-rsync" title="将服务器信息发布到所有服务器" alt="将服务器信息发布到所有服务器">发布配置</button>
        <button type="button" class="btn bg-olive btn-flat btn-check" title="检查服务器的连通性" alt="检查服务器的连通性">连通性检查</button>
        {/if}
    </div>
    <div class="clearfix"></div>
    <blockquote class="hidden-xs">
        <p>节点是一个最小的业务单元，是由唯一的IP和至少一个标签的组合。每个节点最多只能拥有一个服务类标签。</p>
        <p style="line-height: 30px;">{foreach $tag_type as $k => $v}
            <label class="label labeltype-{$k}"><input type="checkbox" class="label-type" value="{$k}"> {$v}</label> {/foreach}
        </p>
    </blockquote>

    <div class="row query-header">
        <div class="col-xs-12 col-md-3">
            <select class="q-tags form-control" multiple="multiple" style="display:none;width:98%;"></select>
        </div>

        <div class="col-xs-5 col-md-2">
            <input type="text" placeholder="服务器标识" class="form-control q-ip" value="{$query.ip}">
        </div>
        <div class="col-xs-7 col-md-2">
            <div class="checkbox f-inline">
                <label>
                    <input type="checkbox" class="q-status" style="position: inherit;margin-right: 5px;" {if $query.status=='online' } checked="checked" {/if}>在线状态</label>
            </div>
            <button type="button" class="btn btn-info btn-query">查询</button>
        </div>

        <div class="col-xs-12 col-md-4">
            <button type="button" class="btn btn-default btn-mulit">标签批量管理</button>
            <div class="multi-mode-div">
                <select class="form-control input-sm choose-tag" style="min-width:300px">
                    <option value="0">选择标签</option>
                    {foreach $tag_list as $t}
                    <option value="{$t.id}" data-type="{$t.type}">{$t.name}</option>
                    {/foreach}
                </select>
                <a type="button" class="btn btn-default tag-mulit" data-act="add"><i class="fa fa-plus"></i></a>
                <a type="button" class="btn btn-default tag-mulit" data-act="del"><i class="fa fa-minus"></i></a>
            </div>
        </div>
    </div>
        <div class="row">
            <div class="col-md-12 col-xs-12 wp-3">
                <div class="box">
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>服务器标识 <i class="fa fa-question-circle" title="服务器物理IP" alt="服务器物理IP"></i></th>
                                    <th>
                                        <label class="multi-mode-div"><input type="checkbox" class="mulit-check"></label>
                                        服务IP <i class="fa fa-question-circle" title="服务的监听IP (虚ip/本地ip/0.0.0.0)" alt="服务的监听IP (虚ip/本地ip/0.0.0.0)">
                                    </th>
                            <th style="width: 50%;">标签 <i class="fa fa-question-circle" title="节点的业务属性" alt="节点的业务属性"></i></th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                {foreach $nodes_list as $group => $item} {foreach $item as $order => $detail}
                                <tr data-id="{$detail.id}" data-group="{$group}" data-ip="{$detail.ip}">
                                    {if $order == 0 || $role!=''}
                                    <th scope="row" rowspan="{if $group!='' && $detail.group==$group}1{else}{count($item)}{/if}" style="background-color: #fff;vertical-align: middle;">
                                        <a href="?ip={$detail.ip}">{$group}</a>
                                        <span class="glyphicon glyphicon-plus btn-add" data-ip="{$detail.ip}" data-identification="{$detail.ip}" title="快速新增"></span>
                                    </th>
                                    {/if}
                                    <td class="number"><label><input type="checkbox" class="multi-mode" value="{$detail.id}"> {$detail.ip}</label></td>
                                    <td class="td-tags">
                                        {foreach $tag_map[$detail.id] as $t} {assign var="item" value=$tag_list[$t['tag_id']]}
                                        <span class="label labeltype-{$item.type} tags" data-tagid="{$t['tag_id']}" data-id="{$t.id}" title="{$item.memo}" alt="{$item.memo}" data-jump="{if stripos($item['name'],'VIP_')===0}{assign var='tag_tmp' value=explode('VIP_',$item['name'])}{$tag_tmp[1]}{/if}">{$item['name']}{if $item['type']==$TAG_SERVICE}:{$item['port']}{/if}</span>
                                        <button type="button" class="btn btn-danger btn-xs tag-remove"><i class="fa fa-remove"></i></button>
                                        {/foreach}
                                    </td>
                                    <td>
                                        {if $detail.status==0}
                                        <span class="status-words badge bg-red">离线</span>
                                        <a href="javascript:void(0);" class="btn btn-default icn-only change-status" data-act="online" title="上线">
                                            <span class="glyphicon glyphicon-arrow-up"></span>
                                        </a>
                                        {else}
                                        <span class="status-words badge bg-green">在线</span>
                                        <a href="javascript:void(0);" class="btn btn-default icn-only change-status" data-act="offline" title="下线">
                                            <span class="glyphicon glyphicon-arrow-down"></span>
                                        </a>
                                        {/if}
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);" class="btn btn-default icn-only btn-edit" title="编辑"><span class="glyphicon glyphicon-edit"></span></a>
                                        <a href="javascript:void(0);" class="btn btn-default icn-only btn-remove" title="删除"><span class="glyphicon glyphicon-remove"></span></a>
                                    </td>
                                </tr>
                                {/foreach} {/foreach}
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
                <h3>添加/编辑服务节点信息</h3>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_id">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">IP地址</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="" class="form-control valid-data w-40p number f-inline ipt-ip" data-field="ip" data-format="ip">
                            <span class="help-block f-inline">对外服务所监听的IP地址</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">服务器标识</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="" class="form-control valid-data w-40p number f-inline ipt-identification" data-field="identification" data-format="ip">
                            <span class="help-block f-inline">一般为192.168.开头的IP地址</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">节点标签 (可多选)</label>
                        <div class="col-sm-9">
                            <select class="valid-data ipt-tags" multiple="multiple" data-field="tag_ids" style="width:100%;"></select>
                            <div style="line-height:45px;">
                                <span class="help-block f-inline">
                                  通过标签名称或者端口号查找 Or 没有合适的标签<a href="tag#add" target="_blank">新增标签</a>
                                </span>

                            </div>
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
        var tags = {$raw_tag}, default_tag_type = ['1','2','3'];//标签内容

        $.fn.modal.Constructor.prototype.enforceFocus = function() {}; //fix bug at select2 4.x in modal use

        //按类型获取标签数据
        function getTagByTypes(tags,types){
            var filter_data = [ ];
            for(var i in tags){
                if($.inArray(tags[i].type,types) != -1) filter_data.push(tags[i]);
            };
            return filter_data;
        }

        //初始化标签选择器
        function initTagSelect(element,data,selected){
            element.empty();
            for(var i in data){
                var self = data[i];
                element.append('<option value="'+self.id+'" data-type="'+self.type+'">'+self.name+'</option>');
            };

            var target = element.select2({
                theme: 'bootstrap',
                placeholder: '选择标签',
                templateResult: function(data){
                    if (!data.id) { return data.text; }
                    return $('<span class="select2-option labeltype-'+$(data.element).data('type')+'"> ' + data.text + '</span>');
                },
                templateSelection: function(data, container){
                    if (!data.id) { return data.text; }
                    return $('<span class="labeltype-font labeltype-font-'+$(data.element).data('type')+'"> ' + data.text + '</span>');
                }
            });
            typeof(selected) != "undefined" && element.val(selected).trigger('change');
            return target;
        }

        initTagSelect($('.q-tags'),getTagByTypes(tags,default_tag_type),{json_encode($query.tag_id)});

        $ipt_tags = initTagSelect($('.ipt-tags'),tags);
        initTagSelect($('.choose-tag'),tags,0);

        for(var i in default_tag_type){
            $('.label-type[value='+default_tag_type[i]+']').attr('checked',true);
        }

        //更改标签选择器的标签类型
        $('.label-type').click(function(){
            var allow_type = [ ];
            $('.label-type').each(function(){
                $(this)[0].checked && allow_type.push($(this).val());
            });
            $('.q-tags').select2("destroy");
            initTagSelect($('.q-tags'),getTagByTypes(tags,allow_type));
        });

        //切换上下线状态
        function switch_status(obj) {
            var pre_st = obj.data('act');
            if (pre_st == 'online') {
                obj.data('act', 'offline').attr('title', '下线');
                obj.prev('.status-words').text('在线').removeClass('bg-red').addClass('bg-green');
                obj.find('span').removeClass('glyphicon-arrow-up').addClass('glyphicon-arrow-down');
            } else if (pre_st == 'offline') {
                obj.data('act', 'online').attr('title', '上线');
                obj.prev('.status-words').text('离线').removeClass('bg-green').addClass('bg-red');
                obj.find('span').removeClass('glyphicon-arrow-down').addClass('glyphicon-arrow-up');
            }
        }

        //查询功能
        $('.btn-query').click(function() {
            var tags = $('.q-tags').val();
            var tag_str = (tags && tags.length > 0) ? tags.join(',') : '';
            var status = $('.q-status:checked').length > 0 ? 'online' : '';
            window.location = 'nodes?ip=' + $('.q-ip').val() +
                '&tag_id=' + tag_str + '&status=' + status;
        });

        //回车查询
        $('body').keydown(function() {
            if (event.keyCode == '13' && $('#_j_menu_edit').is(':hidden')) {
                $('.btn-query').trigger('click');
            }
        });

        //辅助功能-填写服务器标识
        $('.ipt-ip').blur(function() {
            if ($('.ipt-identification').val() == '') $('.ipt-identification').val($(this).val());
        });

        //发布配置
        $('.btn-rsync').click(function() {
            var self = $(this);
            lconfirm('您确定要将当前的服务器信息发布到所有节点么？', function() {
                self.text('processing').attr('disabled', true);

                var aj = $.ajax({
                    url: '/rest/sqlite/dump/1001',
                    type: 'get',
                    cache: false,
                    dataType: 'json',
                    success: function(d) {
                        if (d.errno == 0 && d.data.ret == true) {
                            self.text('发布成功');
                        } else {
                            self.text('发布失败');
                        }
                        self.removeAttr('disabled');
                        setTimeout(function() {
                            self.text('发布配置');
                        }, 2000);
                    },
                    error: function() {
                        self.removeAttr('disabled');
                        alert('发布失败，出现严重错误，请尽快联系技术人员解决！');
                    }
                });
            });
        });

        //连通性检查
        $('.btn-check').click(function() {
            var self = $(this);
            self.text('Checking...').attr('disabled', true);
            $.get('/rest/ansible/ping/1001', { }, function(d) {
                if (d.errno == 0) {
                    var msg = '';
                    if (d.data.unreachable.length) msg += '<p style="word-wrap: break-word;word-break: normal;">以下IP无法连通: ' + d.data.unreachable.join(' / ') + '</p>';
                    if (d.data.connected.length) msg += '<p style="word-wrap: break-word;word-break: normal;">新发现以下IP可以连接: ' + d.data.connected.join(' / ') + '</p>';
                    alert(msg == '' ? '恭喜，目前一切正常' : msg);
                } else {
                    alert(d.error);
                }
                self.text('连通性检查').removeAttr('disabled');
            }, 'json');
        });

        //添加节点-界面
        $('.btn-add').click(function() {
            var init = $(this).data();
            $('#_j_menu_edit .form-horizontal .valid-data:input').each(function() {
                var self = $(this),
                    field = self.data('field'),
                    val = init[field];
                if (field == 'tag_ids') $(".ipt-tags").val(val).trigger('change');
                else self.val(val);
                //field == 'identification' && self.val('');
            });
            $('#edit_id').val('');
            $('#_j_menu_edit').modal('show');
        });

        //编辑节点-界面
        $('.btn-edit').click(function() {
            var id = $(this).closest('tr').data('id');
            if (id) {
                $.get('/rest/server/nodes/' + id, {}, function(data, status) {
                    if (status == 'success' && data.errno == 0) {
                        $('#edit_id').val(id);
                        $('#_j_menu_edit .form-horizontal .valid-data:input').each(function() {
                            var self = $(this),
                                field = self.data('field');
                            if (typeof(data.data[field]) != 'undefined') {
                                if (field == 'tag_ids') {
                                    $ipt_tags.val(data.data[field]).trigger('change');
                                } else self.val(data.data[field]);
                            } else self.val('');
                        });
                        $('#_j_menu_edit').modal('show');
                    } else {
                        alert('操作失败：' + data.error);
                    }
                }, 'json');
            }
        });

        //添加/保存节点
        $('.btn-save').click(function() {
            var update = {
                id: $('#edit_id').val()
            };
            $('#_j_menu_edit .form-horizontal .valid-data:input').each(function() {
                var self = $(this),
                    field = self.data('field');
                update[field] = $.trim(self.val());
            });
            $.post('/rest/server/nodes/', {
                'method': 'POST',
                'after_style': 'postdone',
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

        //删除节点
        $('.btn-remove').click(function() {
            var self = $(this),
                item = self.closest('tr'),
                msg = '确定要删除 ' + item.data('ip') + ' 的该节点吗?<br><span style="color:#d73925;">如果该节点为临时下线，不建议使用删除操作！</span>';
            lconfirm(msg, function() {
                $.post('/rest/server/nodes/' + item.data('id'), {
                    'method': 'DELETE'
                }, function(data, status) {
                    if (data.errno) {
                        alert('操作失败：' + data.error);
                    } else {
                        if (item.find('th').length > 0 && item.next('tr').find('th').length == 0) {
                            var th_html = item.find('th').clone();
                            item.next('tr').prepend(th_html.attr('rowspan', th_html.attr('rowspan') * 1 - 1));
                        } else if (item.prevAll("tr[data-group='" + item.data('group') + "']").find('th').attr('rowspan') * 1 > 1) {
                            var th_rows = item.prevAll("tr[data-group='" + item.data('group') + "']").find('th').attr('rowspan') * 1 - 1;
                            item.prevAll("tr[data-group='" + item.data('group') + "']").find('th').attr('rowspan', th_rows);
                        }
                        item.remove();
                    }
                }, 'json');
            });
        });

        //上下线功能
        $('.change-status').click(function() {
            var self = $(this),
                item = self.closest('tr'),
                msg = '确定要' + self.attr('title') + item.data('ip') + ' 的节点吗?';
            lconfirm(msg, function() {
                $.post('/rest/server/nodes/' + item.data('id'), {
                    'method': 'PUT',
                    'put_style': self.data('act')
                }, function(data, status) {
                    if (data.errno) {
                        alert('操作失败：' + data.error);
                    } else {
                        switch_status(self);
                    }
                }, 'json');
            });
        });

        //tag批量操作-提交
        $('.tag-mulit').click(function() {
            var obj = $(this), item = $('.choose-tag option:selected'), target = $('.multi-mode:checked'),
                node_ids = [], acts = { 'add':'增加', 'del':'删除' }, act = obj.data('act');
            if(!acts[act]) return false;
            if(typeof(item.val())=='undefined'){
                alert('请选择要批量'+acts[act]+'的标签');
                return false;
            }
            if(target.length == 0) {
                alert('请选择要操作的节点，请在服务IP前的复选框做选择');
                return false;
            } else {
                target.each(function(){
                    node_ids.push($(this).val());
                });
            }
            msg = '要批量给选中的节点<span class="text-red">' + acts[act] + '</span> <b>' + item.text() + '</b> 标签吗?';
            lconfirm(msg,function(){
                $.post('/rest/server/tagmap/', {
                    'method': 'POST',
                    'update': {
                        id: item.val(),
                        node_ids: node_ids
                    },
                    'post_style': act
                }, function(data, status) {
                    if (data.errno != 0) {
                        alert('操作失败：' + data.error);
                    } else {
                        alert('操作成功!');
                        window.location.reload();
                    }
                }, 'json');
            });
        });

        //节点批量操作
        $('.btn-mulit').click(function(){
            //console.log(window.location.pathname);
            var query_string = window.location.search, match_rule = /mulit=?(\d)/, match_arr = match_rule.exec(query_string);
            if(match_arr){
                var mulit = match_arr[1] == '1' ? 0 : 1;
                query_string = query_string.replace(match_rule,'mulit='+mulit);
            } else {
                query_string = query_string=='' ? '?mulit=1' : query_string+'&mulit=1';
            }
            window.location.href = window.location.pathname + query_string;
        });

        //批量操作-全选/取消全选
        $('.mulit-check').change(function(){
            $('.multi-mode:checkbox').prop('checked',this.checked);
        });

        $('table').delegate('.tag-remove', 'click', function() { //tag删除功能
            var self = $(this).prev('span');
            if (self.data('id') && confirm('是要删除 ' + self.text() + ' 这个标签吗?')) {
                $.post('/rest/server/tagmap/' + self.data('id'), {
                    'method': 'DELETE'
                }, function(data) {
                    data.errno == 0 && self.remove();
                }, 'json');
            }
        }).delegate('.tags', 'click', function() { //tag单击功能
            var self = $(this);
            if (self.data('jump')!='') window.location.href = 'nodes?ip=&tag_name=' + self.data('jump');
            else if (self.data('tagid') * 1)  window.location.href = 'nodes?ip=&tag_id=' + self.data('tagid');
        });
        {*
            //标签上的悬浮删除操作
            .delegate('.tags', 'mouseenter', function() {
                $(this).next('button').show();
            }).delegate('.tags', 'mouseout', function() {
                var self = $(this);
                setTimeout(function() {
                    self.next('button').fadeOut();
                }, 1500);
            });
        *}

        //删除筛选tag 重新触发查询
        $('.tag-filter-del').click(function() {
            $(this).closest('.tag-filter').remove();
            $('.btn-query').trigger('click');
        });
    });
</script>
