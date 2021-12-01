<template>

    <!--    <section v-if="errored">-->
    <!--        <p>We're sorry, we're not able to retrieve this information at the moment, please try back-->
    <!--            later</p>-->
    <!--    </section>-->

    <!--    <section v-else>-->
    <!--        <div v-if="loading">Loading...</div>-->

    <div>
        <table id="trades-table" class="table table-striped responsive" style="width:100%">
            <thead>
            <tr>
                <th>{{ 'ID' }}</th>
                <th>{{ 'Responder' }}</th>
                <th>{{ 'Amount' }}</th>
                <th>{{ 'Payment method' }}</th>
                <th>{{ 'Margin' }}</th>
                <th>{{ 'Offer Type' }}</th>
                <th>{{ 'Status' }}</th>
                <th>{{ 'Action' }}</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(trade,index) in trades" :id=trade.trade_hash>

                <td>{{ (index + 1) }}</td>
                <td>

                    <img v-if="trade.responder_avatar_url === '/2/images/avatar.png'" :src="placeholder_img"
                         class="avatar img-fluid rounded-circle me-1" width="35" height="35">
                    <img v-else :src="trade.responder_avatar_url" class="avatar img-fluid rounded-circle me-1"
                         width="35" height="35">
                    {{ trade.responder_username }}
                </td>
                <td>{{ trade.fiat_amount_requested + ' ' + trade.fiat_currency_code }}</td>
                <td>{{ trade.payment_method_name }}</td>
                <td>{{ trade.margin }}</td>
                <td>{{ trade.offer_type }}</td>
                <td>{{ trade.trade_status }}</td>

                <td>
                    <a v-on:click="set_new_hash(trade.trade_hash)" class="btn" style="display: inline">
                        <i class="fa fa-envelope text-info"></i>
                    </a>

                </td>

            </tr>

            </tbody>
        </table>

            <trade-messages hash="FRD879D"></trade-messages>
<!--            <trade-messages :hash=this.current_hash></trade-messages>-->


    </div>

    <!--    </section>-->


</template>


<script>
import Messages from './Messages'
import {Notyf} from 'notyf';
import 'notyf/notyf.min.css';

export default {

    props: ['apiRoute', 'placeholder_img'],
    components: {
        'trade-messages': Messages
    },
    data: function () {
        return {
            loading: true,
            errored: false,
            trades: [],
            current_hash: '',
            previous_hash: '',
            audio: '',
        }
    },
    mounted() {
        console.log('Will it play here?? lol');
        console.log('Play outside of');

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
        }, 5000);

        console.log('Component mounted. 9:33');

        this.audio = new Audio('/assets/audio/beep2.wav');
    },
    methods: {

        playMusic() {
            console.log('Ringing');
            this.audio.play();
        },

        set_new_hash(hash) {
            this.previous_hash = this.current_hash
             if (this.previous_hash){
                 document.getElementById(this.previous_hash).style.backgroundColor = "";
                 document.getElementById(this.previous_hash).style.color = "#6c757d";
             }


            this.current_hash = hash;
            document.getElementById(this.current_hash).style.backgroundColor = "#4bbf73";
            document.getElementById(this.current_hash).style.color = "#FFF";
        },


        getRecords: function () {
            console.log('Running');

            //axios.get(this.apiRoute)
            axios.get('api/users')

                .then((response) => {
                    console.log(this.trades.length, ' - ', response.data.length);

                    // 1. Response should not empty the response
                    // 2. Fire notification only after if there is new record


                    if (response.data.length != 0 && response.data.length > this.trades.length) {
                        this.trades = response.data;
                        console.log('notification');
                        this.playMusic();
                        this.notyf.success("New trade notification");


                    } else {
                        this.trades = response.data;
                    }

                })
                .catch(error => {
                    console.log(error);
                    this.errored = true
                });
        },
    },

}
</script>
