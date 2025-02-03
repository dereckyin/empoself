var app = new Vue({
  el: '#app',

  components: {
 
  },
  data(){
      return { 
         phone:'',
         password:'',

         showHint: false,
          hint: '',
       }
  },

  computed: {
    
  },

  created () {
    this.setCookie("jwt", '');
    this.setCookie("phone", '');
    localStorage.token = '';
  },

  watch: {

  },

  methods: {
    keymonitor: function(event) {
          if(event.key == "Enter"){
            app.checkLogin();
          }
        },

    checkLogin: function(){
      if (this.phone === '')
        return false;
      if(this.password === '')
        return false;

      var recaptcha = document.getElementById('recaptchaResponse');

      var form_Data = new FormData();

       form_Data.append('phone', this.phone);
       form_Data.append('password', this.password);
       form_Data.append('recaptcha_response', recaptcha.value);

       const _this = this

      axios.post('api/login', form_Data)
        .then(function(response){
          if(response.data['error']){
            Swal.fire({
              text: response.data['error'],
              icon: 'error',
              confirmButtonText: 'OK'
            })
          }
          else{
            _this.setCookie("jwt", response.data['jwt']);
            _this.setCookie("uid", response.data['uid']);
            localStorage.token = response.data['jwt'];

            var url = _this.getCookie("userurl");

            if(url !== "" && url !== null)
            {
              _this.delCookie("userurl");
              setTimeout(function(){
                window.location.href=url;
              },1000);
            }
            else
            {
              setTimeout(function(){
                window.location.href="default";
              },1000);
            }
 
          }
        });
    },

    register:  function(obj){
      window.location.href = "create_user";
    },

    setCookie: function(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/;SameSite=Strict;";
    },

    getCookie : function(name) {
      var arg = escape(name) + "=";
      var nameLen = arg.length;
      var cookieLen = document.cookie.length;
      var i = 0;
      while (i < cookieLen) {
        var j = i + nameLen;
        if (document.cookie.substring(i, j) == arg) return this.getCookieValueByIndex(j);
        i = document.cookie.indexOf(" ", i) + 1;
        if (i == 0) break;
      }
      return null;
    },

    getCookieValueByIndex: function (startIndex) {
      var endIndex = document.cookie.indexOf(";", startIndex);
      if (endIndex == -1) endIndex = document.cookie.length;
      return unescape(document.cookie.substring(startIndex, endIndex));
    },

    delCookie : function(name) {
      var exp = new Date();
      exp.setTime(exp.getTime() - 1);
      var cval = this.getCookie(name);
      document.cookie = escape(name) + "=" + cval + "; expires=" + exp.toGMTString();
    },
  }

});


