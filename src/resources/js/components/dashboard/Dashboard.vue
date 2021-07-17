<template>
    <div>
        This is the dashboard page

        <router-link :to="{ path: '/kanban/board', query: { id: 1 } }">
            <span class="text-gray-700 bg-gray-50 p-2 m-2">Test Board 1</span>
        </router-link>

        <router-link :to="{ path: '/kanban/board', query: { id: 2 } }">
            <span class="text-gray-700 bg-gray-50 p-2 m-2">Test Board 2</span>
        </router-link>
    </div>
</template>

<script>
export default {
};
</script>

