import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import 'vue-select/dist/vue-select.css';
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

import "@fortawesome/fontawesome-free/js/all.js";

Vue.config.productionTip = false;

Vue.use(Toast, {
    transition: "Vue-Toastification__fade",
    maxToasts: 20,
    newestOnTop: true
});

new Vue({
    router,
    render: (h) => h(App)
}).$mount("#app");
