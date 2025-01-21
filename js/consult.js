var app = new Vue({
    el: '#app',
    data:{
      name: '',
      gender: '',
      birthday: '',
      phone: '',
      email: '',
      address: '',
      emergency_contact: '',
      emergency_contact_phone: '',
      referral_source: [],
      referral_source_other: '',
      health_condition: [],
      health_condition_other: '',

      // for popup verify
      pop_name: '',
      pop_birthday: '',
      verify_code: '',
      showHint: false,
      countdown: 60, // Countdown timer in seconds
      isButtonDisabled: false, // Button state
      hint: '',
      verified: false,
      button_text : '發送驗證碼',
    },
  
    created () {

    },
  
    computed: {

    },
  
    mounted(){
      
    },
  
    watch: {
  
    },
  
  
  
    methods:{
      checkForm: function (e) {
        var must = [];
        var format = [];

        this.name = this.name.trim();
        this.birthday = this.birthday.trim();
        this.phone = this.phone.trim();
        this.email = this.email.trim();
        this.address = this.address.trim();
        this.emergency_contact = this.emergency_contact.trim();
        this.emergency_contact_phone = this.emergency_contact_phone.trim();

        this.referral_source_other = this.referral_source_other.trim();
        this.health_condition_other = this.health_condition_other.trim();

        if (!this.name) {
          must = [...must, '姓名'];
        }

        if (!this.birthday) {
          must = [...must, '生日'];
        }

        if (!this.phone) {
          must = [...must, '手機號碼'];
        }

        if (!this.email) {
          must = [...must, 'Email'];
        }

        if(this.email && !this.email.match(/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)) {
          format = [...format, 'Email'];
        }

        if(this.phone && !this.phone.match(/^(09|\+8869)\d{8}$/)) {
          format = [...format, '手機號碼'];
        }

        if (!this.address) {
          must = [...must, '地址'];
        }

        if (!this.emergency_contact) {
          must = [...must, '緊急聯絡人'];
        }

        if (!this.emergency_contact_phone) {
          must = [...must, '緊急聯絡人電話'];
        }


        if (this.health_condition.length == 0 && !this.health_condition_other) {
          must = [...must, '體況'];
        }

        if(must.length > 0 || format.length > 0){
          var html = '';

          if(must.length > 0){
            html = html + ' 請填寫以下欄位' + "<br><br>" + must.join('、');

          if(format.length > 0)
          {
            if(html != ''){
              html = html + "<br><br>";
              html = html + '以下欄位格式錯誤' + "<br><br>" + format.join('、') + "<br><br>";
            } else {
              html = '以下欄位格式錯誤' + "<br><br>" + format.join('、') + "<br><br>";
            }
          }

          Swal.fire({
            html: html,
            confirmButtonText: 'OK'
          });
          return false;
        }
      }

        return true;
      },

      send_verify_code: async function() {
        var must = [];
        if (!this.pop_name) {
          must = [...must, '姓名'];
        }
        if(!this.pop_birthday) {
          must = [...must, '生日'];
        }

        if(must.length > 0){
          Swal.fire({
            html: '”請輸入「姓名」和「生日」',
            confirmButtonText: 'OK'
          });
          return false;
        }

        const existingData = await this.sendVerifyCode();
        this.startCountdown();
        this.showHint = true;
      },

      sendVerifyCode: async function() {
        let json = null;
        let _this = this;
        const parameters = { name: this.pop_name.trim(), birthday: this.pop_birthday.trim() };

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
        if (!this.pop_name) {
          must = [...must, '姓名'];
        }
        if(!this.pop_birthday) {
          must = [...must, '生日'];
        }

        if(!this.verify_code) {
          must = [...must, '驗證碼'];
        }

        if(must.length > 0){
          Swal.fire({
            html: '請填寫以下欄位' + "<br><br>" + must.join('、'),
            confirmButtonText: 'OK'
          });
          return false;
        }

        const parameters = { name: this.pop_name, birthday: this.pop_birthday, verify_code: this.verify_code };
        
        axios
            .post("api/consult_verify", parameters, headers = {"Content-Type": "application/json"})
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


      nextSection: async function() {
        // Check for existing data with the same name and birthday
        let _this = this;
        if (this.checkForm()) {
            const existingData = await this.checkExistingData(this.name.trim(), this.birthday.trim());
            
            if (existingData && this.verified == false) {
              Swal.fire({
                text: '您之前已經諮詢過，點擊「載入資料」按鈕來代入舊資料',
                icon: "warning",
                confirmButtonText: 'OK'
              });
            } else if (existingData && this.verified == true) {
                document.getElementById("section1").classList.add("hidden");
                document.getElementById("section2").classList.remove("hidden");
            }
            else 
            {
              document.getElementById("section1").classList.add("hidden");
              document.getElementById("section2").classList.remove("hidden");
            }
        }
      },

      checkExistingData: async function(name, birthday) {
        // Make an API call to fetch existing data
        let json = "";
        parameters = {name: name, birthday: birthday};

        try {
          let ret = await axios({ method: 'get', url: 'api/consult_get', params: parameters });

          if (ret.data.length > 0) {
              json = ret.data[0];
          }
        } catch (error) {

        }
        
        return json;

      },

      loadOldData: function(data) {
        // Load the old data into the form fields
        this.name = data.name;
        this.birthday = data.birthday;
        this.gender = data.gender;
        this.phone = data.phone;
        this.email = data.email;
        this.address = data.address;
        this.emergency_contact = data.emergency_contact || ''; // Assuming this field may not exist in old data
        this.emergency_contact_phone = data.emergency_contact_phone || ''; // Assuming this field may not exist in old data
        this.referral_source = data.referral_source.split(',') || []; // Assuming this field may not exist in old data
        this.referral_source_other = data.referral_source_other || ''; // Assuming this field may not exist in old data
        this.health_condition = data.health_condition.split(',') || ''; // Assuming this field may not exist in old data
        this.health_condition_other = data.health_condition_other || ''; // Assuming this field may not exist in old data
      },

      submitForm: function(){
        var form_Data = new FormData();
        let _this = this;

        var url = 'api/consult_add';
        if(this.verified)
          url = 'api/consult_update';

        form_Data.append('name', this.name.trim());
        form_Data.append('gender', this.gender.trim());
        form_Data.append('birthday', this.birthday.trim());
        form_Data.append('phone', this.phone.trim());
        form_Data.append('email', this.email.trim());
        form_Data.append('address', this.address.trim());
        form_Data.append('emergency_contact', this.emergency_contact.trim());
        form_Data.append('emergency_contact_phone', this.emergency_contact_phone.trim());
        form_Data.append('referral_source', this.referral_source);
        form_Data.append('referral_source_other', this.referral_source_other.trim());
        form_Data.append('health_condition', this.health_condition);
        form_Data.append('health_condition_other', this.health_condition_other.trim());


        axios({
          method: 'post',
          headers: {
              'Content-Type': 'multipart/form-data',
          },
          url: url,
          data: form_Data
        })
        .then(function(response) {
            //handle success
            Swal.fire({
              text: response.data.message,
              icon: 'success',
              confirmButtonText: 'OK'
            })

            _this.reset();
        })
        .catch(function(response) {
            //handle error
            Swal.fire({
              text: JSON.stringify(response.data),
              icon: 'error',
              confirmButtonText: 'OK'
            })
        });
      },

      reset: function() {
        this.name = '';
        this.gender = '';
        this.birthday = '';
        this.phone = '';
        this.email = '';
        this.address = '';
        this.emergency_contact = '';
        this.emergency_contact_phone = '';
        this.referral_source = [];
        this.referral_source_other = '';
        this.health_condition = [];
        this.health_condition_other = '';

        document.getElementById("section1").classList.remove("hidden");
        document.getElementById("section2").classList.add("hidden");
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
          }
        }, 1000); // Update every second
      }
    }
  });