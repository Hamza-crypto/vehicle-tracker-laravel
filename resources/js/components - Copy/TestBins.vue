<template>

    <section v-if="errored">
        <p>We're sorry, we're not able to retrieve this information at the moment, please try back
            later</p>
    </section>

    <section v-else>
        <div v-if="loading">Loading...</div>

        <div v-else>
            <table id="bins-table" class="table table-striped" style="width:100%">
                <thead>
                <tr>
                    <th>Isndex</th>
                    <th>ID</th>
                    <th>BIN</th>
                    <th>Created at</th>

                </tr>
                </thead>

                <tbody>
                <tr v-for="(bin,index) in trades" :key="index">
                    <td>{{ index }}</td>
                    <td>{{ bin.id }}</td>
                    <td>{{ bin.number + ' -- ' + bin.number }}</td>
                    <td>{{ bin.created_at }}</td>


                </tr>
                </tbody>
            </table>
        </div>

    </section>


</template>


<script>

import {Notyf} from 'notyf';
import 'notyf/notyf.min.css'; // for React, Vue and Svelte


export default {
    data: function () {
        return {
            notyf: null,
            sound: null,
            loading: true,
            errored: false,
            trades: [],

        }
    },
    mounted() {

        this.notyf = new Notyf({
                duration: 10000,
                position: {
                    x: 'right',
                    y: 'top',
                },
                dismissible: true
            }
        );

        window.setInterval(() => {
            this.getRecords()
        }, 1000);


    },
    methods: {

        getRecords: function () {
            // console.log('Running');

            axios.get('paxful/trades/active2')

                .then((response) => {
                    console.log(this.trades.length, ' - ', response.data.length);

                    // 1. Response should not empty the response
                    // 2. Fire notification only after if there is new record

                    if (response.data.length != 0 && response.data.length > this.trades.length) {
                        this.trades = response.data;
                        console.log('notification');
                        this.notyf.success("New trade notification");


                    } else {
                        this.trades = response.data;
                    }

                })

                .catch(error => {
                    console.log(error);
                    this.errored = true
                })

                .finally(() => {
                    this.loading = false;
                });
        },


    },

}
</script>
