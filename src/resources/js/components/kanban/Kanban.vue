<template>
    <div v-if="kanban !== null">
        <kanban-bar :kanbanId="kanban.id"
                    :kanbanMembers="kanban.members"
                    :kanbanName="kanban.name"
                    :loadingMembers="loadingMembers"></kanban-bar>

        <draggable @end="getRowChangeData($event)"
                   :animation="200"
                   :list="kanban.rows"
                   class="h-full list-group"
                   :disabled="isDraggableDisabled"
        >

            <div :key="row.id" class="mx-10 my-3" v-for="(row, rowIndex) in kanban.rows">

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
                        <i class="fas fa-business-time"></i>
                    </a>
                </div>
                <div class="flex flex-wrap">
                    <div class="space-x-2  flex flex-1 flex-col pt-3 pb-2 overflow-x-auto overflow-y-hidden">

                        <draggable :animation="200"
                                   class="h-full list-group flex"
                                   :list="row.columns"
                                   :group="'row-'+ row.id"
                                   :disabled="isDraggableDisabled"
                                   @end="getColumnChangeData($event, rowIndex)">
                            <div :key="column.id"
                                 class="flex-1 bg-gray-200 px-3 py-3 column-width rounded mr-4"
                                 v-for="(column, columnIndex) in row.columns">
                                <div class="flex" v-if="loadingCards.columnId === column.id && loadingCards.isLoading ">
                                    <p class="flex-auto text-gray-700 font-semibold font-sans tracking-wide pt-1 animate-pulse">
                                        Loading... </p>
                                </div>
                                <div class="flex" v-else>

                                    <p class="flex-auto text-gray-700 font-semibold font-sans tracking-wide pt-1">
                                        {{ column.name }} </p>

                                    <button @click="createTask(rowIndex, columnIndex)"
                                            class="w-6 h-6 bg-blue-200 rounded-full hover:bg-blue-300 mouse transition ease-in duration-200 focus:outline-none">
                                        <i class="fas fa-plus text-white"></i>
                                    </button>
                                </div>
                                <draggable :animation="200"
                                           :disabled="isDraggableDisabled"
                                           :list="column.tasks"
                                           @change="getTaskChangeData($event, columnIndex, rowIndex)"
                                           class="h-full list-group"
                                           group="tasks">

                                    <task-card :class="{'opacity-60':isDraggableDisabled}"
                                        v-for="task in column.tasks"
                                        :key="task.id"
                                        :task="task"
                                        class="mt-3 cursor-move"
                                    ></task-card>

                                </draggable>
                            </div>
                        </draggable>
                    </div>
                </div>
            </div>

        </draggable>

        <hr class="mt-5"/>
        <button @click="createRowAndColumns(kanban.rows.length, [], null, null)"
                class="text-gray-500 hover:text-gray-600 font-semibold font-sans tracking-wide bg-gray-200 rounded-lg rounded p-4 m-10 hover:bg-blue-200 mouse transition ease-in duration-200 focus:outline-none">
            <p class="font-bold inline">Create new row</p>
            <i class="pl-2 fas fa-plus"></i>
        </button>

        <add-row-and-columns-modal :kanbanData="kanban"></add-row-and-columns-modal>
        <add-member-modal :kanbanData="kanban"></add-member-modal>
        <add-task-by-column-modal :kanbanData="kanban"></add-task-by-column-modal>

    </div>
</template>

<script>

import draggable from "vuedraggable";

import KanbanBar from "./kanbanComponents/KanbanBar.vue";
import {ajaxCalls} from "../../mixins/ajaxCallsMixin";
import AddMemberModal from "./kanbanComponents/AddMemberModal";
import AddRowAndColumnsModal from "./kanbanComponents/AddRowAndColumnsModal";
import AddTaskByColumnModal from "./kanbanComponents/AddTaskByColumnModal";
import TaskCard from "./kanbanComponents/TaskCard";

export default {

    inject: ["eventHub"],
    mixins: [ajaxCalls],


    props: {'id': Number},

    components: {
        AddTaskByColumnModal,
        KanbanBar,
        AddMemberModal,
        AddRowAndColumnsModal,
        TaskCard,
        draggable,
    },

    data() {
        return {
            kanban: null,
            loadingMembers: {memberId: null, isLoading: false},
            loadingRow: {rowId: null, isLoading: false},
            loadingCards: {columnId: null, isLoading: false},
            isDraggableDisabled: false
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
        this.eventHub.$on("save-task", (taskData) => {
            this.saveTask(taskData);
        });
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
        this.eventHub.$off('save-task');
        this.eventHub.$off('save-members');
        this.eventHub.$off('remove-member');
        this.eventHub.$off('save-row-and-columns');
    },

    methods: {

        getKanban(kanbanID) {
            this.asyncGetKanbanData(kanbanID).then((data) => {
                this.kanban = data.data;
                console.log(this.kanban);
            }).catch(res => {
                console.log(res)
            });
        },

        createTask(rowIndex, columnIndex) {
            let rowName = this.kanban.rows[rowIndex].name;
            let rowId = this.kanban.rows[rowIndex].id;

            let columnName = this.kanban.rows[rowIndex].columns[columnIndex].name;
            let columnId = this.kanban.rows[rowIndex].columns[columnIndex].id;

            let boardId = this.kanban.id;

            this.eventHub.$emit("create-task", {
                rowIndex,
                rowName,
                columnId,
                rowId,
                columnIndex,
                columnName,
                boardId
            });
        },

        saveTask(taskData) {

            this.isDraggableDisabled = true;

            this.asyncCreateTask(taskData).then((data) => {

                this.asyncGetTaskCardsByColumn(taskData.selectedColumnId).then((data) => {
                    console.log(taskData)
                    this.kanban.rows[taskData.selectedRowIndex].columns[taskData.selectedColumnIndex].tasks = data.data;
                    this.isDraggableDisabled = false

                }).catch(res => {
                    console.log(res)
                });
                this.triggerSuccessToast('task created');
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

        // Whenever a user drags a card
        getTaskChangeData(event, columnIndex, rowIndex) {
            let eventName = Object.keys(event)[0];
            let taskCardData = this.kanban.rows[rowIndex].columns[columnIndex].task_cards;
            let columnId = this.kanban.rows[rowIndex].columns[columnIndex].id;
            let rowId = this.kanban.rows[rowIndex].id
            this.isDraggableDisabled = true;


            switch (eventName) {
                case "moved":
                    this.asyncUpdateTaskCardsIndexes(taskCardData).then(() => {
                        this.isDraggableDisabled = false
                        this.triggerSuccessToast('task moved')
                    });
                    break;
                case "added":
                    this.asyncUpdateTaskCardRowAndColumnId(columnId, rowId, event.added.element.id).then(() => {
                            this.asyncUpdateTaskCardsIndexes(taskCardData).then(() => {
                                this.asyncGetTaskCardsByColumn(columnId).then((data) => {
                                    this.kanban.rows[rowIndex].columns[columnIndex].task_cards = data.data;
                                }).catch(res => {
                                    console.log(res)
                                });
                                this.isDraggableDisabled = false
                                this.triggerSuccessToast('task moved');
                            });
                        }
                    );
                    break;
                case "removed":
                    this.asyncUpdateTaskCardsIndexes(taskCardData).then(() => {
                        this.isDraggableDisabled = false
                    });
                    break;
                default:
                    alert('event "' + eventName + '" not handled: ');
            }
        },

        // Whenever a user drags a column
        getColumnChangeData(event, rowIndex) {

            if (event.oldIndex !== event.newIndex) {
                console.log('column');
                let columns = this.kanban.rows[rowIndex].columns;
                this.isDraggableDisabled = true;
                this.asyncUpdateColumnIndexes(columns).then(() => {

                    this.isDraggableDisabled = false
                    this.getKanban(this.kanban.id);
                    this.triggerSuccessToast('Column position updated')
                });
            }
        },

        // Whenever a user drags a row
        getRowChangeData(event) {
            if (event.oldIndex !== event.newIndex) {
                this.isDraggableDisabled = true
                this.asyncUpdateRowIndexes(this.kanban.rows).then(() => {
                    this.isDraggableDisabled = false
                    this.getKanban(this.kanban.id);
                    this.triggerSuccessToast('Row position updated')
                });
            }
        },
    }

};
</script>
