<template>
    <div v-if="kanban !== null">
        <kanban-bar :kanbanId="kanban.id"
                    :kanbanMembers="kanban.members"
                    :kanbanName="kanban.name"
                    :loadingMembers="loadingMembers"></kanban-bar>
    </div>
</template>

<script>

import KanbanBar from "./kanbanComponents/KanbanBar.vue";
import {ajaxCalls} from "../../mixins/ajaxCallsMixin";

export default {

    inject: ["eventHub"],
    mixins: [ajaxCalls],


    props: {'id': Number},

    components: {
        KanbanBar,
    },

    data() {
        return {
            kanban: null,
            loadingMembers: {memberId: null, isLoading: false},
        };
    },

    mounted() {
        this.getKanban(this.id);
    },

    methods: {

        getKanban(kanbanID) {
            this.eventHub.$emit("set-loading-state", true);

            this.asyncGetKanbanData(kanbanID).then((data) => {
                this.kanban = data.data;
                this.eventHub.$emit("set-loading-state", false);

            }).catch(res => {
                console.log(res)
            });
        },

    }

    };
</script>
