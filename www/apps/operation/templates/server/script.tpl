<style type="text/css">
    .modal-dialog {
        width: 95%;
    }

    .lh-30 {
        line-height: 30px;
    }

    .pd-5 {
        padding: 5px;
    }
</style>
<div>
    <div class="form-group">
        <a class="btn btn-default" href="/server/remote/index"><span class="glyphicon glyphicon-menu-left">返回</span></a>
        <a class="btn btn-default btn-new" href="javascript:void(0);"><span class="glyphicon glyphicon-plus">新增</span></a>
    </div>
    {assign var="c" value="4"} {foreach $list as $k => $v}
    <p class="bg-info lh-30 pd-5">{strtoupper($k)}</p>
    {assign var="o" value="0"} {foreach $v as $file} {if $o%$c == 0}
    <div class="row">{/if}
        <div class="col-xs-9 col-md-{(12/$c)-1} lh-row-head">{$file}</div>
        <div class="col-xs-1 col-md-1 lh-row-head">
            <a data-path="{$file}" href="javascript:void(0);" class="btn btn-default icn-only btn-edit" title="编辑">
                <span class="glyphicon glyphicon-edit"></span>
            </a>
        </div>
        {if $o%$c == $c-1}</div>{/if} {assign var="o" value=$o+1} {/foreach} {if $o%$c != 0}</div>{/if} {/foreach}
</div>

<div class="modal fade" tabindex="-1" id="_j_menu_edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h3>查看/编辑脚本</h3>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_id">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-md-2 control-label">文件名</label>
                        <div class="col-md-10">
                            <input type="text" placeholder="" class="form-control path">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">内容</label>
                        <div class="col-md-10">
                            <textarea class="content form-control" rows="18"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">关闭</button>
                <button type="button" class="btn btn-primary btn-save">保存</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('.page-title').text('');
        $('.btn-new').click(function() {
            $('.path').val('');
            $('textarea.content').val('');
            $('#_j_menu_edit').modal();
        });
        $('.btn-edit').click(function() {
            $('input').attr('disabled', true);
            var path = $(this).data('path');
            $.get('/rest/ansible/script/' + encodeURI('{$type}|' + path), {}, function(d) {
                if (d.errno == 0) {
                    $('.path').val(path);
                    $('textarea.content').val(d.data);
                    $('input').attr('disabled', false);
                } else {
                    alert('操作失败：' + d.error);
                }
            }, 'json');
            $('#_j_menu_edit').modal();
        });
        $('.btn-save').click(function() {
            if ($('.path').val() == '') {
                alert('文件名必须填写!');
                return false;
            }
            $.post('/rest/ansible/script/', {
                update: {
                    path: '{$type}',
                    file: $('.path').val(),
                    data: $('textarea.content').val()
                }
            }, function(d) {
                window.location.reload();
                //$('#_j_menu_edit').modal('hide');
            }, 'json');
        });
    });
</script>
