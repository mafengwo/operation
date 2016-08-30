<style type="text/css">
    .status-words {
        padding-right: 8px;
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

    .tags {
        cursor: pointer;
    }
</style>
<div>
    <section class="content wp-3">
        <div class="row">
            <div class="col-md-12 col-xs-12 wp-3">
                <div class="box">
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>UID</th>
                                    <th>用户名</th>
                                    <th>注册时间</th>
                                    <th>用户权限 <span style="font-weight:normal;">(全部权限在配置文件中定义，单独权限请到『节点配置』中进行授权)</span></th>
                                    <th>用户状态</th>
                                </tr>
                            </thead>
                            <tbody>
                                {foreach $list as $u}
                                <tr data-id="{$u.id}">
                                    <th>{$u.id}</th>
                                    <td>{$u.username}</td>
                                    <td>{$u.ctime}</td>
                                    <td>
                                        {if in_array($u['id'],$super_list)}<span class="label bg-green">全部权限</span>{/if} {if count($privacy_list[$u.id])}{foreach $privacy_list[$u.id] as $p}
                                        <span class="label label-info tags" data-id="{$p.id}" title="双击删除该权限">{$p.node_name}{if $p.parent}({$p.parent}){/if}</span> {/foreach}{/if}
                                    </td>
                                    <td>
                                        {if $u.flag==0} 待审核 {if $super}
                                        <a class="btn btn-default btn-pass" href="javascript:void(0);" role="button">
                                            <span class="glyphicon glyphicon-user"></span>审核</a>
                                        {/if} {else}正常{/if}
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
</div>

<script type="text/javascript">
$(function() {
    {if $super}
    $('.tags').dblclick(function() {
        var self = $(this),
            id = self.data('id');
        if (confirm('是否要删除这个权限？')) {
            $.post('/rest/system/privacy/' + id, {
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
    {/if}
    $('.btn-pass').click(function() {
        var self = $(this),
            item = self.closest('tr');
        $.post('/rest/user/item/' + item.data('id'), {
            'method': 'PUT',
            'put_style': 'audit'
        }, function(data, status) {
            if (data.errno) {
                alert('操作失败：' + data.error);
            } else {
                alert('审核成功');
                self.closest('td').empty().append('正常');
            }
        }, 'json');
    });
});
</script>
