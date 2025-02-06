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
       }
  },

  computed: {
    
  },

  created () {

  },

  watch: {

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

      const existingData = await this.sendVerifyCode();
      this.startCountdown();
      this.showHint = true;
    },

    sendVerifyCode: async function() {
      let json = null;
      let _this = this;
      const parameters = { name: this.name.trim(), birthday: this.birthday.trim() };

      let ret = await axios
          .post("api/consult_send_verify_code", parameters, {
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
      }
      if(!this.birthday) {
        must = [...must, '生日'];
      }

      if(!this.verify_code) {
        must = [...must, '驗證碼'];
      }

      if(!this.new_password) {
        must = [...must, '新密碼'];
      }

      if(!this.confirm_password) {
        must = [...must, '確認密碼'];
      }

      if(this.new_password != this.confirm_password) {
        must = [...must, '新密碼和確認密碼不相同'];
      }

      if(must.length > 0){
        var html = must.join('」、「');
        html = "請填寫以下欄位: 「" + html + "」";
        this.hint = html;
        this.showHint = true;
        return false;
      }

      const parameters = { name: this.name, birthday: this.birthday, verify_code: this.verify_code, new_password: this.new_password, confirm_password: this.confirm_password };
      
      axios
          .post("api/reset_password", parameters, headers = {"Content-Type": "application/json"})
          .then((res) => {
              if (res.data.length > 0) {
                  _this.loadOldData(res.data[0]);
                  $(".mask").toggle();
                  $(".popup-dialog").toggle();
                  _this.verified = true;
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

                  _this.showHint = true;
                  _this.hint = err.data.message;
              }
              
              if (err.status == 501) {

                  _this.showHint = true;
                  _this.hint = err.data.message;
              }

          }
      );
    },

  }

  

});


