<template>

    <div>

        <div class="row g-0">
            <div class="col-12 col-lg-5 col-xl-4">
                <div id="btn_release_div" class="d-flex mt-1">
                    <button class="btn btn-success mb-2 btn-lg me-1 px-3 mr-3" :disabled="release_btn_status"
                            v-on:click="release_coin">
                        Release
                    </button>
                    <input type="text" v-model="dispute_reason" class="form-control form-control-lg mb-2 mr-3"
                           placeholder="Dispute Reason">

                    <button class="btn btn-danger mb-2 btn-lg me-1 px-3" :disabled="dispute_btn_status"
                            v-on:click="dispute">
                        Dispute
                    </button>

                </div>


                <div class="py-2 px-4 border-bottom d-none d-lg-block">
                    <div class="d-flex align-items-start align-items-center py-1">
                    </div>
                </div>

                <div class="position-relative">
                    <div class="chat-messages p-4">

                        <div class="chat-message-left pb-4" v-for="image in this.trade_chat.attachments">
                            <div>
                                <img class="pic" :src=image style="width: 230px;" @click="() => showImg(index)">
                            </div>

                        </div>

                    </div>
                </div>

                <div class="flex-grow-0 py-3 px-4 border-top">
                    <div class="input-group">

                        <div>
                            <img v-if="previewImage" :src="previewImage" style="width: 230px;"/>
                            <input type="file" name="picture" class="mt-2" @change=ImagePreview>
                        </div>

                        <button type="submit" class="btn btn-primary mt-2" v-on:click="uploadImage">Upload Img
                        </button>


                    </div>
                </div>

            </div>
            <div class="col-12 col-lg-7 col-xl-8" style="border-left: 1px solid #dee6ed!important;">
                <div class="py-2 px-4 border-bottom d-lg-block">

                    <div class="d-flex align-items-start align-items-center py-1">
                        <div class="position-relative">

                            <div class="input-group mb-2">
                                <input type="text" class="form-control" placeholder="Card number" minlength="10"
                                       v-model="card_number">
                                <input type="text" class="form-control" placeholder="Month e.g. 12" minlength="2"
                                       v-model="month">
                                <input type="text" class="form-control" placeholder="Year e.g. 21" minlength="2"
                                       v-model="year">
                                <input type="text" class="form-control" placeholder="CVC" minlength="3" v-model="cvc">
                                <input type="text" class="form-control" placeholder="Amount" minlength="1"
                                       v-model="amount">

                                <button class="btn btn-primary ml-2" type="button" v-on:click="add_card">Add</button>
                                <button class="btn btn-secondary ml-2 fa fa-check" type="button"
                                        v-on:click="check_balance"></button>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-success" style="width:inherit;" v-if="card_accepted"><i
                                        class="fas fa-check"></i> Accepted
                                    </button>
                                    <button class="btn btn-danger" style="width:inherit;" v-if="card_rejected"><i
                                        class="fas fa-times"></i> Rejected
                                    </button>
                                    <button class="btn btn-warning" style="width:inherit;" v-if="card_pending"><i
                                        class="fas fa-exclamation"></i> Pending
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-danger mt-1" style="width:inherit;" v-if="balance_success"><i
                                        class="fas fa-money"></i> Balance {{ this.balance }}
                                    </button>
                                    <button class="btn btn-danger mt-1" style="width:inherit;" v-if="balance_failure"><i
                                        class="fas fa-money"></i> Balance: {{ this.balance }}
                                    </button>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="chat-messages p-4">

                    <div class="pb-4" :class=" (message.author==author) ? 'chat-message-right' : 'chat-message-left' "
                         v-if="message.type != 'trade_attach_uploaded' "
                         v-for="message in chat_message">
                        <div>

                            <!-- <img src="img/avatars/avatar-3.jpg" class="rounded-circle me-1" alt="Bertha Martin" width="40" height="40">-->
                            <div class="text-muted small text-nowrap mt-2">
                                {{ formatTime(message.timestamp) }}
                            </div>

                        </div>
                        <div class="flex-shrink-1 bg-light rounded py-2 px-3 ms-3">
                            <div class="fw-bold mb-1 font-weight-bold"> {{ message.author }}</div>
                            {{ message.text }}
                        </div>
                    </div>

                </div>

                <div class="flex-grow-0 py-3 px-4 border-top">
                    <div class="input-group">
                        <input type="text" class="form-control" v-model="msg" placeholder="Type your message"
                               v-on:keyup.enter="setMessage">
                        <button class="btn btn-primary" v-on:click="setMessage">Send</button>
                    </div>
                </div>

            </div>
        </div>

        <vue-easy-lightbox
            :visible="visible"
            :imgs="img_array"
            :index="index"
            @hide="handleHide"
        ></vue-easy-lightbox>
    </div>
</template>


<script>

import {Notyf} from 'notyf';
import 'notyf/notyf.min.css';

export default {

    props: ['trade_chat', 'hash'],
    data: function () {
        return {
            author: null,
            msg: null,
            previewImage: null,
            upload_img_url: null,
            upload_img_with_public_url: null,
            dispute_reason: '',

            card_number: '',
            cvc: null,
            month: null,
            year: null,
            amount: null,
            card_status: null,

            balance: null,
            balance_success: null,
            balance_failure: null,

            recent_msg: '',
            last_msg_array_count: 0,

            // For Image zoom component
            visible: false,
            index: 0,   // default: 0
            img_array: [],

        }
    },

    mounted() {

        console.log(this.trade_chat);
        this.author = this.trade_chat.author;

        this.notyf = new Notyf({
                duration: 20000,
                position: {
                    x: 'right',
                    y: 'top',
                },
                dismissible: true
            }
        );

        window.setInterval(() => {
            this.check_status()
        }, 10000);

        console.log('Message Component mounted.');
    },

    methods: {

        ImagePreview(e) {
            this.upload_img_url = e.target.files[0];
            const reader = new FileReader();
            reader.readAsDataURL(this.upload_img_url);
            reader.onload = e => {
                this.previewImage = e.target.result;
                this.upload_img_url = e.target.result;
            };
        },
        uploadImage() {

            const URL = '/paxful/trade/chat/img/upload';

            const data = new URLSearchParams();
            data.append('picture', this.upload_img_url);
            data.append('hash', this.hash);

            console.log(this.upload_img_url);


            axios.post(URL, data)
                .then((response) => {
                    console.log(response);
                    this.previewImage = '';
                })
                .catch(error => {
                    console.log(error);
                    //this.errored = true
                });


        },
        uploadImage_with_url(url) {

            const URL = '/paxful/trade/chat/img/upload2';

            console.log('Time: 5:27');

            const data = new URLSearchParams();
            data.append('file_url', url);
            data.append('hash', this.hash);


            axios.post(URL, data)
                .then((response) => {
                    console.log(response);
                    this.previewImage = '';
                    if (response.data.status == 1) {
                        this.notyf.success('Balance screenshot sent to trade chat');
                    }
                })
                .catch(error => {
                    console.log(error);
                    //this.errored = true
                });


        },

        setMessage: function () {
            console.log('Sending msg...');

            var url = '/paxful/trade/chat/send/' + this.hash + "/" + this.msg;
            console.log(url);
            this.recent_msg = this.msg;
            this.msg = '';
            let message = {text: this.recent_msg, author: this.author};
            this.trade_chat.messages.push(message);
            this.last_msg_array_count = this.trade_chat.messages.length;
            if (this.recent_msg.length > 0) {

                axios.get(url)
                    .then((response) => {
                        console.log(response.data);
                    })
                    .catch(error => {
                        console.log(error);
                    })
            }

        },
        formatTime: function (unix) {

            var date = new Date(unix * 1000);

            var hours = date.getHours();
            var minutes = "0" + date.getMinutes();
            var seconds = "0" + date.getSeconds();
            var formattedTime = hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);
            return formattedTime;

        },

        release_coin() {
            if (confirm('Do you really want to release the coins?')) {
                var URL = '/paxful/trade/release/payment/' + this.hash;
                this.notyf.success('Command sent successfully');
                axios.get(URL)
                    .then((response) => {
                        console.log(response);

                        if (response.data.status == 0) {
                            this.notyf.error(response.data.msg);
                        }
                        if (response.data.status == 1) {
                            this.notyf.success(response.data.msg);
                        }
                    });
            }

        },
        dispute() {
            if (confirm('Do you really want to open the dispute?')) {
                var URL = '/paxful/trade/dispute/' + this.hash + "/" + this.dispute_reason;
                this.notyf.success('Command sent successfully');
                axios.get(URL)
                    .then((response) => {

                        console.log(response);
                        if (response.data.status == 0) {
                            this.notyf.error(response.data.msg);
                        }
                        if (response.data.status == 1) {
                            this.notyf.success(response.data.msg);
                        }

                    });
            }


        },
        add_card() {
            this.notyf.success('Checking the card. Please wait....');
            var URL = '/paxful/trade/card/add';

            const data = new URLSearchParams();
            data.append('card_number', this.card_number);
            data.append('month', this.month);
            data.append('year', this.year);
            data.append('cvc', this.cvc);
            data.append('amount', this.amount);

            axios.post(URL, data)
                .then((response) => {
                    console.log('************');
                    console.log(response.data);

                    this.notyf.dismissAll();
                    if (response.data.status == 0) {

                        this.notyf.error(response.data.msg);
                        if (response.data.image) {
                            // alert(response.data.image);
                            // this.uploadImage_with_url(response.data.image)
                            console.log(response.data.image);
                        }

                    }
                    if (response.data.status == 1) {
                        this.notyf.success(response.data.msg);
                    }

                });
        },
        check_balance() {
            this.balance_failure = false;
            this.balance_success = false;
            this.notyf.success('Checking balance. Please wait....');
            var URL = '/paxful/trade/balance/check';

            const data = new URLSearchParams();
            data.append('card_number', this.card_number);
            data.append('month', this.month);
            data.append('year', this.year);
            data.append('cvc', this.cvc);
            data.append('amount', this.amount);

            axios.post(URL, data)
                .then((response) => {
                    console.log('************');
                    console.log(response.data);


                    if (response.data.status == 0) {

                        this.notyf.error(response.data.msg);
                        if (response.data.balance) {
                            this.balance = response.data.balance;
                            this.balance_failure = true;
                        }

                        if (response.data.image) {
                            // alert(response.data.image);
                            // this.uploadImage_with_url(response.data.image)
                            console.log(response.data.image);
                            console.log(response.data.balance);
                        }

                    }
                    if (response.data.status == 1) {
                        if (response.data.balance) {
                            this.balance = response.data.balance;
                            this.balance_success = true;
                        }
                        this.notyf.success(response.data.msg);

                        console.log(response.data.image);
                        console.log(response.data.balance);
                    }
                    if (response.data.message) {
                        this.notyf.error(response.data.message);
                    }

                });
        },
        check_status() {



            var URL = '/paxful/trade/card_status/' + this.card_number;

            console.log('changed', this.card_number);
            if (this.card_number.length > 14) {
                axios.get(URL)
                    .then((response) => {
                        console.log('************');
                        console.log(response.data);
                        this.card_status = response.data;

                    });
           }
        },

        // For Image Zoom Component
        showImg (index) {
            this.index = index
            this.visible = true
        },
        handleHide () {
            this.visible = false
        }

    },

    computed: {
        release_btn_status() {
            if (this.card_status != 'accepted') return true;
        },

        dispute_btn_status() {
            console.log('Dispute', this.dispute_reason.length);
            if (!this.dispute_reason.length > 0) return true;
        },

        card_accepted() {
            if (this.card_status == 'accepted') return true;
        },

        card_rejected() {
            if (this.card_status == 'declined') return true;
        },

        card_pending() {
            if (this.card_status == 'pending') return true;
        },

        status_class() {
            if (this.card_status == 'accepted') {
                return 'btn-success';
            } else if (this.card_status == 'declined') {
                return 'btn-danger';
            } else {
                return 'btn-warning';
            }

        },

        chat_message() {

            this.img_array = [];
            for(let i=0; i<Object.keys(this.trade_chat.attachments).length; i++){

                // Push the new object into the array of objects
                this.img_array.push(this.trade_chat.attachments[i]);
            }


            if (this.trade_chat.messages.length < this.last_msg_array_count) {
                let message = {text: this.recent_msg, author: this.author};
                this.trade_chat.messages.push(message);
            }
            return this.trade_chat.messages;
        }
    },





}
</script>
