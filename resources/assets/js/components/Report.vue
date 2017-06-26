<template>

    <div>
        <button v-on:click="getReport()">View Report</button>
        <div id="highChartsBox"></div>
    </div>

</template>



<script>

    var Highcharts = require('highcharts');
    import axios from 'axios';

    export default {
        data: function(){
            return {
                message : []
            }
        },
        methods:{

            getReport:function () {

                axios.get('report/get-report').then(response => {
                    this.reportData =response.data;
                    Highcharts.chart('highChartsBox', this.reportData);
                }).catch(e => {
                    console.log(e)
                });

            }

        }
    }

</script>
