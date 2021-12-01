<template>

    <div>
        <section v-if="errored">
            <p>We're sorry, we're not able to retrieve this information at the moment, please try back
                later</p>
        </section>
        <div class="row g-0" v-else>
            <div class="col-12 col-lg-5 col-xl-3">
                <div class="py-2 px-4 border-bottom d-none d-lg-block">
                    <div class="d-flex align-items-start align-items-center py-1">
                    </div>
                </div>

                <div class="position-relative">
                    <div class="chat-messages p-4">

                        <div class="chat-message-left pb-4" v-for="image in attachments2">
                            <div>
                                <img :src=image style="width: 230px;">
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

                        <button type="submit" class="btn btn-primary mt-2" v-on:click="uploadImage">Upload Image
                        </button>


                    </div>
                </div>

            </div>
            <div class="col-12 col-lg-7 col-xl-9" style="border-left: 1px solid #dee6ed!important;">
                <div class="py-2 px-4 border-bottom d-lg-block">

                    <div class="d-flex align-items-start align-items-center py-1">
                        <div class="position-relative">
                            <div class="d-flex" id="btn_release_div">
                                <button class="btn btn-success mb-2 btn-lg me-1 px-3 mr-3" v-on:click="release_coin">
                                    Release
                                </button>
                                <input type="text" v-model="dispute_reason" class="form-control form-control-lg mb-2 mr-3" placeholder="Dispute Reason">

                                <button class="btn btn-danger mb-2 btn-lg me-1 px-3" v-on:click="dispute">
                                    Dispute
                                </button>


                            </div>

                            <div class="input-group mb-2 mt-4">
                                <input type="text" class="form-control" placeholder="Card number">
                                <input type="text" class="form-control" placeholder="CVC">
                                <input type="text" class="form-control" placeholder="12 / 30">
                                <input type="text" class="form-control" placeholder="Amount">
                                <button class="btn btn-secondary fa fa-check" type="button"></button>
                                <button class="btn btn-primary" type="button">Add</button>
                            </div>
                        </div>


                    </div>
                </div>


                <div class="chat-messages p-4">

                    <div class="pb-4" :class=" (message.author==author) ? 'chat-message-right' : 'chat-message-left' "
                         v-if="message.type != 'trade_attach_uploaded' "
                         v-for="message in chat">
                        <div>

                            <!--                            <img src="img/avatars/avatar-3.jpg" class="rounded-circle me-1" alt="Bertha Martin" width="40" height="40">-->
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

    </div>
</template>


<script>

import {Notyf} from 'notyf';
import 'notyf/notyf.min.css';

export default {

    props: ['hash'],
    data: function () {
        return {
            loading: true,
            errored: false,
            chat: [],
            attachments: [],
            author: null,
            msg: null,
            attachments2: [],
            attachments_hashes: [],
            previewImage: null,
            upload_img_url: null,
            dispute_reason: null,

        }
    },

    mounted() {

        this.getMessages();

        window.setInterval(() => {
            this.getMessages()
        }, 2000);


        window.setInterval(() => {
            this.getImages()
        }, 3000);

        this.notyf = new Notyf({
                duration: 10000,
                position: {
                    x: 'right',
                    y: 'top',
                },
                dismissible: true
            }
        );

        //console.log('Component mounted.');
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

            console.log('Time: 5:27');

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
        getMessages: function () {
            console.log('Getting Messages...');

            var url = '/paxful/trade/chat/vue/' + this.hash;
            axios.get(url)

                .then((response) => {

                    this.chat = response.data.messages;
                    this.author = response.data.author;
                    this.attachments = response.data.attachments;
                })
                .catch(error => {
                    console.log(error);
                    //this.errored = true
                })
        },
        setMessage: function () {
            console.log('Sending msg...');

            var url = '/paxful/trade/chat/send/' + this.hash + "/" + this.msg;

            var m = this.msg;
            this.msg = '';
            let person = {text: m, author: this.author};
            this.chat.push(person);
            console.log(url);
            if (m.length > 0) {

                axios.get(url)
                    .then((response) => {
                        console.log(response);
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
        getImages: function () {
            console.log('Getting Images...');

            this.attachments_hashes = [];
            this.attachments.forEach((abc) => {
                this.attachments_hashes.push(abc.image_hash)
            });

            var url = '/paxful/getimages/' + this.attachments_hashes;

            if (this.attachments_hashes.length > 0) {
                axios.get(url)
                    .then((response) => {
                        //console.log(response.data);
                        this.attachments2 = response.data;

                    })
                    .catch(error => {
                        console.log(error);
                        //this.errored = true
                    })
            }

        },
        release_coin() {
            var URL = '/paxful/trade/release/payment/' + this.hash;
            axios.get(URL)
                .then((response) => {
                    console.log(response);

                    if(response.data.error){
                        this.notyf.error(response.data.error);
                    }
                    if(response.data.success){
                        this.notyf.success(response.data.success);
                    }
                });
        },

        dispute() {
            var URL = '/paxful/trade/dispute/' + this.hash + "/" + this.dispute_reason;
            axios.get(URL)
                .then((response) => {

                    console.log(response);
                    if(response.data.error){
                        this.notyf.error(response.data.error);
                    }
                    if(response.data.success){
                        this.notyf.success(response.data.success);
                    }

                });
        },
    },
    watch: {

    }

}
</script>
