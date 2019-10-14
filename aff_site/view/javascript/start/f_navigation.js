var nav_status = 'close';
var nav_fixed_status = 'no';

function f_nav_move(click_source) {
    if (click_source == 'li') {
        if (nav_fixed_status == 'yes') {
            return;
        }
    }
    var stop_move;
    if (nav_status == 'open') {
        nav_status = 'close';
        stop_move = -150;
    } else {
        nav_status = 'open';
        stop_move = 0;
    }
    var nav_left = parseInt(document.getElementById("el_nav").offsetLeft);
    var nav_interval_nav = setInterval(f_nav_move_interval, 1);

    function f_nav_move_interval() {
        if (nav_left == stop_move) {
            clearInterval(nav_interval_nav);
        } else {
            if (nav_status == 'open') {
                nav_left = nav_left + 2;
            } else {
                nav_left = nav_left - 2;
            }
            document.getElementById("el_nav").style.left = nav_left + 'px';
        }
    }
}

function f_nav_fixed() {
    if (nav_fixed_status == 'no') {
        document.getElementById('el_nav_svg_1').style.display = 'none';
        document.getElementById('el_nav_svg_2').style.display = 'block';
        nav_fixed_status = 'yes';
        f_nav_fixed_adjust(150);
    } else {
        document.getElementById('el_nav_svg_1').style.display = 'block';
        document.getElementById('el_nav_svg_2').style.display = 'none';
        nav_fixed_status = 'no';
        f_nav_fixed_adjust(0);
        f_nav_move('div');
    }
}

function f_nav_fixed_adjust(left_new) {
    var nav_left = parseInt(document.getElementById("el_article").offsetLeft);
    var nav_interval_article = setInterval(f_nav_fixed_adjust_interval, 1);
    var width_new;

    function f_nav_fixed_adjust_interval() {
        if (nav_left == left_new) {
            clearInterval(nav_interval_article);
        } else {
            if (left_new > 0) {
                nav_left = nav_left + 2;
            } else {
                nav_left = nav_left - 2;
            }
            document.getElementById("el_article").style.left = nav_left + 'px';
            width_new = nav_left + 20;
            document.getElementById("el_article").style.width = '-webkit-calc(100% - ' + width_new + 'px)';
            document.getElementById("el_article").style.width = '-moz-calc(100% - ' + width_new + 'px)';
            document.getElementById("el_article").style.width = '-calc(100% - ' + width_new + 'px)';
        }
    }
}

function f_nav_open_close(nr) {
    if (document.getElementById('ul_' + nr).style.display == 'none') {
        document.getElementById('ul_' + nr).style.display = 'block';
    } else {
        document.getElementById('ul_' + nr).style.display = 'none';
    }
}
