export default {

    // state: {
    //     trade_counter: 0,
    //     counter: 0,
    //     global_previous_hash: '',
    //     global_current_hash: '',
    //     author: '',
    //     trades: {},
    //     messages: [],
    //     images: [],
    // },
    //
    // getters: {
    //
    //     getMessagesFromGetter(state) {
    //         var hash = state.global_current_hash;
    //         var messages = state.messages[hash];
    //         console.log('data123', hash, messages);
    //         return messages;
    //     },
    //
    //     getImagesFromGetter(state) {
    //         var hash = state.global_current_hash;
    //         var images = state.images[hash];
    //         console.log('data123', hash, images);
    //         return images;
    //     },
    //
    //     getTradesFromGetter(state) {
    //         return state.trades;
    //     }
    // },
    //
    // mutations: {
    //
    //     update_hash(state, hash) {
    //         state.counter++;
    //
    //         state.global_previous_hash = state.global_current_hash;
    //         state.global_current_hash = hash;
    //
    //         const rows = document.querySelectorAll("table > tbody > tr");
    //
    //         rows.forEach((row) => {
    //             row.style.backgroundColor='';
    //             row.style.color = "#6c757d";
    //         });
    //
    //         document.getElementById(hash).style.backgroundColor = "#4bbf73";
    //         document.getElementById(hash).style.color = "#FFF";
    //
    //
    //         console.log("New hash inside index.js ",  state.global_current_hash);
    //     },
    //
    //     update_messages(state, {data, hash}) {
    //         state.counter++;
    //         state.global_current_hash = hash;
    //         var a = state.messages[hash] = data;
    //         console.log(" update_messages",state.global_current_hash,a);
    //         return a;
    //     },
    //
    //     update_images(state, {data, hash}) {
    //         state.counter++;
    //         state.global_current_hash = hash;
    //         var a = state.images[hash] = data;
    //         console.log(" update_images",state.global_current_hash,a);
    //         return a;
    //     },
    //
    //     update_trades(state, {data}) {
    //         state.trade_counter++;
    //         state.trades = data;
    //     },
    //
    // }
}
