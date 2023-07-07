<template>
   <div class="flex items-center">
      <div class="w-11 h-11 rounded-xl text-lg font-semibold count-box flex justify-center items-center text-white mr-6">{{ remainingHours }}  
      </div>
      <span class="mr-6 text-white text-2xl"> : </span>
      <div class="w-11 h-11 rounded-xl text-lg font-semibold count-box flex justify-center items-center text-white mr-6">{{ remainingMinutes }}</div> <span class="mr-6 text-2xl text-white"> : </span>
      <div class="w-11 h-11 rounded-xl text-lg font-semibold count-box flex justify-center items-center text-white mr-6">{{ remainingSeconds }} </div>
   </div>
</template>

<script>
import moment from 'moment';
export default {
   name: 'counter-timing',
   props: {
      countDownToTime: {
         type: Date,
         default() {
            let date = new Date();
            date.setDate(date.getDate() + 1);
            return date;
         }
      }
   },
   data() {
      return {
         remainingHours: null,
         remainingMinutes: null,
         remainingSeconds: null,
         timer: null
      }
   },
  methods: {
      startTimer() {
         // const timeNow = new Date().getTime();
         const timeNow = moment().utcOffset(0).valueOf();
         const timeDifference = moment(this.countDownToTime).utcOffset(0).valueOf() - timeNow;
         if (timeDifference <= 0) {
            this.$emit('timeExpires')
         }
         const millisecondsInOneSecond = 1000;
         const millisecondsInOneMinute = millisecondsInOneSecond * 60;
         const millisecondsInOneHour = millisecondsInOneMinute * 60;
         const millisecondsInOneDay = millisecondsInOneHour * 24;
         // const differenceInDays = timeDifference / millisecondsInOneDay;
         const remainderDifferenceInHours = (timeDifference % millisecondsInOneDay) / millisecondsInOneHour;
         const remainderDifferenceInMinutes = (timeDifference % millisecondsInOneHour) / millisecondsInOneMinute;
         const remainderDifferenceInSeconds = (timeDifference % millisecondsInOneMinute) / millisecondsInOneSecond;
         const remainingHours = Math.floor(remainderDifferenceInHours);
         const remainingMinutes = Math.floor(remainderDifferenceInMinutes);
         const remainingSeconds =Math.floor(remainderDifferenceInSeconds);
         // this.timerOutput =   remainingHours + ":" + remainingMinutes +  ":" + remainingSeconds ;
         this.remainingHours = remainingHours;
         this.remainingMinutes = remainingMinutes;
         this.remainingSeconds = remainingSeconds

      }
   },
   mounted() {
      this.startTimer()
      this.timer =setInterval(() => { this.startTimer() }, 1000);
   },
   beforeUnmount() {
      clearInterval(this.timer);
   }
}

</script>

<style scoped>
.count-box{
   background: rgba(255, 255, 255, 0.24);
   border: 1px solid rgba(255, 255, 255, 0.32);
}
</style>