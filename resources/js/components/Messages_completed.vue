<template>

    <div>

        <div class="row g-0">
            <div class="col-12 col-lg-5 col-xl-4">
                <div id="btn_release_div" class="d-flex mt-1">


                </div>


                <div class="py-2 px-4 border-bottom d-none d-lg-block">
                    <div class="d-flex align-items-start align-items-center py-1">
                        Attachments
                    </div>
                </div>

                <div class="position-relative">
                    <div class="chat-messages p-4">

                        <div class="chat-message-left pb-4" v-for="image in this.trades.attachments">
                            <div>
                                <img class="pic" :src=image style="width: 230px;" @click="() => showImg(index)">
                            </div>

                        </div>

                    </div>
                </div>

                <div class="flex-grow-0 py-3 px-4 border-top">
                </div>

            </div>
            <div class="col-12 col-lg-7 col-xl-8" style="border-left: 1px solid #dee6ed!important;">
                <div class="py-2 px-4 border-bottom d-lg-block">

                    <div class="d-flex align-items-start align-items-center py-1">
                        <div class="position-relative">

                            <div class="input-group mb-2">

                            </div>
                            <div class="row">
                            </div>
                            <div class="row">
                                Messages
                            </div>
                        </div>


                    </div>
                </div>

                <div class="chat-messages p-4">

                    <div class="pb-4" :class=" (message.author==author) ? 'chat-message-right' : 'chat-message-left' "
                         v-if="message.type != 'trade_attach_uploaded' "
                         v-for="message in this.trades.messages">
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


export default {

    props: ['hash', 'author'],
    data: function () {
        return {
            trades: [],
            load_images_in_first_load: 0,
            visible: false,
            index: 0,   // default: 0
            img_array: [],   // default: 0
        }
    },

    mounted() {
        this.getRecords();
    },

    methods: {
        getRecords: function () {
            console.log('Running new trades');

            var url = '/paxful/trade/chat/vue/' + this.hash + '/' + this.author + '/' + this.load_images_in_first_load;
            axios.get(url) //this.apiRoute

                .then((response) => {

                    this.load_images_in_first_load = 1;
                    this.getRecordsforImg();

                    console.log(Object.keys(this.trades).length, ' - ', Object.keys(response.data).length);

                    this.trades = response.data;

                })
                .catch(error => {
                    console.log(error);
                    this.errored = true
                });
        },

        getRecordsforImg: function () {
            console.log('Running new trades');

            var url = '/paxful/trade/chat/vue/' + this.hash + '/' + this.author + '/' + this.load_images_in_first_load;
            axios.get(url) //this.apiRoute

                .then((response) => {

                    this.trades = response.data;

                    for(let i=0; i<Object.keys(this.trades.attachments).length; i++){

                        // Push the new object into the array of objects
                        this.img_array.push(this.trades.attachments[i]);
                    }

                })
                .catch(error => {
                    console.log(error);
                    this.errored = true
                });
        },
        formatTime: function (unix) {

            var date = new Date(unix * 1000);

            var hours = date.getHours();
            var minutes = "0" + date.getMinutes();
            var seconds = "0" + date.getSeconds();
            var formattedTime = hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);
            return formattedTime;

        },

        showImg (index) {
            this.index = index
            this.visible = true
        },
        handleHide () {
            this.visible = false
        }

    },






}
</script>
