/**
 * ---------------------------------------------------------------------------
 * 一些JS工具
 * ---------------------------------------------------------------------------
 * <strong>copyright</strong>： ©版权所有 成都都在哪网讯科技有限公司<br>
 * ----------------------------------------------------------------------------
 *
 * @author :hewei
 * @time:2016年6月12日 下午1:44:43
 * ---------------------------------------------------------------------------
 */
var ts = window.ts || {
        domain: 'http://' + window.top.location.host + '/',
        isPC: function () {
            if(/AppleWebKit.*Mobile/i.test(navigator.userAgent) || /iPad|Android|webOS|iPhone|iPod|BlackBerry/i.test(navigator.userAgent) || (/MIDP|SymbianOS|NOKIA|SAMSUNG|LG|NEC|TCL|Alcatel|BIRD|DBTEL|Dopod|PHILIPS|HAIER|LENOVO|MOT-|Nokia|HTC|SonyEricsson|SIE-|Amoi|ZTE/.test(navigator.userAgent))){
                return false;
            }
            return true;
        }()
    };

/**
 * 获取唯一编码
 *
 * @author hewei
 */
ts.guid = function () {
    function S4() {
        return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
    }
    return (S4()+S4()+"-"+S4()+"-"+S4()+"-"+S4()+"-"+S4()+S4()+S4());
}();

// ------------------------------------ cookie 操作 -------------------------------------
/**
 * Cookie 操作
 * @type {{get: Window.ts.cookie.get, set: Window.ts.cookie.set, del: Window.ts.cookie.del}}
 */
ts.cookie = {
    /**
     * 获取指定cookie 值
     *
     * @param name cookie 名
     * @returns {*}
     * @author hewei
     */
    get: function (name) {
        var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
        if(arr=document.cookie.match(reg)){
            return decodeURIComponent(arr[2]);
        } else {
            return null;
        }
    },
    /**
     * 设置 cookie
     *
     * @param name cookie 名
     * @param value 值
     * @param expires 过期时间（当前时间开始,默认当前会话有效）
     * @param path 路径(默认根目录/)
     * @param domain domain
     * @return {string}
     * @author hewei
     */
    set: function (name, value, expires, path, domain) {
        var expiresStr;
        if (typeof expires === 'number') {
            expiresStr = new Date(new Date().getMilliseconds() + expires * 1000).toUTCString();
        }
        return (document.cookie = [
            encodeURIComponent(name), '=', encodeURIComponent(String(value)),
            expires ? '; expires=' + expiresStr : '', // use expires attribute, max-age is not supported by IE
            path    ? '; path=' + path : '; path=/',
            domain  ? '; domain=' + domain : ''
        ].join(''));
    },
    /**
     * 删除指定cookie
     *
     * @param name cookie 名
     * @return bool
     * @author hewei
     */
    del: function (name) {
        ts.cookie.set(name, '', -1);
        return !ts.cookie.get(name);
    }
};
// ------------------------------------ 登录相关 -------------------------------------
/**
 * 登录
 *
 * @param callback 回调 function(success){}
 * @author hewei
 */
ts.login = function (callback) {
    // 检查登录cookie
    if (ts.cookie.get('ts_login')){
        callback(true);
    } else {
        // 直接跳转登录页面
        window.location.href = ts.domain + 'web/utils/JSPlugins/login?redirect_uri=' + encodeURI(window.location.href);
    }
};
/**
 * Created by HW on 2016/5/6.
 */
(function(root, factory) {
    if (typeof define === "function" && define.amd) {
        define([], factory);
    } else {
        if (typeof exports === "object") {
            module.exports = factory();
        } else {
            factory();
        }
    }
})(this, function() {
    window.ut_rem;
    var win = window, doc = document, docEl = doc.documentElement, resizeEvt = "orientationchange" in window ? "orientationchange" :"resize", recalc = function() {
        var clientWidth = window.top.document.documentElement.clientWidth;
        if (!clientWidth) {
            return;
        }
        window.ut_rem = clientWidth / 640 * 100;
        docEl.style.fontSize = window.ut_rem + "px";
    };
    if (!doc.addEventListener) {
        return;
    }
    win.addEventListener(resizeEvt, recalc, false);
    doc.addEventListener("DOMContentLoaded", recalc, false);
});
// ------------------------------------ 微信相关 start ------------------------------------
ts.wx = {};

/**
 * 微信分享
 *
 * @param opts 参数
 * @author hewei
 */
ts.wx.share = function (opts) {
    this.opts = $.extend({
        shares: 'all',	// 允许分享方式appMessage,timeline,qq,weiboApp,facebook,QZone
        title : null,   // 要分享的标题
        link : null,    // 要分享的连接地址
        imgUrl : null,  // 要分享的图片
        desc : null,    // 分享描述
        /**
         * 分享成功回调
         *
         * @param type 类型
         * @param link 分享出去的链接
         */
        share_success : function(type, link) {},
        share_cancel: function(type, link){}
    }, opts);

    var _this = this;
    wx.ready(function(){
        // 分享限制
        if(_this.opts.shares != 'all'){
            var items = {
                'appMessage': 'menuItem:share:appMessage',
                'timeline': 'menuItem:share:timeline',
                'qq': 'menuItem:share:qq',
                'weiboApp': 'menuItem:share:weiboApp',
                'facebook': 'menuItem:share:facebook',
                'QZone': 'menuItem:share:QZone'
            };
            // 生成隐藏列表
            _this.wx_hids = [];
            $.each(items, function(k, v){
                if($.inArray(k, _this.opts.shares) == -1){
                    _this.wx_hids.push(v);
                }
            });
            if (_this.wx_hids.length > 0){
                wx.hideMenuItems({
                    menuList: _this.wx_hids
                });
            }
        }
        // 初始分享配置
        _this.share_link(_this.opts.title, _this.opts.link, _this.opts.imgUrl, _this.opts.desc);
    });
};

/**
 * 微信分享（链接）
 *
 * @param title 标题
 * @param link 链接
 * @param imgUrl 图标
 * @param desc 描述
 *
 * @author hewei
 */
ts.wx.share.prototype.share_link = function(title, link, imgUrl, desc) {
    var _this = this;
    // 分享数据
    this.opts.title = title;
    this.opts.link = link;
    this.opts.imgUrl = imgUrl;
    this.opts.desc = desc;

    // 分享到朋友圈
    wx.onMenuShareTimeline({
        title : title, // 分享标题
        link : link, // 分享链接
        imgUrl : imgUrl, // 分享图标
        success : function() {
            // 用户确认分享后执行的回调函数
            _this.opts.share_success('Timeline', link);
        },
        cancel : function() {
            // 用户取消分享后执行的回调函数
            _this.opts.share_cancel('Timeline', link);
        }
    });
    // 分享给朋友
    wx.onMenuShareAppMessage({
        title : title, // 分享标题
        desc : desc, // 分享描述
        link : link, // 分享链接
        imgUrl : imgUrl, // 分享图标
        type : '', // 分享类型,music、video或link，不填默认为link
        dataUrl : '', // 如果type是music或video，则要提供数据链接，默认为空
        success : function() {
            // 用户确认分享后执行的回调函数
            _this.opts.share_success('AppMessage', link);
        },
        cancel : function() {
            // 用户取消分享后执行的回调函数
            _this.opts.share_cancel('AppMessage', link);
        }
    });
    // 分享到QQ
    wx.onMenuShareQQ({
        title : title, // 分享标题
        desc : desc, // 分享描述
        link : link, // 分享链接
        imgUrl : imgUrl, // 分享图标
        success : function() {
            // 用户确认分享后执行的回调函数
            _this.opts.share_success('QQ', link);
        },
        cancel : function() {
            // 用户取消分享后执行的回调函数
            _this.opts.share_cancel('QQ', link);
        }
    });
    // 分享到腾讯微博
    wx.onMenuShareWeibo({
        title : title, // 分享标题
        desc : desc, // 分享描述
        link : link, // 分享链接
        imgUrl : imgUrl, // 分享图标
        success : function() {
            // 用户确认分享后执行的回调函数
            _this.opts.share_success('Weibo', link);
        },
        cancel : function() {
            // 用户取消分享后执行的回调函数
            _this.opts.share_cancel('Weibo', link);
        }
    });
};window.ts = ts;