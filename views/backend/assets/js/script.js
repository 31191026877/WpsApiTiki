var editor = {};

function show_message(text, icon) { $.toast({ heading: "Thông Báo", text: text, position: 'bottom-center', icon: icon, hideAfter: 5000, }); }

function isset($element) { if (typeof $element != 'undefined') return true; else return false; }

function str_replace(str, key_search, key_replace) { return str.replace(key_search, key_replace); }

function setCookie(cname, cvalue, exdays) {
    let d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    let name = cname + "="; let decodedCookie = decodeURIComponent(document.cookie); let ca = decodedCookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') { c = c.substring(1); }
        if (c.indexOf(name) == 0) { return c.substring(name.length, c.length); }
    }
    return "";
}

function delCookie(name) {
    document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

function rangeSlider() {
    let slider = $('.range-slider'), range = $('.range-slider__range'), value = $('.range-slider__value');

    slider.each(function () {

        value.each(function () {
            var value = $(this).prev().attr('value');
            if (value.length == 0) value = 0;
            $(this).html(value);
        });

        range.on('input', function () {
            $(this).next(value).html(this.value);
            $(this).trigger('change');
        });
    });
}

function render(props) {
    return function(tok, i) {
        return (i % 2) ? props[tok] : tok;
    };
}

function uniqid(a = "", b = false) {
    const c = Date.now()/1000;
    let d = c.toString(16).split(".").join("");
    while(d.length < 14) d += "0";
    let e = "";
    if(b){
        e = ".";
        e += Math.round(Math.random()*100000000);
    }
    return a + d + e;
}

function hasScrollbar() {
    // The Modern solution
    if (typeof window.innerWidth === 'number')
        return window.innerWidth > document.documentElement.clientWidth

    // rootElem for quirksmode
    let rootElem = document.documentElement || document.body

    // Check overflow style property on body for fauxscrollbars
    let overflowStyle

    if (typeof rootElem.currentStyle !== 'undefined')
        overflowStyle = rootElem.currentStyle.overflow

    overflowStyle = overflowStyle || window.getComputedStyle(rootElem, '').overflow

    // Also need to check the Y axis overflow
    let overflowYStyle

    if (typeof rootElem.currentStyle !== 'undefined')
        overflowYStyle = rootElem.currentStyle.overflowY

    overflowYStyle = overflowYStyle || window.getComputedStyle(rootElem, '').overflowY

    let contentOverflows = rootElem.scrollHeight > rootElem.clientHeight
    let overflowShown    = /^(visible|auto)$/.test(overflowStyle) || /^(visible|auto)$/.test(overflowYStyle)
    let alwaysShowScroll = overflowStyle === 'scroll' || overflowYStyle === 'scroll'

    return (contentOverflows && overflowShown) || (alwaysShowScroll)
}

$(function () {
    $.ajaxSetup({
        beforeSend: function (xhr, settings) {
            settings.data += '&csrf_test_name=' + encodeURIComponent(getCookie('csrf_cookie_name'));
            if (settings.data.indexOf('post_type') === -1 && typeof postType !== null) {
                settings.data += '&post_type=' + postType;
            }
            if (settings.data.indexOf('cate_type') === -1 && typeof cateType !== null) {
                settings.data += '&cate_type=' + cateType;
            }
        }
    });

    let sidebarHeight = $('#adminmenuwrap').height() + 100;

    if(sidebarHeight >  $('.page-content .page-body').height()) {
        $('.page-content .page-body').css('min-height', sidebarHeight+'px');
    }

    setTimeout(function () {
        if(hasScrollbar()) {
            let formLeft = $('form .col-form-left');
            let boxFixed = formLeft.find('.box-fix');
            boxFixed.addClass('fixed');
            boxFixed.css('width', formLeft.width()+'px');
        }
    }, 600);



    //$('.nav.nav-tabs a[role="role"]').tab('show');

    document.addEventListener("keydown", function(event) {
        let keyCode = event.keyCode || event.which;
        //F6
        if(keyCode === 117) {
            event.preventDefault();
            window.location = base + 'system';
            return false;
        }
        //F7
        if(keyCode === 118) {
            event.preventDefault();
            window.location = base + 'theme/option';
            return false;
        }
        //F8
        if(keyCode === 119) {
            event.preventDefault();
            window.location = base + 'theme/widgets';
            return false;
        }

        //ctrl + B
        if(keyCode === 66) {
            if(event.ctrlKey) {
                let button = $('.action-bar .pull-right .fa-reply').closest('a');
                let url = button.attr('href');
                if(typeof url != 'undefined' && url.length != 0) {
                    event.preventDefault();
                    window.location = url;
                    return false;
                }
            }
        }
        //Ctrl + H
        if(keyCode === 72) {
            if(event.ctrlKey) {
                event.preventDefault();
                $('#js_hot_key_model').modal('show');
                return false;
            }
        }
    });

    rangeSlider();

    create_permalink_slug();

    $(document).on('click', '#js_slug__change', function () {

        let slug = $('input#slug').val();

        $('#sample-permalink').html(
            '<span class="default-slug">' + domain +
            '<span id="editable-post-name">' +
            '<input type="text" id="js_slug_input_new" class="form-control" value="' + slug + '" autocomplete="off">' +
            '</span>' +
            '</span>'
        );

        $('#button_edit_slug #js_slug__ok,  #button_edit_slug #js_slug__cancel').show();
        $('#button_edit_slug #js_slug__change').hide();

        return false;
    });

    $(document).on('click', '#js_slug__ok', function () {

        let slug = $('input#js_slug_input_new').val();

        let data = {
            'action'    : 'ajax_admin_slug_create',
            'slug'      : slug,
            'slug_id'   : $('#box_edit_slug #object_id').attr('data-id')
        };

        $jqxhr = $.post(ajax, data, function () { }, 'json');

        $jqxhr.done(function (response) {

            if (response.status == 'success') {

                slug = response.slug;

                $('#sample-permalink').html(
                    '<a class="permalink" target="_blank" href="'+ domain + slug +'">\n' +
                    '<span class="default-slug">'+ domain +'<span id="editable-post-name">'+slug+'</span></span>\n' +
                    '</a>'
                );

                $('#button_edit_slug #js_slug__ok,  #button_edit_slug #js_slug__cancel').hide();

                $('#button_edit_slug #js_slug__change').show();

                $('input#js_slug_input_current').val(slug);

                $('input#slug').val(slug);

                $('#slug').trigger('change');
            }
            else {
                show_message(response.message, response.status);
            }
        });

        $jqxhr.fail(function (data) {

        });

        $jqxhr.always(function (data) {

        });

        return false;
    });

    $(document).on('click', '#js_slug__cancel', function () {

        let slug = $('input#js_slug_input_current').val();

        $('#sample-permalink').html(
            '<a class="permalink" target="_blank" href="'+ domain + slug +'">\n' +
            '<span class="default-slug">'+ domain +'<span id="editable-post-name">'+slug+'</span></span>\n' +
            '</a>'
        );

        $('#button_edit_slug #js_slug__ok,  #button_edit_slug #js_slug__cancel').hide();

        $('#button_edit_slug #js_slug__change').show();

        $('input#js_slug_input_current').val(slug);

        $('input#slug').val(slug);

        return false;
    });

    /*===========================================
    * theme
    * ===========================================*/
    //Cập nhật các trạng thái có kiểu boolean
    $('input#select_all').on('ifChecked', function (event) {
        $('.select').iCheck('check');
    });
    $('.check-column input.select').on('ifUnchecked', function (event) {
        $('input#select_all').iCheck('uncheck');
        let checked = false;
        $('.check-column input.select').each(function () {
            if($(this).is(':checked')) { checked = true; return false; }
        });
        if(checked == false) {
            $('.box-heading .js_btn_confirm').hide();
        }
    });
    $('.check-column input.select').on('ifChecked', function (event) {
        let checkedAll = true;
        $('.check-column input.select').each(function () {
            if(!$(this).is(':checked')) {
                checkedAll = false;
                return false;
            }
        });
        if(checkedAll == true) $('input#select_all').iCheck('check');
        $('.box-heading .js_btn_confirm').show();
    });
    $('input#select_all').on('ifUnchecked', function (event) {
        let checkedAll = true;
        $('.check-column input.select').each(function () {
            if(!$(this).is(':checked')) {
                checkedAll = false;
                return false;
            }
        });
        if(checkedAll == true) $('.check-column input.select').iCheck('uncheck');
    });

    //tooltip boostrap
    $('[data-toggle="tooltip"]').tooltip();

    //<![CDATA[
    var Nanobar = function () {
        var c, d, e, f, g, h, k = { width: "100%", height: "3px", zIndex: 9999, top: "0" }, l = { width: 0, height: "100%", clear: "both", transition: "height .3s" }; c = function (a, b) { for (var c in b) a.style[c] = b[c]; a.style["float"] = "left" }; f = function () { var a = this, b = this.width - this.here; 0.1 > b && -0.1 < b ? (g.call(this, this.here), this.moving = !1, 100 == this.width && (this.el.style.height = 0, setTimeout(function () { a.cont.el.removeChild(a.el) }, 100))) : (g.call(this, this.width - b / 4), setTimeout(function () { a.go() }, 16)) }; g = function (a) {
        this.width =
            a; this.el.style.width = this.width + "%"
        }; h = function () { var a = new d(this); this.bars.unshift(a) }; d = function (a) { this.el = document.createElement("div"); this.el.style.backgroundColor = a.opts.bg; this.here = this.width = 0; this.moving = !1; this.cont = a; c(this.el, l); a.el.appendChild(this.el) }; d.prototype.go = function (a) { a ? (this.here = a, this.moving || (this.moving = !0, f.call(this))) : this.moving && f.call(this) }; e = function (a) {
            a = this.opts = a || {}; var b; a.bg = a.bg || "#2980B9"; this.bars = []; b = this.el = document.createElement("div"); c(this.el,
                k); a.id && (b.id = a.id); b.style.position = a.target ? "relative" : "fixed"; a.target ? a.target.insertBefore(b, a.target.firstChild) : document.getElementsByTagName("body")[0].appendChild(b); h.call(this)
        }; e.prototype.go = function (a) { this.bars[0].go(a); 100 == a && h.call(this) }; return e
    }();
    var nanobar = new Nanobar(); nanobar.go(30); nanobar.go(60); nanobar.go(100);
    //]]>
    //upload file
    $('.iframe-btn').fancybox({
        'type': 'iframe',
        animationEffect: "zoom",
        transitionEffect: "zoom-in-out",
    });

    $().fancybox({selector: '[data-fancybox="iframe"]',});

    $('.mobile-nav').click(function () { $('#adminmenumain').toggleClass('open-nv'); return false; })

    $(document).on('click', '.toast .toast__close', function (e) {
        $(this).closest('.toast').hide('slow');
    });
});

$(document).on('focusin', function (e) { if ($(e.target).closest(".mce-window").length) { e.stopImmediatePropagation(); } });

function create_permalink_slug() {

    if(isset('#box_slug') && isset('input#slug') && typeof object_id != 'undefined') {

        let slug = $('input#slug').val();

        if(typeof slug == 'undefined') return false;

        $('#box_slug').remove();

        let str = '<div class="col-md-12" id="box_edit_slug">\n' +
            '    <label class="control-label required" for="current-slug" aria-required="true">Đường dẫn:</label>\n' +
            '    <span id="sample-permalink">\n' +
            '        <a class="permalink" target="_blank" href="'+ domain + slug +'">\n' +
            '            <span class="default-slug">'+ domain +'<span id="editable-post-name">'+slug+'</span></span>\n' +
            '        </a>\n' +
            '    </span>\n' +
            '    ‎<span id="button_edit_slug">\n' +
            '        <button type="button" class="btn" id="js_slug__change"><i class="fad fa-pencil"></i></button>\n' +
            '        <button type="button" class="btn" id="js_slug__ok">OK</button>\n' +
            '        <button type="button" class="btn" id="js_slug__cancel">Cancel</button>\n' +
            '    </span>\n' +
            '    <div id="object_id" data-id="'+object_id+'"></div>\n' +
            '    <input type="hidden" id="js_slug_input_current" value="'+slug+'">\n' +
            '    <input type="hidden" id="slug" name="slug" value="'+slug+'">\n' +
            '</div>';

        if(isset('#box_'+language+'_title')) {

            $('#box_'+language+'_title').after(str);
        }

        if(isset('#box_'+language+'_name')) {

            $('#box_'+language+'_name').after(str);
        }
    }
}
//format number
var inputnumber = 'Giá trị nhập vào không phải là số';

function FormatNumber(str) {
    var strTemp = GetNumber(str);
    if (strTemp.length <= 3)
        return strTemp;
    strResult = "";
    for (var i = 0; i < strTemp.length; i++)
        strTemp = strTemp.replace(",", "");
    var m = strTemp.lastIndexOf(".");
    if (m == -1) {
        for (var i = strTemp.length; i >= 0; i--) {
            if (strResult.length > 0 && (strTemp.length - i - 1) % 3 == 0)
                strResult = "," + strResult;
            strResult = strTemp.substring(i, i + 1) + strResult;
        }
    } else {
        var strphannguyen = strTemp.substring(0, strTemp.lastIndexOf("."));
        var strphanthapphan = strTemp.substring(strTemp.lastIndexOf("."),
            strTemp.length);
        var tam = 0;
        for (var i = strphannguyen.length; i >= 0; i--) {

            if (strResult.length > 0 && tam == 4) {
                strResult = "," + strResult;
                tam = 1;
            }

            strResult = strphannguyen.substring(i, i + 1) + strResult;
            tam = tam + 1;
        }
        strResult = strResult + strphanthapphan;
    }
    return strResult;
}

function GetNumber(str) {
    var count = 0;
    for (var i = 0; i < str.length; i++) {
        var temp = str.substring(i, i + 1);
        if (!(temp == "," || temp == "." || (temp >= 0 && temp <= 9))) {
            alert(inputnumber);
            return str.substring(0, i);
        }
        if (temp == " ")
            return str.substring(0, i);
        if (temp == ".") {
            if (count > 0)
                return str.substring(0, ipubl_date);
            count++;
        }
    }
    return str;
}

function IsNumberInt(str) {
    for (var i = 0; i < str.length; i++) {
        var temp = str.substring(i, i + 1);
        if (!(temp == "." || (temp >= 0 && temp <= 9))) {
            alert(inputnumber);
            return str.substring(0, i);
        }
        if (temp == ",") {
            return str.substring(0, i);
        }
    }
    return str;
}
