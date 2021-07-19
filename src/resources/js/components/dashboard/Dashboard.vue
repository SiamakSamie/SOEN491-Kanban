<template>
    <div>
        <div class="mx-10 my-3 space-y-5 shadow-xl p-5 bg-white">
            <actions :boardsLength="dashboardData.boards.length"></actions>
        </div>
    </div>
</template>

<script>

import {ajaxCalls} from "../../mixins/ajaxCallsMixin";
import Actions from "./dashboardComponents/Actions.vue";


export default {
    inject: ["eventHub"],
    components: {
        Actions,
    },
    mixins: [ajaxCalls],

    mounted() {
        this.getDashboardData();
    },

    data() {
        return {
            dashboardData: null,
        };
    },

    created() {

        this.eventHub.$on("save-board", (kanbanData) => {
            this.saveBoard(kanbanData);
        });
    },

    beforeDestroy(){
        this.eventHub.$off('save-board');
    },

    methods: {
        saveBoard(kanbanData) {
            this.loadingBoard = true
            const cloneKanbanData = {...kanbanData};
            this.asyncCreateBoard(cloneKanbanData).then(res => {
                this.asyncGetBoards().then((data) => {
                    this.dashboardData.boards = data.data;
                    this.loadingBoard = false;
                }).catch(res => {
                    console.log(res)
                });
            });
        },

        getDashboardData() {
            this.asyncGetDashboardData().then((data) => {
                this.dashboardData = data.data;
            }).catch(res => {console.log(res)});
        },
    }
};
</script>

