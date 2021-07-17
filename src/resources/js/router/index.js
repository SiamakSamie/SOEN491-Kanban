import Vue from "vue";
import Router from "vue-router";
import Kanban from "../components/kanban/Kanban";
import Dashboard from "../components/dashboard/Dashboard";

Vue.use(Router);

export default new Router({
    mode: "history",

    routes: [
        {
            path: "/kanban/",
            redirect: "kanban/dashboard"
        },
        {
            path: "/kanban/dashboard",
            component: Dashboard
        },
        {
            path: "/kanban/board",
            component: Kanban,
            props: (route) => ({id: Number(route.query.id)})
        },

        // All invalid pages get re-routed to dashboard page
        {
            path: '*',
            redirect: "kanban/dashboard"
        }
    ]
});
