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

                    <div class="chat-message-left pb-4" v-for="image in attachments">
                        <div>
                            <img src="" class="rounded-circle me-1"
                                 width="35" height="35">
                        </div>

                    </div>

                </div>
            </div>

            <div class="flex-grow-0 py-3 px-4 border-top">
                <div class="input-group">
                    <button class="btn btn-primary">Upload Image</button>
                </div>
            </div>

        </div>
        <div class="col-12 col-lg-7 col-xl-9">
            <div class="py-2 px-4 border-bottom d-none d-lg-block">
                <div class="d-flex align-items-start align-items-center py-1">
                    <div class="position-relative">
                    </div>
                    <div>
                        <button class="btn btn-primary btn-lg me-1 px-3">
                            Release
                        </button>
                        <button class="btn btn-info btn-lg me-1 px-3 d-none d-md-inline-block">
                            Dispute
                        </button>
                    </div>

                </div>
            </div>

            <div class="input-group mb-3">
                <select class="form-select">
                    <option>Select...</option>
                    <option>One</option>
                    <option>Two</option>
                    <option>Three</option>
                </select>
                <input type="text" class="form-control" placeholder="Search for...">
                <button class="btn btn-secondary" type="button">Go!</button>
            </div>

            <div class="chat-messages p-4">

                <div class="pb-4" :class=" (message.author==author) ? 'chat-message-right' : 'chat-message-left' "
                     v-for="message in chat">
                    <div>

                        <div class="text-muted small text-nowrap mt-2" :format="formatOptions"> {{
                                message.timestamp
                            }}
                        </div>

                    </div>
                    <div class="flex-shrink-1 bg-light rounded py-2 px-3 ms-3">
                        <img :src=file.full_url class="rounded-circle me-1"
                             v-if="message.type == 'trade_attach_uploaded' " v-for="file in message.text.files"
                             width="35" height="35">
                        <div class="fw-bold mb-1 font-weight-bold"> {{ message.author }}</div>
                        {{ message.text }}
                    </div>
                </div>


            </div>

            <div class="flex-grow-0 py-3 px-4 border-top">
                <div class="input-group">
                    <input type="text" class="form-control" v-model.lazy="msg" placeholder="Type your message">
                    <button class="btn btn-primary" v-on:click="setMessage">Send</button>
                </div>
            </div>

        </div>
    </div>
    </div>
</template>


<script>

export default {

    props: ['apiRoute', 'hash'],
    data: function () {
        return {
            loading: true,
            errored: false,
            chat: [],
            attachments: [],
            author: null,
            formatOptions: {format: "MM-dd-yyyy", type: "date"},
            msg: null
        }
    },

    mounted() {


        //this.getMessages();
        //  window.setInterval(() => {
        //      this.getMessages()
        //  }, 5000);

        //console.log('Component mounted.');
    },
    methods: {
        getMessages: function () {
            console.log('Running');

            axios.get(this.apiRoute)

                .then((response) => {
                    console.log(response.data);
                    this.chat = response.data.messages;
                    this.attachments = response.data.attachments;
                    this.author = response.data.author;
                })
                .catch(error => {
                    console.log(error);
                    this.errored = true
                })

                .finally(() => {
                    this.loading = false;
                });
        },
        setMessage: function () {
            console.log('Sending msg');

            axios.get(this.hash + '/' + this.msg)

                .then((response) => {
                    console.log(response.data);

                })
                .catch(error => {
                    console.log(error);
                })
        },
    },

}
</script>
