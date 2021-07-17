<template>
    <div>
        This is the kanban page showing board id:{{ id }}

        <router-link :to="{ path: '/kanban' }">
            <span class="text-gray-700 bg-gray-50 p-2 m-2">Go to dashboard</span>
        </router-link>
    </div>
</template>

<script>
export default {
    props: {'id': Number},
};
</script>
