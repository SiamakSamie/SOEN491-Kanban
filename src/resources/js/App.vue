<template>
    <div class="flex" id="app">
        <div class="flex min-h-screen">
            <side-bar></side-bar>
        </div>
        <div>
            <router-view></router-view>
        </div>

    </div>
</template>

<script>
import SideBar from "./components/global/SideBar.vue";

export default {
    name: "App",
    components: {
        SideBar,
    },

};
</script>
