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
        var $login = $('#ts_login');
        window.ts_login_callback = callback;
        // 初始化
        if ($login.length < 1){
            $login = $('<div id="ts_login"><div class="login"><iframe scrolling="no" frameborder="0" width="100%" height="100%"></iframe><div class="close"><i></i></div></div></div>').hide().appendTo($('body'));
            // 绑定关闭按钮事件
            $('#ts_login,#ts_login .close').click(function () {
                $login.fadeOut();
                return false;
            });
            // 阻止login 弹出窗点击事件
            $('#ts_login .login').click(function () {
                return false;
            });
            // iframe 加载完成事件
            $('#ts_login iframe').load(function () {
                if (!ts.cookie.get('ts_login')){
                    $login.fadeIn();
                    $('#ts_login iframe').show();
                }
            });
        }
        // 加载登录页面
        $login.find('.login').removeClass('full').find('iframe').attr('src', ts.domain + 'web/utils/JSPlugins/login?redirect_uri=' + encodeURI(window.location.href)).hide();
    }
};window.ts = ts;