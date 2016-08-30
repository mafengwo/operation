$(function() {

    //alert for system
    window.alert = function(msg) {
        $('.alert-box p').empty().append(msg);
        layer.open({
            type: 1,
            shade: true,
            title: false,
            closeBtn: 0,
            content: $('.alert-box'),
            success: function(layero, index) {
                $('.btn-layer-close').click(function() {
                    layer.close(index);
                })
            }
        });
    }

    //confirm for system
    window.lconfirm = function(msg, ok_fun, err_fun) {
        index = layer.confirm(msg, {
            btn: ['确定', '取消'] //按钮
        }, function() {
            typeof ok_fun == 'function' && ok_fun();
            layer.close(index);
        }, function() {
            typeof err_fun == 'function' && err_fun();
            layer.close(index);
        });
    }

    /** skin begin **/
    var my_skins = [
        "skin-blue",
        "skin-black",
        "skin-red",
        "skin-yellow",
        "skin-purple",
        "skin-green",
        "skin-blue-light",
        "skin-black-light",
        "skin-red-light",
        "skin-yellow-light",
        "skin-purple-light",
        "skin-green-light"
    ];

    function change_skin(cls) {
        $.each(my_skins, function(i) {
            $("body").removeClass(my_skins[i]);
        });

        $("body").addClass(cls);
        //store('skin', cls);
        return false;
    }

    function get(name) {
        if (typeof(Storage) !== "undefined") {
            return localStorage.getItem(name);
        } else {
            window.alert('Please use a modern browser to properly view this template!');
        }
    }

    var skin = get('skin');
    skin && change_skin(skin);
    /** skin end **/

    /** auto lock code begin **/
    var timer, lock_time = 3600,
        idle_time = 0,
        auto_start = true;

    function timeStart() {
        idle_time = idle_time + 1;
        //console.log(idle_time);
        if (idle_time >= lock_time) {
            auto_start = false;
            var back = encodeURIComponent(window.location.href);
            window.location.href = '/system/user/lockscreen?back=' + back;
        } else timer = setTimeout(timeStart, 1000);
    }

    function timeStop() {
        //console.log('clear timer');
        timer && clearTimeout(timer);
        idle_time = 0;
        auto_start && timeStart()
    }

    $('body').mousedown(timeStop);
    $('body').mousemove(timeStop);
    $('body').keydown(timeStop);
    $('body').mouseup(timeStop);
    $('body').keyup(timeStop);

    timeStart();
    /** auto lock code end **/
});
