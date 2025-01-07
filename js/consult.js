
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
          format = [...format, 'phone'];
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

      nextSection: function(){
        if(this.checkForm()){
          document.getElementById("section1").classList.add("hidden");
          document.getElementById("section2").classList.remove("hidden");
        }
      },

      submitForm: function(){
        var form_Data = new FormData();
        let _this = this;

        form_Data.append('name', this.name);
        form_Data.append('gender', this.gender);
        form_Data.append('birthday', this.birthday);
        form_Data.append('phone', this.phone);
        form_Data.append('email', this.email);
        form_Data.append('address', this.address);
        form_Data.append('emergency_contact', this.emergency_contact);
        form_Data.append('emergency_contact_phone', this.emergency_contact_phone);
        form_Data.append('referral_source', this.referral_source);
        form_Data.append('referral_source_other', this.referral_source_other);
        form_Data.append('health_condition', this.health_condition);
        form_Data.append('health_condition_other', this.health_condition_other);


        axios({
          method: 'post',
          headers: {
              'Content-Type': 'multipart/form-data',
          },
          url: 'api/consult_add',
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
    
    }
  });