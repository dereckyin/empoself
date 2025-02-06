var app = new Vue({
  el: '#app',

  components: {
 
  },
  data(){
      return { 
         name: '',
         birthday: '',

         line_verify: '',
         email_verify: '',

         verify_code: '',
         new_password: '',
         confirm_password: '',

         showHint: false,
         hint: '',

         showSubmitHint: false,
         submit_hint: '',

         countdown: 60, // Countdown timer in seconds
         isButtonDisabled: false, // Button state

         button_text : '發送驗證碼',

         is_submit: false,
       }
  },

  computed: {
    
  },

  created () {

  },

  watch: {
    showHint: function() {
      setTimeout(() => {
        this.showHint = false;
      }, 3000);
    },

    showSubmitHint: function() {
      setTimeout(() => {
        this.showSubmitHint = false;
      }, 3000);
    }

  },

  methods: {
   
    send_verify_code: async function() {
      var must = [];
      if (!this.name) {
        must = [...must, '姓名'];
      }
      if(!this.birthday) {
        must = [...must, '生日'];
      }

      if(must.length > 0){
        this.hint = '請輸入「姓名」和「生日」';
        this.showHint = true;
        return false;
      }

      if(this.is_submit)
        return false;

      this.is_submit = true;

      const existingData = await this.sendVerifyCode();
      this.startCountdown();
      this.showHint = true;

      this.is_submit = false;
    },

    sendVerifyCode: async function() {
      let json = null;
      let _this = this;
      const parameters = { name: this.name.trim(), birthday: this.birthday.trim() };

      let ret = await axios
          .post("api/reset_send_verify_code", parameters, {
            headers: {
            "Content-Type": "application/json"
            }})
          .then((res) => {
              json = res.data;
              _this.showHint = true;
              _this.hint = "已發送驗證碼到「" + json.message +  "」";
          })
          .catch((err) => {
            if (err.status == 401) {
              _this.showHint = true;
              _this.hint = "所填寫的資料有誤，請再次確認填寫是否正確";
          }
          
          if (err.status == 501) {
              _this.showHint = true;
              _this.hint = "目前無法發送驗證碼，請稍後再試";
          }
          })
          .finally(() => {
              return json;
          });
    },

    
    submit_verify_code: async function() {

      let _this = this;
      var must = [];
      if (!this.name) {
        must = [...must, '姓名'];
        if(must.length > 0){
          var html = must.join('」、「');
          html = "請輸入「" + html + "」";
          this.submit_hint = html;
          this.showSubmitHint = true;
          return false;
        }
      }
      if(!this.birthday) {
        must = [...must, '生日'];
        if(must.length > 0){
          var html = must.join('」、「');
          html = "請輸入「" + html + "」";
          this.submit_hint = html;
          this.showSubmitHint = true;
          return false;
        }
      }

      if(!this.verify_code) {
        must = [...must, '驗證碼'];
        if(must.length > 0){
          var html = must.join('」、「');
          html = "請輸入「" + html + "」";
          this.submit_hint = html;
          this.showSubmitHint = true;
          return false;
        }
      }

      if(!this.new_password) {
        must = [...must, '新密碼'];
        if(must.length > 0){
          var html = must.join('」、「');
          html = "請輸入「" + html + "」";
          this.submit_hint = html;
          this.showSubmitHint = true;
          return false;
        }
      }

      if(!this.confirm_password) {
        must = [...must, '確認密碼'];
        if(must.length > 0){
          var html = must.join('」、「');
          html = "請輸入「" + html + "」";
          this.submit_hint = html;
          this.showSubmitHint = true;
          return false;
        }
      }

      const passwordPattern = /^[A-Za-z0-9!@#$%^&*()_+\-=<>?]+$/;
      const newPassword = document.getElementById('new-password').value;
      const confirmPassword = document.getElementById('confirm-password').value;

      var recaptcha = document.getElementById('recaptchaResponse');
      if(!recaptcha.value){
        must = [...must, '請勾選我不是機器人'];
        if(must.length > 0){
          var html = must.join('」、「');
          html = "「" + html + "」";
          this.submit_hint = html;
          this.showSubmitHint = true;
          return false;
        }
      }

      if (!passwordPattern.test(newPassword) || !passwordPattern.test(confirmPassword)) {
          must = [...must, '密碼只能是英文大小寫字母、數字或特殊符號'];
          if(must.length > 0){
            html = must.join('');
            this.submit_hint = html;
            this.showSubmitHint = true;
            return false;
          }
      }

      if(this.new_password != this.confirm_password) {
        must = [...must, '兩次輸入的密碼不一致'];
        if(must.length > 0){
          html = must.join('');
          this.submit_hint = html;
          this.showSubmitHint = true;
          return false;
        }
      }

      if(this.is_submit)
        return false;

      this.is_submit = true;

      const parameters = { name: this.name, birthday: this.birthday, verify_code: this.verify_code, new_password: this.new_password, confirm_password: this.confirm_password, recaptcha_response: recaptcha.value };
      
      axios
          .post("api/reset_password", parameters, headers = {"Content-Type": "application/json"})
          .then((res) => {
              if (res.data.length > 0) {
               Swal.fire({
                text: "重設密碼成功",
                icon: "success",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK",
              }).then((result) => {
                if (result.value) {
                  window.location.href = "login";
                }
            });
                  

              } else {
                  Swal.fire({
                      text: res.data.message,
                      icon: "error",
                      confirmButtonText: 'OK'
                  });
              }
          })
          .catch((err) => {
              if (err.status == 401) {

                  _this.showSubmitHint = true;
                  _this.submit_hint = err.data.message;
              }

              if (err.status == 404) {

                _this.showSubmitHint = true;
                _this.submit_hint = err.data.message;
            }

              if (err.status == 501) {

                  _this.showSubmitHint = true;
                  _this.submit_hint = err.data.message;
              }

          }
      ).finally(() => {
          _this.is_submit = false;
      });
    },

    startCountdown: function() {
      this.isButtonDisabled = true; // Disable the button
      this.countdown = 60; // Reset countdown to 60 seconds

      const interval = setInterval(() => {
        this.countdown--;
        this.button_text = "發送驗證碼 ... " + this.countdown + "秒";

        if (this.countdown <= 0) {
          clearInterval(interval); // Clear the interval when countdown reaches 0
          this.isButtonDisabled = false; // Re-enable the button

          this.button_text = "發送驗證碼"; // Reset the button text
          this.hint = "";
        }
      }, 1000); // Update every second
    }

  }

  

});


