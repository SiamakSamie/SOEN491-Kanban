<template>
    <div v-if="kanban !== null">
        <kanban-bar :kanbanId="kanban.id"
                    :kanbanMembers="kanban.members"
                    :kanbanName="kanban.name"
                    :loadingMembers="loadingMembers"></kanban-bar>

        <div :key="row.id" class="p-2" v-for="(row, rowIndex) in kanban.rows">

            <div class="border bg-gray-700 pl-3 pr-3 rounded py-2 flex justify-between"
                 v-if="loadingRow.rowId === row.id && loadingRow.isLoading ">
                <h2 class="text-gray-100 font-medium tracking-wide animate-pulse">
                    Loading... </h2>
            </div>

            <div class="border bg-gray-700 pl-3 pr-3 rounded py-2 flex justify-between" v-else>
                <h2 class="text-gray-100 font-medium tracking-wide">
                    {{ row.name }} </h2>

                <a @click="createRowAndColumns(rowIndex, row.columns, row.id, row.name)"
                   class="px-2 text-gray-500 hover:text-gray-400 transition duration-300 ease-in-out focus:outline-none">
                    <i class="fas fa-columns"></i>
                </a>
            </div>


    </div>

        <hr class="mt-5"/>
        <button @click="createRowAndColumns(kanban.rows.length, [], null, null)"
                class="text-gray-500 hover:text-gray-600 font-semibold font-sans tracking-wide bg-gray-200 rounded-lg rounded p-4 m-10 hover:bg-blue-200 mouse transition ease-in duration-200 focus:outline-none">
            <p class="font-bold inline">Create new row</p>
            <i class="pl-2 fas fa-plus"></i>
        </button>

        <add-row-and-columns-modal :kanbanData="kanban"></add-row-and-columns-modal>
    </div>
</template>

<script>

import draggable from "vuedraggable";

import KanbanBar from "./kanbanComponents/KanbanBar.vue";
import {ajaxCalls} from "../../mixins/ajaxCallsMixin";
import AddMemberModal from "./kanbanComponents/AddMemberModal";
import AddRowAndColumnsModal from "./kanbanComponents/AddRowAndColumnsModal";

export default {

    inject: ["eventHub"],
    mixins: [ajaxCalls],


    props: {'id': Number},

    components: {
        KanbanBar,
        AddMemberModal,
        AddRowAndColumnsModal,
        draggable,
    },

    data() {
        return {
            kanban: null,
            loadingMembers: {memberId: null, isLoading: false},
            loadingRow: {rowId: null, isLoading: false},
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
        this.eventHub.$on("save-row-and-columns", (rowData) => {
            this.saveRowAndColumns(rowData);
        });
        this.eventHub.$on("delete-row", (rowData) => {
            this.saveRowAndColumns(rowData);
        });

    },

    beforeDestroy() {
        this.eventHub.$off('save-members');
        this.eventHub.$off('remove-member');
        this.eventHub.$off('save-row-and-columns');
    },

    methods: {

        getKanban(kanbanID) {
            this.asyncGetKanbanData(kanbanID).then((data) => {
                this.kanban = data.data;
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

        createRowAndColumns(rowIndex, rowColumns, rowId, rowName, boardId = this.kanban.id) {
            this.eventHub.$emit("create-row-and-columns", {
                rowIndex,
                rowColumns,
                rowId,
                rowName,
                boardId
            });
        },

        saveRowAndColumns(rowData) {
            const cloneRowData = {...rowData};
            let rowIndex = cloneRowData.rowIndex;

            if (cloneRowData.rowId !== null)
                this.loadingRow = {rowId: this.kanban.rows[rowIndex].id, isLoading: true}

            this.asyncCreateRowAndColumns(cloneRowData).then((data) => {

                if (cloneRowData.rowId !== null) {
                    this.kanban.rows[rowIndex] = data.data[0];

                } else {
                    this.kanban.rows.push(data.data[0]);
                }
                this.loadingRow = {rowId: null, isLoading: false}

            }).catch(res => {
                console.log(res)
            });
        },

        deleteRow(rowData) {
            this.asyncDeleteRow(rowData.id).then(() => {
                this.triggerSuccessToast('Row Deleted')
                this.getKanban(this.kanban.id);

            }).catch(res => {
                console.log(res)
            });
        },
    }

};
</script>
