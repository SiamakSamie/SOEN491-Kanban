<template>
    <div v-if="dashboardData !== null">
        <div class="mx-10 my-3 space-y-5 shadow-xl p-5 bg-white">
            <actions :boardsLength="dashboardData.boards.length"></actions>
            <add-or-edit-board-modal></add-or-edit-board-modal>
        </div>
    </div>
</template>

<script>

import {ajaxCalls} from "../../mixins/ajaxCallsMixin";
import Actions from "./dashboardComponents/Actions.vue";
import AddOrEditBoardModal from "./dashboardComponents/AddOrEditBoardModal";

export default {
    inject: ["eventHub"],
    components: {
        AddOrEditBoardModal,
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
        this.eventHub.$on("save-board", (boardData) => {
            this.saveBoard(boardData);
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

