
<template>

    <section>
<!--      <trade-messages trade_chat="asdsadasd" hash="6NGdUxmt655yn3" ></trade-messages>-->
        <div class="table-responsive"  v-if=this.open>
            <table class="table">

                <thead>
                <tr>
                    <th>{{ 'ID' }}</th>
                    <th>{{ 'Responder' }}</th>
                    <th>{{ 'Amount' }}</th>
                    <th>{{ 'Payment method' }}</th>
                    <th>{{ 'Margin' }}</th>
                    <th>{{ 'Offer-Type' }}</th>
                    <th>{{ 'Status' }}</th>
                    <th>{{ 'Action' }}</th>
                </tr>
                </thead>
                <tbody>
                <template v-for="(trade,index) in trades" >

                    <tr :id="'parent'+index"  v-bind:key="index"  onclick="expand_row(this)">
                        <td>{{ index }}</td>
                        <td>
                            <img v-if="trade[0].responder_avatar_url === '/2/images/avatar.png'" :src="placeholder_img"
                                 class="avatar img-fluid rounded-circle me-1" width="35" height="35">
                            <img v-else :src="trade[0].responder_avatar_url" class="avatar img-fluid rounded-circle me-1"
                                 width="35" height="35">
                            {{ trade[0].responder_username }}
                        </td>
                        <td>{{ trade[0].fiat_amount_requested + ' ' + trade[0].fiat_currency_code }}</td>
                        <td>{{ trade[0].payment_method_name }}</td>
                        <td>{{ trade[0].margin }}</td>
                        <td>{{ trade[0].offer_type }}</td>
                        <td>{{ trade[0].trade_status }}</td>
                        <td>
                            <span class="badge badge-primary">
                                             <i class="fa fa-image text-light"></i>
                                            {{ trade[0].total_attachments}}
                           </span>
                        </td>
                    </tr>
                    <tr class="hide-table-padding">
                        <td colspan="8">
                            <div :id="'child'+index" class="inner-row d-none">
                                <trade-messages :trade_chat=trade[1] :hash=index></trade-messages>
                            </div>
                        </td>
                    </tr>
                </template>

                </tbody>
            </table>
        </div>
        <div class="row" v-else>

            <div class="col-12">

                <div class="card border border-primary">
                    <!-- .card-body -->
                    <div class="card-body" style=" margin: auto;
                          width: 50%;
                          padding: 30px;
                        text-align: center;">
                        <h1 style="font-size: 5rem;">
                            <i class="fas fa-store-alt-slash"></i></h1>
                        <h3 class="state-header"
                            style="font-size: 2.5rem; font-weight: bold; margin-top:20px;"> {{ this.msg_title }} </h3>
                        <p class="state-description" style="font-size: 0.8rem;"> {{ this.msg_desc }} </p>

                    </div><!-- /.card-body -->
                </div>
            </div>
        </div >
    </section>
</template>


<script>


import Messages from './Messages'
import {Notyf} from 'notyf';
import 'notyf/notyf.min.css';

export default {

    props: ['apiRoute', 'placeholder_img', 'open_status', 'msg_title', 'msg_desc'],
    components: {
        'trade-messages': Messages,
    },
    data: function () {
        return {

            trades: {},
            audio: '',
            new_images: 0,
            open: null,
            load_images_in_first_load: 0,
        }
    },
    mounted() {
        this.getRecords();


        if (this.open_status == 0){
            this.open = 0;
        }
        else{
            this.open = 1;
        }
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
        }, 16000);

        console.log('Component mounted.');

        this.audio = new Audio('/assets/audio/beep2.wav');
    },

    methods: {


        playMusic() {
            console.log('Ringing');
            this.audio.play();
        },

        getRecords: function () {
            console.log('Running new trades');

            var url = '/paxful/trades/active2/' + this.load_images_in_first_load
            axios.get(url) //this.apiRoute

                .then((response) => {
                    this.load_images_in_first_load = 1;

                    console.log(Object.keys(this.trades).length, ' - ', Object.keys(response.data).length);

                    // 1. Response should not empty the response
                    // 2. Fire notification only after if there is new record

                    // this.$store.commit("update_trades", {data: response.data});

                    if (Object.keys(response.data).length != 0 && Object.keys(response.data).length > Object.keys(this.trades).length) {
                        //this.trades = response.data;
                        console.log('notification');
                        this.playMusic();
                        this.notyf.success("New trade notification");
                    }

                    this.trades = response.data;

                })
                .catch(error => {
                    console.log(error);
                    this.errored = true
                });
        },
    },

}
</script>
