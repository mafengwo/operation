<link href="http://{$IMG_DOMAIN}/css/plugins/select2/select2.css" rel="stylesheet" type="text/css" />
<link href="http://{$IMG_DOMAIN}/css/plugins/select2/select2-bootstrap.css" rel="stylesheet" type="text/css" />
<link href="http://{$IMG_DOMAIN}/css/plugins/jquery.nestable.css" rel="stylesheet">
<style type="text/css">
    .dd-content {
        height: 40px;
        font-size: 14px;
        line-height: 29px;
    }
</style>
<div class="box-header">
    <a href="javascript:void(0);" class="btn btn-default" id="_j_menu_append">新增节点</a>
</div>
<div class="box-body nodes-list">
    <ol class="dd-list">
        {foreach $nodes as $s0}
        <li class="dd-item" data-id="{$s0.id}">
            <div class="dd-content {if $s0.hidden}bg-gray{/if}"><span class="dd-view">{$s0.text}</span><span class="dd-op"><a class="dd-add" href="javascript:void(0);">新增</a><a class="dd-edit" href="#" data-toggle="modal">编辑</a><a class="dd-delete" href="javascript:void(0);">删除</a><a class="dd-privacy" href="javascript:void(0);">授权</a></span></div>
            {if count($s0.sub)}
            <ol class="dd-list">
                {foreach $s0.sub as $s1}
                <li class="dd-item" data-id="{$s1.id}">
                    <div class="dd-content {if $s1.hidden}bg-gray{/if}"><span class="dd-view">{$s1.text}</span><span class="dd-op"><a class="dd-add" href="javascript:void(0);">新增</a><a class="dd-edit" href="#" data-toggle="modal">编辑</a><a class="dd-delete" href="javascript:void(0);">删除</a><a class="dd-privacy" href="javascript:void(0);">授权</a></span></div>
                    {if count($s1.sub)}
                    <ol class="dd-list">
                        {foreach $s1.sub as $s2}
                        <li class="dd-item" data-id="{$s2.id}">
                            <div class="dd-content {if $s2.hidden}bg-gray{/if}"><span class="dd-view">{$s2.text}</span><span class="dd-op"><a class="dd-add" href="javascript:void(0);">新增</a><a class="dd-edit" href="#" data-toggle="modal">编辑</a><a class="dd-delete" href="javascript:void(0);">删除</a><a class="dd-privacy" href="javascript:void(0);">授权</a></span></div>
                            {if count($s2.sub)}
                            <ol class="dd-list">
                                {foreach $s2.sub as $s3}
                                <li class="dd-item" data-id="{$s3.id}">
                                    <div class="dd-content {if $s3.hidden}bg-gray{/if}"><span class="dd-view">{$s3.text}</span><span class="dd-op"><a class="dd-edit" href="javascript:void(0);" data-toggle="modal">编辑</a><a class="dd-delete" href="javascript:void(0);">删除</a><a class="dd-privacy" href="javascript:void(0);">授权</a></span></div>
                                </li>
                                {/foreach}
                            </ol>
                            {/if}
                        </li>
                        {/foreach}
                    </ol>
                    {/if}
                </li>
                {/foreach}
            </ol>
            {/if}
        </li>
        {/foreach}
    </ol>

</div>

<div class="modal fade" tabindex="-1" id="_j_menu_edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h3>添加/编辑节点信息</h3>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_id">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">标题</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="" class="form-control valid-data w-40p number f-inline" data-field="text">
                            <span class="help-block f-inline">菜单中的显示名称</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">归属关系</label>
                        <div class="col-sm-9">
                            <select class="form-control ipt-parent valid-data" data-field="parentid">
                                <option value="0">0. 根节点</option>
                                {assign var="nc_0" value="0"} {foreach $nodes as $n0} {assign var="nc_0" value=$nc_0+1}
                                <option value="{$n0.id}">{$nc_0}. {$n0.text}</option>
                                {assign var="nc_1" value="0"} {foreach $n0.sub as $n1} {assign var="nc_1" value=$nc_1+1}
                                <option value="{$n1.id}">{$nc_0}.{$nc_1} {$n1.text}</option>
                                {assign var="nc_2" value="0"} {foreach $n1.sub as $n2} {assign var="nc_2" value=$nc_2+1}
                                <option value="{$n2.id}">{$nc_0}.{$nc_1}.{$nc_2} {$n2.text}</option>
                                {assign var="nc_3" value="0"} {foreach $n2.sub as $n3} {assign var="nc_3" value=$nc_3+1}
                                <option value="{$n3.id}">{$nc_0}.{$nc_1}.{$nc_2}.{$nc_3} {$n3.text}</option>
                                {/foreach} {/foreach} {/foreach} {/foreach}
                            </select>
                            <span class="help-block f-inline">请选择该节点的上级节点</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">链接地址</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="" class="form-control valid-data w-40p number f-inline" data-field="url">
                            <span class="help-block f-inline">程序的访问路径("/"开头,无需填写域名部分)</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">可见性</label>
                        <div class="col-sm-9">
                            <select class="form-control valid-data ipt-mode" data-field="mode">
                                <option value="0">显示</option>
                                <option value="1">隐藏</option>
                            </select>
                            <span class="help-block f-inline">该节点是否要在左侧菜单中显示</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">取消</button>
                <button type="button" class="btn btn-primary btn-save-nodes">保存</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="_j_menu_privacy">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h3>编辑节点权限</h3>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-4">
                            将『<span id="text_privacy_menu"></span>』以及子节点的操作权限开放给：
                        </div>
                        <div class="col-sm-8">
                            <select class="form-control" id="admin_uid" style="width:300px;">
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
                <button type="button" class="btn btn-primary btn-save-privacy">确定</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="http://{$IMG_DOMAIN}/js/plugins/select2/select2.js"></script>
<script type="text/javascript">
    $(function() {
      $.fn.modal.Constructor.prototype.enforceFocus = function() { }; //fix bug at select2 4.x in modal use
      $('#admin_uid').select2({
          theme: 'bootstrap',
          placeholder: '选择用户'
      });

        $('#_j_menu_append').on('click', function() {
            $('#_j_menu_edit .form-horizontal .valid-data:text').val('');
            $('.ipt-parent').val(0);
            $('.ipt-mode').val(0);
            $('#_j_menu_edit').modal();
        });

        //保存节点数据
        $('.btn-save-nodes').click(function() {
          var update = {
              id: $('#edit_id').val()
          };
          $('#_j_menu_edit .form-horizontal .valid-data:input').each(function() {
              var self = $(this),
                  field = self.data('field');
              update[field] = $.trim(self.val());
          });
          $('.btn-save-nodes').addClass('disabled');
          $.post('/rest/system/nodes/', {
              'method': 'POST',
              'update': update
          }, function(data, status) {
              if (data.errno) {
                  alert('操作失败：'+data.error);
              } else {
                  $('#_j_menu_edit').modal('hide');
                  window.location.reload();
              }
              $('.btn-save-nodes').removeClass('disabled');
          }, 'json');
        });

        //增加用户权限
        $('.btn-save-privacy').click(function(){
            var admin_uid = $('#admin_uid').val(),
                node_id = $('#admin_uid').data('id');
            if(admin_uid && node_id){
              $('.btn-save-privacy').addClass('disabled');
              $.post('/rest/system/privacy/', {
                  'method': 'POST',
                  'post_style': 'user',
                  'update': {
                    admin_uid: admin_uid,
                    node_id: node_id
                  }
              }, function(data, status) {
                  if (data.errno) {
                      alert('操作失败：'+data.error);
                  } else {
                      $('#_j_menu_privacy').modal('hide');
                      alert('权限添加成功');
                  }
                  $('.btn-save-privacy').removeClass('disabled');
              }, 'json');
            }
        });

        $('.nodes-list')
            .delegate('.dd-add', 'click', function() {
                var item = $(this).closest('li');
                var parentId = item.data('id');
                $('#_j_menu_edit .form-horizontal .valid-data:text').val('');
                $('.ipt-mode').val(0);
                $('.ipt-parent').val(parentId);
                $('#_j_menu_edit').modal();
            })
            .delegate('.dd-edit', 'click', function() {
                var item = $(this).closest('li');
                var id = item.data('id');
                $.getJSON('/rest/system/nodes/'+id, { }, function(d) {
                    if(d.errno == 0) {
                      $('#edit_id').val(id);
                      $('#_j_menu_edit .form-horizontal .valid-data:input').each(function() {
                        var self = $(this),
                            field = self.data('field');
                        if (typeof(d.data[field]) != 'undefined') {
                            self.val(d.data[field]);
                        } else self.val('');
                      });
                      $('#_j_menu_edit').modal('show');
                    } else {
                      alert('操作失败：'+d.error);
                    }
                });
            })
            .delegate('.dd-privacy', 'click', function() {
                var item = $(this).closest('li');
                var id = item.data('id');
                $('#text_privacy_menu').text(item.find('.dd-content:eq(0)>.dd-view').text());
                $('#admin_uid').data('id',id).val('').trigger('change');
                $('#_j_menu_privacy').modal();
            })
            .delegate('.dd-delete', 'click', function(e) {
                e.preventDefault();
                var self = $(this),
                    item = self.closest('li'),
                    id = item.data('id');
                if (self.hasClass('disabled')) {
                    return false;
                }
                if (!confirm('确定删除该项？')) {
                    return false;
                }
                self.addClass('disabled');
                $.post('/rest/system/nodes/' + id, {
                    method: 'DELETE'
                }, function(data) {
                    if (data.errno == 0) {
                        alert('删除成功');
                        window.location.reload();
                    } else {
                        alert('操作失败：'+data.error);
                    }
                    self.removeClass('disabled');
                });
            });
    });
</script>
