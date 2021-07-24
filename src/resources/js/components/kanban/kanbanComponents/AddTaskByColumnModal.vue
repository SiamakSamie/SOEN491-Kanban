<template>
    <div>
        <transition
            enter-active-class="transition duration-500 ease-out transform"
            enter-class=" opacity-0 bg-blue-200"
            leave-active-class="transition duration-300 ease-in transform"
            leave-to-class="opacity-0 bg-blue-200">
            <div v-if="modalOpen" class="overflow-auto fixed inset-0 bg-gray-700 bg-opacity-50 z-30"></div>
        </transition>

        <transition
            enter-active-class="transition duration-300 ease-out transform "
            enter-class="scale-95 opacity-0 -translate-y-10"
            enter-to-class="scale-100 opacity-100"
            leave-active-class="transition duration-150 ease-in transform"
            leave-class="scale-100 opacity-100"
            leave-to-class="scale-95 opacity-0">
            <!-- Modal container -->

            <div v-if="modalOpen"
                class="fixed inset-0 z-40 flex items-start justify-center"
            >
                <!-- Close when clicked outside -->
                <div class="overflow-auto fixed h-full w-full"
                    @click="modalOpen = false"
                ></div>
                <div style="width: 700px; min-height: 300px; max-height: 80%"
                    class="flex flex-col overflow-auto z-50 w-100 bg-white rounded-md shadow-2xl m-10">
                    <div class="flex justify-between p-5 bg-indigo-800 border-b">
                        <div class="space-y-1">
                            <h1 class="text-2xl text-white pb-2">Create new task</h1>
                            <p class="text-sm font-medium leading-5 text-gray-500">
                                <em>
                                    Adding a new task to row
                                    <b>'{{ taskData.selectedRowName }}'</b>
                                    in column
                                    <b>'{{ taskData.selectedColumnName }}'</b>
                                </em>
                            </p>
                        </div>
                        <div>
                            <button type="button"
                                class="focus:outline-none flex flex-col items-center text-gray-400 hover:text-gray-500 transition duration-150 ease-in-out pl-8"
                                @click="modalOpen = false">
                                <i class="fas fa-times"></i>
                                <span class="text-xs font-semibold text-center leading-3 uppercase">Esc</span>
                            </button>
                        </div>
                    </div>
                    <form class="space-y-6 overflow-auto px-8 py-6">
                        <div class="space-y-6">
                            <div class="space-y-4">
                                <label class="block space-y-2">
                                    <span class="block text-xs font-bold leading-4 tracking-wide uppercase text-gray-600">Task Name</span>
                                    <input
                                        type="text"
                                        v-model="taskData.taskName"
                                        placeholder="Task"
                                        class="px-3 py-3 placeholder-gray-400 text-gray-700 rounded border border-gray-400 w-full pr-10 outline-none text-md leading-4"/>
                                </label>
                            </div>


                            <div class="flex-1">
                                <span class="block text-xs font-bold leading-4 tracking-wide uppercase text-gray-600">Assign To</span>
                                <vSelect
                                    v-model="taskData.assignedTo"
                                    multiple
                                    :options="kanbanData.members"
                                    :getOptionLabel="opt => opt.employee.user.name"
                                    style="margin-top: 7px"
                                    class="text-gray-700">
                                    <template slot="option" slot-scope="option">
                                        <avatar
                                            class="mr-3 m-1 float-left"
                                            :name="option.employee.user.name"
                                            :size="4">
                                        </avatar>
                                        <p class="inline">{{ option.employee.user.name }}"</p>
                                    </template>
                                    <template #no-options="{ search, searching, loading }">
                                        No result .
                                    </template>
                                </vSelect>
                            </div>
                            <div class="flex">
                                <div class="flex-1 pr-3">
                                    <span class="block text-xs font-bold leading-4 tracking-wide uppercase text-gray-600">Priority</span>
                                    <vSelect
                                        v-model="taskData.priority"
                                        :options="priorityOptions"
                                        label="name"
                                        style="margin-top: 7px"
                                        class="text-gray-700">
                                        <template slot="option" slot-scope="option">
                                            <span class="fa mr-4" :class="[option.icon, `text-${option.color}-400 `]"></span>
                                            {{ option.name }}
                                        </template>
                                        <template #no-options="{ search, searching, loading }">
                                            No result .
                                        </template>
                                    </vSelect>
                                </div>
                                <div class="flex-1"><span class="block text-xs font-bold leading-4 tracking-wide uppercase text-gray-600">Badge</span>
                                    <vSelect
                                        v-model="taskData.badge"
                                        :options="computedBadges"
                                        label="name"
                                        placeholder="Choose or Create"
                                        style="margin-top: 7px"
                                        taggable
                                        :create-option="option => ({name: option.toLowerCase()})">
                                        class="text-gray-700">
                                        <template slot="option" slot-scope="option">
                                            <span :style="`color: hsl(${option.hue}, 50%, 45%);`" class="fa fa-circle mr-4"></span>
                                            {{ option.name }}
                                        </template>
                                        <template #no-options="{ search, searching, loading }">
                                            No result .
                                        </template>
                                    </vSelect>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="block space-y-2">
                                <span class="block text-xs font-bold leading-4 tracking-wide uppercase text-gray-600">Task Description</span>
                                <textarea
                                    v-model="taskData.taskDescription"
                                    placeholder="Details"
                                    class="h-20 px-3 py-3 placeholder-gray-400 text-gray-700 rounded border border-gray-400 w-full pr-10 outline-none text-md leading-4"/>
                            </label>
                        </div>

                        <div class="w-full grid sm:grid-cols-2 gap-3 sm:gap-3">
                            <button
                                type="button"
                                class="px-4 py-3 border border-gray-200 rounded text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-600 transition duration-300 ease-in-out"
                                @click="modalOpen = false">
                                Cancel
                            </button>
                            <button
                                type="button"
                                class="px-4 py-3 border border-transparent rounded text-white bg-indigo-600 hover:bg-indigo-500 transition duration-300 ease-in-out"
                                @click="saveTask($event)">
                                Save Task
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </transition>
    </div>
</template>
<script>
import vSelect from "vue-select";
import Avatar from "../../global/Avatar.vue";
import {ajaxCalls} from "../../../mixins/ajaxCallsMixin";
import {helperFunctions} from "../../../mixins/helperFunctionsMixin";


export default {
    inject: ["eventHub"],
    components: {
        vSelect,
        Avatar,
    },

    mixins: [ajaxCalls, helperFunctions],

    props: {
        kanbanData: Object,
    },
    data() {
        return {
            modalOpen: false,
            isSavingTask: false,
            badges: [],
            priorityOptions: [
                {
                    name: "High",
                    icon: "fa-arrow-up",
                    color: "red",
                },
                {
                    name: "Medium",
                    icon: "fa-arrow-up",
                    color: "yellow",
                },
                {
                    name: "Low",
                    icon: "fa-arrow-down",
                    color: "green",
                },
                {
                    name: "None",
                    icon: "fa-circle",
                    color: "gray",
                },
            ],
            taskData: {
                taskName: null,
                taskDescription: null,
                priority: null,
                assignedTo: null,
                selectedRowIndex: null,
                selectedColumnIndex: null,
                selectedColumnId: null,
                selectedRowName: null,
                selectedRowId: null,
                selectedColumnName: null,
                boardId: null
            },
        };
    },
    mounted() {

    },
    created() {
        this.eventHub.$on("create-task", (taskData) => {
            this.openModal(taskData);
            console.log(taskData);
        });
        this.getBadges();

    },

    computed: {
        computedBadges() {
            return this.badges.map(badge => {
                let computedBadges = {};
                computedBadges.name = badge.name;
                computedBadges.hue = this.generateHslColorWithText(badge.name);
                return computedBadges;
            })
        },
    },

    methods: {
        saveTask(event) {
            event.target.disabled = true;
            this.eventHub.$emit("save-task", this.taskData);
            this.modalOpen = false;

            this.taskData = {
                taskName: null,
                taskDescription: null,
                priority: null,
            };
        },

        getBadges() {
            this.asyncGetBadges().then((data) => {
                this.badges = data.data;
            }).catch(res => {
                console.log(res)
            });
        },

        openModal(taskData) {
            this.taskData.selectedRowIndex = taskData.rowIndex;
            this.taskData.selectedRowName = taskData.rowName;
            this.taskData.selectedRowId = taskData.rowId;
            this.taskData.selectedColumnId = taskData.columnId;
            this.taskData.selectedColumnIndex = taskData.columnIndex;
            this.taskData.selectedColumnName = taskData.columnName;
            this.taskData.boardId = taskData.boardId;

            this.modalOpen = true;
        },
    },
};
</script>
