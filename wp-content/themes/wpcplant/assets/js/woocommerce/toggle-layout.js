(function () {

    document.addEventListener('DOMContentLoaded', () => {

        let myBtns = document.querySelectorAll('.gridlist-toggle a');
        let layout = getCookie('shop_layout');
        if (layout) {
            document.querySelector('#primary ul.products').setAttribute('data-layout', layout);
        }
        myBtns.forEach(function (btn) {

            btn.addEventListener('click', (e) => {
                myBtns.forEach(b => b.classList.remove('active'));
                e.preventDefault();
                btn.classList.add('active');
                document.querySelector('#primary ul.products').setAttribute('data-layout', btn.getAttribute('data-class'));
                eraseCookie('shop_layout');
                setCookie('shop_layout',btn.getAttribute('data-class'),2);
            });

        });

    });

    function setCookie(name,value,days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days*24*60*60*1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "")  + expires + "; path=/";
    }

    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    function eraseCookie(name) {
        document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    }

})();
