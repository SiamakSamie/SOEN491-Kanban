<template>
    <div class="bg-white hover:shadow-md shadow rounded px-3 pt-3 pb-5 border-l-4"
         v-bind:class="`border-${priorityColor}-400`">
        <div class="flex justify-between">
            <p class="text-gray-700 font-semibold font-sans tracking-wide text-sm mr-3">{{ task.name }}</p>
            <div class="flex justify-end">
                <template v-for="(employee, employeeIndex) in task.assigned_to">
                    <template v-if="employeeIndex < 3">
                        <avatar :borderColor="'white'"
                                :borderSize="0"
                                :class="{ '-ml-2': employeeIndex > 0 }"
                                :key="employeeIndex"
                                :name="employee.employee.user.name"
                                :size="6"
                                :tooltip="true"></avatar>
                    </template>
                </template>

                <div v-if="task.assignedTo" class="flex">
          <span
              v-if="task.assignedTo.length > 3"
              class="z-10 flex items-center justify-center font-semibold text-gray-800 text-xs w-6 h-6 rounded-full bg-gray-300 border-white border -ml-2 pr-1"
          >+{{ task.assignedTo.length - 3 }}
          </span>
                </div>
            </div>
        </div>

        <p class="text-gray-600 font-semibold font-sans tracking-wide text-xs mr-3 my-3">{{ task.description }}</p>


        <div class="flex mt-2 justify-between items-center">
            <div class="flex flex-wrap items-center">
                <avatar :name="task.reporter.name" :size="6" :tooltip="true"
                        class="border border-white"></avatar>
                <span class="text-xs text-gray-600 px-1"> • {{ task.created_at | moment("DD MMM, YYYY") }}</span>
            </div>
            <badge :name="task.badge.name" v-if="task.badge !== []"></badge>
        </div>
    </div>
</template>
<script>
import Badge from "../../global/Badge";
import Avatar from "../../global/Avatar";

export default {
    components: {
        Badge,
        Avatar,
    },
    props: {
        task: {
            badge: Object,
            priority: String,
            creator: Object,
            assignedTo: Object,
            required: true,
            default: () => ({}),
        },
    },

    computed: {
        priorityColor() {
            switch (this.task.priority) {
                case 'Low':
                    return 'green';
                case 'Medium':
                    return 'yellow';

                case 'High':
                    return 'red';

                case 'None':
                    return 'gray';

                default:
                    return 'gray';
            }
        },
    },
};
</script>
