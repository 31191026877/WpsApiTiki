$(function() {
    const SELF_XSS_HEADER = "STOP!";
    const SELF_XSS_LINE_1 = "Nếu ai đó yêu cầu bạn sao chép (dán) nội dung nào đó ở đây, bạn có 11/10 khả năng bạn đang bị lừa";
    const SELF_XSS_LINE_2 = "Dán bất cứ thứ gì vào đây có thể cấp cho những kẻ tấn công quyền truy cập vào tài khoản quản trị của bạn.";
    const SELF_XSS_LINE_3 = "Nếu bạn hiểu chính xác những gì bạn đang làm, bạn nên đến làm việc với chúng tôi https://jobs.sikido.vn";
    console.log("%c "+SELF_XSS_HEADER, "font-size:40px; font-weight:bold; color: #FF9D00;text-shadow: 0px 0px 2px #000;");
    console.log("%c "+SELF_XSS_LINE_1, "font-size:15px; font-weight:bold; color: #fff;text-shadow: 0px 0px 2px #000;");
    console.log("%c "+SELF_XSS_LINE_2, "font-size:15px; font-weight:bold; color: red;text-shadow: 0px 0px 2px #000;");
    console.log("%c "+SELF_XSS_LINE_3, "font-size:15px; font-weight:bold; color: #fff;text-shadow: 0px 0px 2px #000;");

    // First we get the viewport height and we multiple it by 1% to get a value for a vh unit
    let vh = window.innerHeight * 0.01;

    // Then we set the value in the --vh custom property to the root of the document
    document.documentElement.style.setProperty('--vh', `${vh}px`);

    $.ajaxSetup({
        beforeSend: function(xhr, settings) {
            settings.data += '&csrf_test_name=' + encodeURIComponent(getCookie('csrf_cookie_name'));
        }
    });
    //Fixed menu scroll
    if(typeof $('.header-mobile').html() != 'undefined') {

        let nav_m = $('.header-mobile');

        let nav_m_p = nav_m.position();

        $(window).scroll(function() {
            if ($(this).scrollTop() > nav_m_p.top) {
                nav_m.addClass('fixed');
            } else {
                nav_m.removeClass('fixed');
            }
        });
    }

    /*********** MOBILE SEARCH ***********/
    $('.js_btn_panel__sidebar').click(function() {
        let id = $(this).attr('href');
        if(!$(id).hasClass('active')) {
            $('.panel--sidebar').removeClass('active');
            $('body').removeClass('panel__sidebar-opened');
        }
        $(id).toggleClass('active');
        $('body').toggleClass('panel__sidebar-opened');
        return false;
    });

    $('.js_panel__close').click(function() {
        $('.panel--sidebar').removeClass('active');
        $('body').removeClass('panel__sidebar-opened');
        return false;
    });

    $('.navigation__item_menu').click(function() {
        $('.panel--sidebar').removeClass('active');
        $('body').removeClass('panel__sidebar-opened');
        return false;
    });

    $('#td-header-search-mob').keyup(function() {
        $('.result-msg a').attr('href', domain + 'search?keyword=' + $(this).val() + '&type=products');
    });

    //Search Result
    let searchResult = [];
    let typingTimeout = null;
    typeAndSearch();
    function typeAndSearch() {
        $(document).on("keyup", '.td-search-form input[name="keyword"]', function (event) {
            let self = $(this);
            let keyword = self.val();
            if (event.which === 13) {
                checkSubmitForm(keyword);
            } else {
                let waitTyping = 1000;
                clearTimeout(typingTimeout);
                typingTimeout = setTimeout(function () {
                    if (keyword !== "") {
                        startToSearch(keyword);
                    }
                }, waitTyping);
            }
        });
    }
    function checkSubmitForm(keyword) {
        if (keyword === "") {
            alert("Hãy nhập từ khóa bạn muốn tìm");
            return false;
        }
    }
    function startToSearch(keyword) {
        $('.td-drop-down-search .loading').show();
        let data = {
            'keyword': keyword,
            'action': 'ThemeAjax::productSearch',
        };
        $jqxhr = $.post(ajax, data, function() {}, 'json');
        $jqxhr.done(function(data) {
            $('.td-drop-down-search .loading').hide();
            searchResult[keyword] = data.data;
            $('#td-aj-search-mob').html(data.data);
        });
    }

    //Header Search Result
    let headerSearchResult = [];
    let headerTypingTimeout = null;
    let headerLiveSearch = $(".live-search-results");

    headerSearch();

    function headerSearch() {
        $(document).on("keyup", 'header .form-search input[name="keyword"]', function (event) {
            let self = $(this);
            let keyword = self.val();
            if (event.which === 13) {
                checkSubmitForm(keyword);
            } else {
                let waitTyping = 300;
                clearTimeout(headerTypingTimeout);
                headerTypingTimeout = setTimeout(function () {
                    if (keyword !== "") {
                        headerStartToSearch(keyword);
                    }
                }, waitTyping);
            }
        });
    }

    function headerStartToSearch(keyword) {
        if(keyword.length <= 2) {
            headerLiveSearch.hide();
            return false;
        }

        if(typeof headerSearchResult[keyword] != 'undefined') {
            headerLiveSearch.show();
            headerLiveSearch.find('.autocomplete-suggestions .product-slider-vertical').html(headerSearchResult[keyword]);
            return false;
        }

        let data = {
            'keyword': keyword,
            'action': 'ThemeAjax::productSearch',
        };
        let jqxhr = $.post(ajax, data, function() {}, 'json');
        jqxhr.done(function(data) {
            headerSearchResult[keyword] = data.data;
            headerLiveSearch.show();
            headerLiveSearch.find('.autocomplete-suggestions .product-slider-vertical').html(data.data);
        });
    }

    $(document).mouseup(function(e) {
        if (!headerLiveSearch.is(e.target) && headerLiveSearch.has(e.target).length === 0) {
            let inputSearch = $('header .form-search input[name="keyword"]');
            if (!inputSearch.is(e.target) && inputSearch.has(e.target).length === 0) {
                headerLiveSearch.hide();
            }
        }
    });

    $(document).on('focus', 'header .form-search input[name="keyword"]', function () {
        headerStartToSearch($(this).val());
        return false;
    });

    let aos_in = false;

    AOS.init();

    setTimeout(function () { AOS.init(); }, 500);

    $(window).scroll(function () { if(aos_in === false) { AOS.init(); aos_in = true; } });

    $('img[data-src]').Lazy();
});

//hiển thị thông báo
function show_message(text, icon) {
    $.toast({ heading: "Thông Báo", text: text, position: 'bottom-center', icon: icon, hideAfter: 5000, });
}

//kiểm tra đối tượng có tồn tại không
function isset($element) {
    if (typeof $element != 'undefined')
        return true;
    return false;
}

function setCookie(cname, cvalue, exdays) {
    let d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function delCookie(name) {
    document.cookie = name + ';expires= Thu, 01-Jan-1970 00:00:01 GMT;';
};

function render(props) {
    return function(tok, i) {
        return (i % 2) ? props[tok] : tok;
    };
}

function number_format (number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

(function(factory){if(typeof define==='function'&&define.amd){define(['jquery'],factory);}else if(typeof exports==='object'){var jQuery=require('jquery');module.exports=factory(jQuery);}else{factory(window.jQuery||window.Zepto||window.$);}}(function($){"use strict";$.fn.serializeJSON=function(options){var f,$form,opts,formAsArray,serializedObject,name,value,parsedValue,_obj,nameWithNoType,type,keys,skipFalsy;f=$.serializeJSON;$form=this;opts=f.setupOpts(options);formAsArray=$form.serializeArray();f.readCheckboxUncheckedValues(formAsArray,opts,$form);serializedObject={};$.each(formAsArray,function(i,obj){name=obj.name;value=obj.value;_obj=f.extractTypeAndNameWithNoType(name);nameWithNoType=_obj.nameWithNoType;type=_obj.type;if(!type)type=f.attrFromInputWithName($form,name,'data-value-type');f.validateType(name,type,opts);if(type!=='skip'){keys=f.splitInputNameIntoKeysArray(nameWithNoType);parsedValue=f.parseValue(value,name,type,opts);skipFalsy=!parsedValue&&f.shouldSkipFalsy($form,name,nameWithNoType,type,opts);if(!skipFalsy){f.deepSet(serializedObject,keys,parsedValue,opts);}}});return serializedObject;};$.serializeJSON={defaultOptions:{checkboxUncheckedValue:undefined,parseNumbers:false,parseBooleans:false,parseNulls:false,parseAll:false,parseWithFunction:null,skipFalsyValuesForTypes:[],skipFalsyValuesForFields:[],customTypes:{},defaultTypes:{"string":function(str){return String(str);},"number":function(str){return Number(str);},"boolean":function(str){var falses=["false","null","undefined","","0"];return falses.indexOf(str)===-1;},"null":function(str){var falses=["false","null","undefined","","0"];return falses.indexOf(str)===-1?str:null;},"array":function(str){return JSON.parse(str);},"object":function(str){return JSON.parse(str);},"auto":function(str){return $.serializeJSON.parseValue(str,null,null,{parseNumbers:true,parseBooleans:true,parseNulls:true});},"skip":null},useIntKeysAsArrayIndex:false},setupOpts:function(options){var opt,validOpts,defaultOptions,optWithDefault,parseAll,f;f=$.serializeJSON;if(options==null){options={};}defaultOptions=f.defaultOptions||{};validOpts=['checkboxUncheckedValue','parseNumbers','parseBooleans','parseNulls','parseAll','parseWithFunction','skipFalsyValuesForTypes','skipFalsyValuesForFields','customTypes','defaultTypes','useIntKeysAsArrayIndex'];for(opt in options){if(validOpts.indexOf(opt)===-1){throw new Error("serializeJSON ERROR: invalid option '"+opt+"'. Please use one of "+validOpts.join(', '));}}optWithDefault=function(key){return(options[key]!==false)&&(options[key]!=='')&&(options[key]||defaultOptions[key]);};parseAll=optWithDefault('parseAll');return{checkboxUncheckedValue:optWithDefault('checkboxUncheckedValue'),parseNumbers:parseAll||optWithDefault('parseNumbers'),parseBooleans:parseAll||optWithDefault('parseBooleans'),parseNulls:parseAll||optWithDefault('parseNulls'),parseWithFunction:optWithDefault('parseWithFunction'),skipFalsyValuesForTypes:optWithDefault('skipFalsyValuesForTypes'),skipFalsyValuesForFields:optWithDefault('skipFalsyValuesForFields'),typeFunctions:$.extend({},optWithDefault('defaultTypes'),optWithDefault('customTypes')),useIntKeysAsArrayIndex:optWithDefault('useIntKeysAsArrayIndex')};},parseValue:function(valStr,inputName,type,opts){var f,parsedVal;f=$.serializeJSON;parsedVal=valStr;if(opts.typeFunctions&&type&&opts.typeFunctions[type]){parsedVal=opts.typeFunctions[type](valStr);}else if(opts.parseNumbers&&f.isNumeric(valStr)){parsedVal=Number(valStr);}else if(opts.parseBooleans&&(valStr==="true"||valStr==="false")){parsedVal=(valStr==="true");}else if(opts.parseNulls&&valStr=="null"){parsedVal=null;}if(opts.parseWithFunction&&!type){parsedVal=opts.parseWithFunction(parsedVal,inputName);}return parsedVal;},isObject:function(obj){return obj===Object(obj);},isUndefined:function(obj){return obj===void 0;},isValidArrayIndex:function(val){return/^[0-9]+$/.test(String(val));},isNumeric:function(obj){return obj-parseFloat(obj)>=0;},optionKeys:function(obj){if(Object.keys){return Object.keys(obj);}else{var key,keys=[];for(key in obj){keys.push(key);}return keys;}},readCheckboxUncheckedValues:function(formAsArray,opts,$form){var selector,$uncheckedCheckboxes,$el,uncheckedValue,f,name;if(opts==null){opts={};}f=$.serializeJSON;selector='input[type=checkbox][name]:not(:checked):not([disabled])';$uncheckedCheckboxes=$form.find(selector).add($form.filter(selector));$uncheckedCheckboxes.each(function(i,el){$el=$(el);uncheckedValue=$el.attr('data-unchecked-value');if(uncheckedValue==null){uncheckedValue=opts.checkboxUncheckedValue;}if(uncheckedValue!=null){if(el.name&&el.name.indexOf("[][")!==-1){throw new Error("serializeJSON ERROR: checkbox unchecked values are not supported on nested arrays of objects like '"+el.name+"'. See https://github.com/marioizquierdo/jquery.serializeJSON/issues/67");}formAsArray.push({name:el.name,value:uncheckedValue});}});},extractTypeAndNameWithNoType:function(name){var match;if(match=name.match(/(.*):([^:]+)$/)){return{nameWithNoType:match[1],type:match[2]};}else{return{nameWithNoType:name,type:null};}},shouldSkipFalsy:function($form,name,nameWithNoType,type,opts){var f=$.serializeJSON;var skipFromDataAttr=f.attrFromInputWithName($form,name,'data-skip-falsy');if(skipFromDataAttr!=null){return skipFromDataAttr!=='false';}var optForFields=opts.skipFalsyValuesForFields;if(optForFields&&(optForFields.indexOf(nameWithNoType)!==-1||optForFields.indexOf(name)!==-1)){return true;}var optForTypes=opts.skipFalsyValuesForTypes;if(type==null)type='string';if(optForTypes&&optForTypes.indexOf(type)!==-1){return true}return false;},attrFromInputWithName:function($form,name,attrName){var escapedName,selector,$input,attrValue;escapedName=name.replace(/(:|\.|\[|\]|\s)/g,'\\$1');selector='[name="'+escapedName+'"]';$input=$form.find(selector).add($form.filter(selector));return $input.attr(attrName);},validateType:function(name,type,opts){var validTypes,f;f=$.serializeJSON;validTypes=f.optionKeys(opts?opts.typeFunctions:f.defaultOptions.defaultTypes);if(!type||validTypes.indexOf(type)!==-1){return true;}else{throw new Error("serializeJSON ERROR: Invalid type "+type+" found in input name '"+name+"', please use one of "+validTypes.join(', '));}},splitInputNameIntoKeysArray:function(nameWithNoType){var keys,f;f=$.serializeJSON;keys=nameWithNoType.split('[');keys=$.map(keys,function(key){return key.replace(/\]/g,'');});if(keys[0]===''){keys.shift();}return keys;},deepSet:function(o,keys,value,opts){var key,nextKey,tail,lastIdx,lastVal,f;if(opts==null){opts={};}f=$.serializeJSON;if(f.isUndefined(o)){throw new Error("ArgumentError: param 'o' expected to be an object or array, found undefined");}if(!keys||keys.length===0){throw new Error("ArgumentError: param 'keys' expected to be an array with least one element");}key=keys[0];if(keys.length===1){if(key===''){o.push(value);}else{o[key]=value;}}else{nextKey=keys[1];if(key===''){lastIdx=o.length-1;lastVal=o[lastIdx];if(f.isObject(lastVal)&&(f.isUndefined(lastVal[nextKey])||keys.length>2)){key=lastIdx;}else{key=lastIdx+1;}}if(nextKey===''){if(f.isUndefined(o[key])||!$.isArray(o[key])){o[key]=[];}}else{if(opts.useIntKeysAsArrayIndex&&f.isValidArrayIndex(nextKey)){if(f.isUndefined(o[key])||!$.isArray(o[key])){o[key]=[];}}else{if(f.isUndefined(o[key])||!f.isObject(o[key])){o[key]={};}}}tail=keys.slice(1);f.deepSet(o[key],tail,value,opts);}}};}));

document.addEventListener("DOMContentLoaded", function(){
// make it as accordion for smaller screens
    if (window.innerWidth > 992) {

        document.querySelectorAll('.navbar li').forEach(function(everyitem){

            everyitem.addEventListener('mouseover', function(e){

                let el_link = this.querySelector('a[data-bs-toggle]');

                if(el_link != null){
                    let nextEl = el_link.nextElementSibling;
                    el_link.classList.add('show');
                    nextEl.classList.add('show');
                }

            });
            everyitem.addEventListener('mouseleave', function(e){
                let el_link = this.querySelector('a[data-bs-toggle]');

                if(el_link != null){
                    let nextEl = el_link.nextElementSibling;
                    el_link.classList.remove('show');
                    nextEl.classList.remove('show');
                }
            })
        });

    }
// end if innerWidth
});
// DOMContentLoaded  end