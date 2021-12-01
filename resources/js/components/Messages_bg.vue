<template>

</template>

<script>

export default {

    props: ['hash'],
    data: function () {
        return {
            categories: [],
            attachments: [],
        }
    },

    mounted() {

        this.getMessages();

        console.log('Message BG  Component mounted.' + this.hash);
    },

    methods: {

        getMessages: function () {
            console.log('Getting Messages BG...'  + this.hash);

            var url = '/paxful/trade/chat/vue/' + this.hash;

            axios.get(url)

                .then((response) => {

                    this.author = response.data.author;
                    this.attachments = response.data.attachments;

                    this.$store.commit("update_messages", {data: response.data.messages, hash: this.hash});

                    console.log('special Response... ', response.data.messages);

                    this.getImages(this.hash);


                })
                .catch(error => {

                })
        },

        getImages: function (hash) {
            console.log('Getting Images BG ...');

            this.attachments_hashes = [];
            this.attachments.forEach((abc) => {
                this.attachments_hashes.push(abc.image_hash)
            });

            var url = '/paxful/getimages/' + this.attachments_hashes;

           // if (this.attachments_hashes.length > 0) {
                axios.get(url)
                    .then((response) => {
                        //console.log(response.data);
                        this.$store.commit("update_images", {data: response.data, hash: hash});

                    })
                    .catch(error => {
                        console.log(error);
                        //this.errored = true
                    })
           // }
        },
    },
}
</script>
