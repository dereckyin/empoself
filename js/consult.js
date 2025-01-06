
var app = new Vue({
    el: '#app',
    data:{

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
  
  
    reset: function() {
  
  
      this.apply_start = '';
      this.apply_end = '';
      this.period = 0;
      this.leave_type = '';
      this.reason = '';
      this.submit = false;
      this.getLeaveCredit();
      this.getRecords();
    },
  
  }
  });