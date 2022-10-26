/*===========================================
* Input type repeater
* ===========================================*/
$(function () {
    $(document).on('click', '.js_repeater_btn__add', function () {
        let id = $(this).data('id');
        let items = [{id: uniqid()}];
        let template = $('#template_' + id).html().split(/\$\{(.+?)\}/g);
        $('#' + id).append(items.map(function(item) {
            return template.map(render(item)).join('');
        }));
        formBuilderReset();
        return false;
    });

    $(document).on('click', '.js_repeater_btn__delete', function () {
        $(this).closest('.store_wg_item').remove();
        return false;
    });
});

/*===========================================
* Input type radio, checkbox
* ===========================================*/
function checkbox_style() { $('input.icheck').iCheck({ checkboxClass: 'icheckbox_square-blue', radioClass: 'iradio_square-blue', increaseArea: '20%' }); }

$(function () {
    checkbox_style();
});

/*===========================================
* Input type switch
* ===========================================*/
$(function () {
    $(document).on('change', 'input.switch', function (e) {
        let dataTrue = $(this).data('true');
        let dataFalse = $(this).data('false');
        $(e.currentTarget).closest('.toggleWrapper').find('input.switch-value').val((this.checked ? dataTrue : dataFalse));
    });
});

/*===========================================
* Input type code
* ===========================================*/
function code($element, $lang = 'css') {
    editor[$element.name] = CodeMirror.fromTextArea($element, {
        mode: $lang,
        theme: 'darkpastel',
        extraKeys: {
            "Ctrl-Space": "autocomplete",
            "Ctrl-F": "findPersistent",
            "Ctrl-S": function () {
                alert("save");
                return false;
            },
            "F11": function (cm) {
                cm.setOption("fullScreen", !cm.getOption("fullScreen"));
            },
            "Esc": function (cm) {
                if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
            }
        },
        onKeyEvent: function (e, s) {
            console.log(s);
        },
        tabSize: 2,
        lineNumbers: true,
        lineWrapping: true,
        styleActiveLine: true,
        styleSelectedText: true,
        matchBrackets: true,
        autoCloseBrackets: true,
    });
}

$(function () {
    //code
    let codeCss = document.getElementsByClassName("code-css");
    for (let index = 0; index < codeCss.length; index++) { code(codeCss[index], 'css'); }

    let codeJavascript = document.getElementsByClassName("code-javascript");
    for (let index = 0; index < codeJavascript.length; index++) { code(codeJavascript[index], 'javascript'); }

    let codeHtml = document.getElementsByClassName("code-html");
    for (let index = 0; index < codeHtml.length; index++) { code(codeHtml[index], 'html'); }

    let codePhp = document.getElementsByClassName("code-php");
    for (let index = 0; index < codePhp.length; index++) { code(codePhp[index], 'php'); }
});

/*===========================================
* Input type img, file, tab
* ===========================================*/
function load_image_review() {
    $.each($('input[type="images"]'), function (index, value) { image_review($(this)); });
    $.each($('.fileupload-image input'), function (index, value) {
        image_review($(this));
    });
}

function image_review($this = null) {

    let field_id = $this.attr('id');

    if ($this.val().length > 0) {

        let url = $this.val();

        if (url.search('http') === -1 || url.search(domain) !== -1) {
            url = str_replace(url, domain + 'uploads/source/', '');
            url = str_replace(url, 'uploads/source/', '');
            url = domain + 'uploads/source/' + url;
        }

        if (url.length > 0) {
            let tmpImg = new Image();
            let width = 0;
            let height = 0;
            tmpImg.src = url;
            tmpImg.onload = function () {
                width = tmpImg.Width;
                height = tmpImg.Height;
            };
            let str = '';
            let fileNameIndex = url.lastIndexOf("/") + 1;
            let filename = url.substr(fileNameIndex);
            if (isset($this.closest('.group').find('.result-img').html())) {
                $this.closest('.group').find('.result-img').attr('src', url);
                $this.closest('.group').find('.result-img-info').html(str);
            }
            else {
                $this.closest('.group').append('<div class="pull-left text-center" style="width:150px;"><img class="result-img" src="' + url + '" style="max-width:150px;margin:10px 0;"></div>');
                $this.closest('.group').append('<div class="pull-left result-img-info" style="width: calc(100% - 160px);margin:10px 0 0 10px;">' + str + '</div>');
            }
        }
    }
    else {

        // if (isset($this.closest('.group').find('.result-img').html())) {
        //     $this.closest('.group').find('.result-img').remove();
        // }
    }
}

function validateYouTubeUrl(url) {
    if (url != undefined || url != '') {
        var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
        var res = url.match(regExp);
        if (res && res[2].length == 11) {
            return true;
        }
        else {
            return false;
        }
    }
}

function getYoutubeID(url) { var videoid = url.match(/(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?v=|\/)([^\s&]+)/); return videoid[1]; }

function video_review() {
    $.each($('input[type="video"]'), function (index, value) {
        var field_id = $(this).attr('id');
        if ($(this).val().length > 0) {
            var url = 'https://img.youtube.com/vi/' + getYoutubeID($(this).val()) + '/0.jpg';
            if (url.length > 0) {
                if (isset($(this).closest('.form-group').find('.result-img').html())) {
                    $(this).closest('.form-group').find('.result-img').attr('src', url);
                }
                else $(this).closest('.form-group').append('<img class="result-img" src="' + url + '" style="max-width:100%;margin-top:10px;">');
            }
        }
        else {
            if (isset($(this).closest('.form-group').find('.result-img').html())) {
                $(this).closest('.form-group').find('.result-img').remove();
            }
        }
    });
}

function filemanager(id, value, type, win) {
    e = tinymce.activeEditor;
    t = id;
    a = type;
    s = win;

    var r = window.innerWidth - 30,
        g = window.innerHeight - 60;
    if (r > 1800 && (r = 1800), g > 1200 && (g = 1200), r > 600) {
        var d = (r - 20) % 138;
        r = r - d + 10
    }
    urltype = 2, "image" == a && (urltype = 1), "media" == a && (urltype = 3);
    var o = "RESPONSIVE FileManager";
    "undefined" != typeof e.settings.filemanager_title && e.settings.filemanager_title && (o = e.settings.filemanager_title);
    var l = "key";
    "undefined" != typeof e.settings.filemanager_access_key && e.settings.filemanager_access_key && (l = e.settings.filemanager_access_key);
    var f = "";
    "undefined" != typeof e.settings.filemanager_sort_by && e.settings.filemanager_sort_by && (f = "&sort_by=" + e.settings.filemanager_sort_by);
    var m = "false";
    "undefined" != typeof e.settings.filemanager_descending && e.settings.filemanager_descending && (m = e.settings.filemanager_descending);
    var c = "";
    "undefined" != typeof e.settings.filemanager_subfolder && e.settings.filemanager_subfolder && (c = "&fldr=" + e.settings.filemanager_subfolder);
    var v = "";
    "undefined" != typeof e.settings.filemanager_crossdomain && e.settings.filemanager_crossdomain && (v = "&crossdomain=1", window.addEventListener ? window.addEventListener("message", n, !1) : window.attachEvent("onmessage", n)),
        tinymce.activeEditor.windowManager.open({
            title: o,
            file: e.settings.external_filemanager_path + "dialog.php?type=" + urltype + "&descending=" + m + f + c + v + "&lang=" + e.settings.language + "&akey=" + l,
            width: r,
            height: g,
            resizable: !0,
            maximizable: !0,
            inline: 1
        }, {
            setUrl: function (n) {
                //console.log(t);
                var i = s.document.getElementById(t);
                if (i.value = e.convertURL(n), "createEvent" in document) {
                    var a = document.createEvent("HTMLEvents");
                    a.initEvent("change", !1, !0), i.dispatchEvent(a)
                } else i.fireEvent("onchange")
            }
        })
}

function responsive_filemanager_callback(field_id) {
    image_review($('#' + field_id));
}

$(function () {
    load_image_review();

    $(document).on('change', 'input[type=images]', function () {
        image_review($(this));
    });

    $(document).on('change', 'input[type=files]', function () {
        let url = $(this).val();
        if (url !== undefined || url !== '') {

            let regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;

            let match = url.match(regExp);

            if (match && match[2].length === 11) {
                url = 'https://img.youtube.com/vi/' + match[2] + '/0.jpg';
                if (isset($(this).closest('.group').find('.result-img').html())) {
                    $(this).closest('.group').find('.result-img').attr('src', url);
                }
                else {
                    $(this).closest('.group').append('<div class="pull-left text-center" style="width:150px;"><img class="result-img" src="' + url + '" style="max-width:150px;margin:10px 0;"></div>');
                }
            }
            else {
                image_review($(this));
            }
        }
    });
});
/*===========================================
* Input type color
* ===========================================*/
$(function () {
    $('.item-color input').spectrum({
        type: "color",
        showInput: true,
        showInitial: true,
        chooseText: "Chọn", cancelText: "Hủy"
    });
    $('.item-color-hexa input').spectrum({
        type: "component",
        showInput: true,
        showInitial: true,
        chooseText: "Chọn"
    });
});

/*===========================================
* Input type select2-multiple
* ===========================================*/
$(function () {
    $(".select2-multiple").select2();
});

/*===========================================
* Input type tab
* ===========================================*/
function inputTabsAnimation(inputBox, self, animation = true) {

    let indicator = inputBox.find('.indicator');

    const tabIndex = inputBox.find('.tab').index(self);

    let indicatorWidth = inputBox.find('.tab')[tabIndex].getBoundingClientRect().width;

    indicatorWidth = parseFloat(indicatorWidth).toFixed(2);

    indicator.css('transform', 'translate3d(calc(' + (indicatorWidth * tabIndex) + 'px), 0, 0)');

    if(animation == true) {
        indicator.css('width', 'calc(' + indicatorWidth + 'px + 2%)');
    }

    setTimeout(function () {
        indicator.css('width', 'calc(' + (indicatorWidth) + 'px)');
    }, 100)
}

$(function () {
    $('.input-tabs .tab.active').each(function(){
        let inputBox = $(this).closest('.input-tabs');
        inputTabsAnimation(inputBox, $(this));
    });

    $(document).on('click touch', '.input-tabs .tab', function () {

        let inputBox = $(this).closest('.input-tabs');

        inputBox.find('.tab').removeClass('active');

        $(this).addClass('active');

        inputTabsAnimation(inputBox, $(this));
    });

    $(document).on('shown.bs.tab', '.nav-tabs a', function(event){
        $('.input-tabs .tab.active').each(function(){
            let inputBox = $(this).closest('.input-tabs');
            inputTabsAnimation(inputBox, $(this), false);
        });
    });

    $(document).on('shown.bs.collapse', '.collapse', function () {
        $('.input-tabs .tab.active').each(function(){
            let inputBox = $(this).closest('.input-tabs');
            inputTabsAnimation(inputBox, $(this), false);
        });
    })
});

/*===========================================
* Input type wysiwyg & wysiwyg-short
* ===========================================*/
$(function () {
    tinymce_load();
});

/*===========================================
* Input type date & datetime
* ===========================================*/
$(function () {
    $('.datetime').datepicker({language: 'vi'});
});

/*===========================================
* Input type daterange
* ===========================================*/
function daterange() {

    $('input.daterange').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear',
            customRangeLabel: "Tùy chọn",
            daysOfWeek: [
                "CN",
                "Hai",
                "Ba",
                "Tư",
                "Năm",
                "Sáu",
                "Bảy"
            ],
            monthNames: [
                "Tháng 1",
                "Tháng 2",
                "Tháng 3",
                "Tháng 4",
                "Tháng 5",
                "Tháng 6",
                "Tháng 7",
                "Tháng 8",
                "Tháng 9",
                "Tháng 10",
                "Tháng 11",
                "Tháng 12"
            ],
        },
        ranges: {
            'Hôm nay': [moment(), moment()],
            'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            '7 Ngày trước': [moment().subtract(6, 'days'), moment()],
            '30 ngày trước': [moment().subtract(29, 'days'), moment()],
            'Tháng này': [moment().startOf('month'), moment().endOf('month')],
            'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
    });

    $('input.daterange').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });
    $('input.daterange').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
}

$(function () { daterange(); });
/*===========================================
* Input text building
* ===========================================*/
$(function () {
    $(document).on('click', '.js_widget_text_style',function() {
        let box =  $(this).closest('.text-builder-container').find('.text-builder-model');
        box.toggle();
        $('.input-tabs .tab.active').each(function(){
            let inputBox = $(this).closest('.input-tabs');
            inputTabsAnimation(inputBox, $(this));
        });
        return false;
    });
    $(document).on('click', '.js_widget_text_builder_close',function() {
        let box =  $(this).closest('.text-builder-model');
        box.hide();
        return false;
    });
});
/*===========================================
* Input type background
* ===========================================*/
function generatorGradient(box) {

    let gradientTypeValue = box.find('select.gradientType').val();

    let gradientType = (gradientTypeValue === 'radial') ? 'radial-gradient' : 'linear-gradient';

    box.find('.gradientRadialDirection').closest('.form-group').hide();

    let gradientRadialDirection = '';

    if(gradientTypeValue == 'radial') {
        gradientRadialDirection = 'circle at '+box.find('select.gradientRadialDirection1').val();
        box.find('select.gradientRadialDirection1').closest('.form-group').show();
    }
    else {
        gradientRadialDirection = box.find('input.gradientRadialDirection2').val()+'deg';
        box.find('input.gradientRadialDirection2').closest('.form-group').show();
    }

    let color1 = box.find('input.gradientColor1').val() + ' '+ box.find('input.gradientPositionStart').val() +'%';
    let color2 = box.find('input.gradientColor2').val() + ' '+ box.find('input.gradientPositionEnd').val() +'%';
    let css = 'background: ' + gradientType + '(' + gradientRadialDirection + ',' + color1 + ', ' + color2 + ');';
    box.find('.input-background-tab-gradient--review').attr('style', css);
}

$(function () {
    $(document).on('click', '.input-background-tab-navs-item',function() {
        let dataTab =  $(this).data('tab');
        $(this).closest('.input-background-tab-box').find('.input-background-tab-navs .input-background-tab-navs-item').removeClass('active');
        $(this).addClass('active');
        $(this).closest('.input-background-tab-box').find('.input-background-tab-content .input-background-tab').removeClass('active');
        $(this).closest('.input-background-tab-box').find('.input-background-tab-content .input-background-tab-' + dataTab).addClass('active');
        return false;
    });

    $(document).on('change', '.input-background-tab-gradient input, .input-background-tab-gradient select',function() {
        generatorGradient($(this).closest('.input-background-tab-box'));
        return false;
    });

    $('.input-background-tab-box').each(function(){
        generatorGradient($(this));
    });
});

function formBuilderReset() {
    checkbox_style();
    $('.datetime').datepicker({language: 'vi'});
    daterange();
    load_image_review();
    $('.item-color input').spectrum({
        type: "color",
        showInput: true,
        showInitial: true,
        chooseText: "Chọn", cancelText: "Hủy"
    });
    $('.item-color-hexa input').spectrum({
        type: "component",
        showInput: true,
        showInitial: true,
        chooseText: "Chọn"
    });
    $(".select2-multiple").select2();
    $('.input-tabs .tab.active').each(function(){
        let inputBox = $(this).closest('.input-tabs');
        inputTabsAnimation(inputBox, $(this));
    });
    $('.input-background-tab-box').each(function(){
        generatorGradient($(this));
    });
    tinymce.remove();
    tinymce_load();
    tinyMCE.execCommand('mceAddControl', false, "content");
    rangeSlider();
    popover_load();
}