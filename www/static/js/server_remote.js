//关闭变量框
function close_var_form() {
    $('#vars').empty();
    $('.var-box').hide();
}

//渲染变量框
function fill_vars_form(data) {
    var target = $('#vars'),
        aRow = $('<div class="row"/>'),
        rowInstance;
    target.empty();
    for (var i in data) {
        if (!rowInstance) rowInstance = aRow.clone();
        rowInstance.append('<div class="col-md-1 col-xs-4 h-40"><span class="pull-right">' +
            data[i] + '</span></div><div class="col-md-2 col-xs-8 h-40"><input type="text" ' +
            'placeholder="" class="form-control pb-vars" data-field="' + data[i] + '"></div>');
        if (rowInstance.find('.col-md-1').length == 4) {
            target.append(rowInstance);
            rowInstance = null;
        }
    }
    if (rowInstance) target.append(rowInstance);
    $('.var-box').show();
}

//检查变量框
function checek_var_form() {
    var data = {
            'rc': 0,
            'vars': {}
        },
        target = $('.pb-vars');
    if (target.length) {
        $('.pb-vars').each(function() {
            var self = $(this),
                key = self.data('field');
            if (self.val() == '') {
                if (key) data.vars[key] = '';
                /*
                  //暂时屏蔽对变量的非空判读
                  alert('变量 ' + self.data('field') + ' 还未填写，请将变量赋值表格填写完整');
                  data.rc = 1; //check失败标识
                  return false;
                */
            } else {
                if (key) data.vars[key] = self.val();
            }
        });
    }
    return data;
}

//自动填写变量
function auto_fill_form() {
    var cmd = $('select.ipt-command').val(),
        ip = $('.ipt-ip').val();
    if (cmd && ip) {
        $.get('/rest/ansible/parameter/' + cmd + '|' + ip, {
            data_style: 'autofill'
        }, function(d) {
            if (d.errno == 0) {
                $('.pb-vars').each(function() {
                    var self = $(this),
                        key = self.data('field'),
                        val = d.data[key];
                    if (val) {
                        var vals = val.split('|');
                        if (vals.length == 1) {
                            self.val(vals[0])
                        } else {
                            var select = $('<select class="form-control pb-vars"/>').data('field', key);
                            for (var i in vals) {
                                select.append('<option value="' + vals[i] + '">' + vals[i] + '</option>')
                            }
                            self.after(select);
                            self.remove();
                        }
                    }
                });
            } else {
                $('.pb-vars').each(function() {
                    var self = $(this);
                    if (self[0].localName == 'select') {
                        self.after('<input type="text" class="form-control pb-vars" data-field="' + self.data('field') + '">').remove();
                    }
                    self.val('');
                });
            }
        }, 'json');
    }
}

//远程操作返回数据解析工具
var ServerRemote = {
    //解析错误
    parseError: function(XMLHttpRequest, textStatus, errorThrown) {
        $('#loading').hide();
        $('.btn-send').text('Run').attr('disabled', false);
        //console.log(XMLHttpRequest)
        $('.result').append('<h4>API严重错误 ' + textStatus + ': ' +
            errorThrown + '</h4><div class="wellx well-sm alert-danger">' +
            XMLHttpRequest.responseText + '</div>');
        $('#reply_host_num').text('0');
    },
    parseResult: function(response) {
        $('.btn-send').text('Run').attr('disabled', false);
        if (response.errno == 0) {
            if (response.data.after.length) {
                var html = $('<div/>'),
                    one = $('#item').clone().removeAttr('id'),
                    counter = {
                        succ: 0,
                        failed: 0
                    };
                for (k in response.data.after) {
                    var data = response.data.after[k],
                        line_num = 0,
                        div = $('<div/>');
                    div.append('<h4 class="alert-heading">' + data.ip + '</h4>');
                    if (data.detail) {
                        var tmp_counter = 0;
                        for (var i in data.detail) {
                            var line = data.detail[i];
                            if (line.rc >= 0) {
                                line_num++;
                                tmp_counter += line.rc;
                                var cls = line.rc == 0 ? 'alert-success' : 'alert-danger';
                                var div_cls = line.rc == 0 ? 'div-succ' : 'div-failed';
                                var cmd = line.cmd == '' ? '' : '<p>'+$('#logininfo').data('username')+'@AOS$ <i>' + line.cmd + '</i></p>';
                                var muiltout = line.muiltout == 1 ? '<br>' : '';
                                if (line.out == '' && line.changed == 1) line.out = '[Success]';
                                if (line.unreachable) line.out = line.msg;
                                $('<div class="wellx well-sm ' + cls + '"><p class="task-name">' + line.task + '</p>' + cmd +
                                    '<p>'+$('#logininfo').data('username')+'@AOS$ <span class="output">' + muiltout +
                                    line.out.replace(/\n/g, '<br>') + '</span></p></div>').appendTo(div);
                                one.find('.box-body').append(div);
                                div.addClass(div_cls);
                            }
                        }
                        tmp_counter == 0 ? counter.succ += 1 : counter.failed += 1;
                    }
                    if (line_num == 0) {
                        var cls = 'alert-danger';
                        if (data.rc == 0) {
                            counter.succ += 1;
                            cls = 'alert-success';
                        } else {
                            counter.failed += 1;
                        }
                        var output = data.ret ? data.ret.replace(/\n/g, '<br>') : '[NO OUTPUT]';
                        $('<div class="wellx well-sm ' + cls + '"><p>'+$('#logininfo').data('username')+'@AOS$ <span class="output">' +
                            output + '</span></p></div>').appendTo(one);
                    }
                    html.append(one);
                    one.addClass('in');
                }

                $('#loading').hide();
                $('.result').append(html);
                $('#reply_host_num').text(response.data.after.length).bind('click',function(){
                    $('.box-body div.div-succ').show();
                    $('.box-body div.div-failed').show();
                });
                $('#host_num_succ').text(counter.succ).bind('click',function(){
                    $('.box-body div.div-succ').show();
                    $('.box-body div.div-failed').hide();
                });
                $('#host_num_failed').text(counter.failed).bind('click',function(){
                    $('.box-body div.div-succ').hide();
                    $('.box-body div.div-failed').show();
                });
            } else {
                $('.result').append('<h4>接口暂时不可用，请稍后再试！</h4>');
                $('#reply_host_num').text('0');
                $('#host_num_succ').text('0');
                $('#host_num_failed').text('0');
            }
        } else {
            $('#loading').hide();
            alert('操作失败：' + response.error);
        }
    }
}
