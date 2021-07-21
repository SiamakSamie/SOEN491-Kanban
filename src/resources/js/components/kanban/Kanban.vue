<template>
    <div v-if="kanban !== null">
        <kanban-bar :kanbanId="kanban.id"
                    :kanbanMembers="kanban.members"
                    :kanbanName="kanban.name"
                    :loadingMembers="loadingMembers"></kanban-bar>

        <add-member-modal :kanbanData="kanban"></add-member-modal>
    </div>
</template>

<script>

import KanbanBar from "./kanbanComponents/KanbanBar.vue";
import {ajaxCalls} from "../../mixins/ajaxCallsMixin";
import AddMemberModal from "./kanbanComponents/AddMemberModal";

export default {

    inject: ["eventHub"],
    mixins: [ajaxCalls],


    props: {'id': Number},

    components: {
        KanbanBar,
        AddMemberModal
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

    watch: {
        id: function (newVal) {
            this.getKanban(newVal);
        }
    },

    created() {
        this.eventHub.$on("save-members", (selectedMembers) => {
            this.saveMember(selectedMembers);
        });
        this.eventHub.$on("remove-member", (memberData) => {
            this.deleteMember(memberData);
        });
    },

    beforeDestroy() {
        this.eventHub.$off('save-members');
        this.eventHub.$off('remove-member');
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

        saveMember(selectedMembers) {
            this.loadingMembers = {memberId: null, isLoading: true}
            const cloneSelectedMembers = {...selectedMembers};
            this.asyncAddMembers(cloneSelectedMembers, this.kanban.id
            ).then(() => {
                this.asyncGetMembers(this.kanban.id).then((data) => {
                    this.kanban.members = data.data;
                    this.loadingMembers = {memberId: null, isLoading: false}
                    this.triggerSuccessToast('New kanban members saved')

                }).catch(res => {
                    console.log(res)
                });
            }).catch(res => {
                console.log(res)
            });
        },

        deleteMember(member) {
            this.asyncDeleteMember(member.id).then(() => {
                this.loadingMembers = {memberId: member.id, isLoading: true}
                this.asyncGetMembers(this.kanban.id).then((data) => {
                    this.kanban.members = data.data;
                    this.loadingMembers = {memberId: null, isLoading: false};
                    this.triggerSuccessToast('Kanban member deleted')
                }).catch(res => {
                    console.log(res)
                });
            }).catch(res => {
                console.log(res)
            });
            this.getKanban(this.kanban.id);
        },
    }

};
</script>
