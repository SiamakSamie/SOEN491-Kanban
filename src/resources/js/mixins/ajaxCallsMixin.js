import axios from 'axios';

export const ajaxCalls = {

    methods: {

        // Kanban App Data

        asyncGetDashboardData() {
            return axios.get('get-dashboard-data');
        },

        asyncGetKanbanData(id) {
            return axios.get('get-board-data/' + id);
        },

        // Boards

        asyncGetBoards() {
            return axios.get('get-boards');
        },

        asyncCreateBoard(kanbanData) {
            return axios.post('create-board', kanbanData).then(() =>{
                this.triggerSuccessToast("Board Created!");
            }).catch((error) => {
                this.triggerErrorToast(error.response.data.message);
            });
        },

        asyncDeleteBoard(boardId) {
            return axios.post('delete-board/' + boardId).then(() =>{
                this.triggerSuccessToast("Board Deleted!");
            }).catch((error) => {
                this.triggerErrorToast(error.response.data.message);
            });
        },

        // Employees

        asyncCreateKanbanEmployee(employeeData) {
            return axios.post('create-kanban-employees', employeeData).then(() =>{
                this.triggerSuccessToast("Employee Added!");
            }).catch((error) => {
                this.triggerErrorToast(error.response.data.message);
            });
        },

        asyncDeleteKanbanEmployee(employeeId) {
            return axios.post('delete-kanban-employee/' + employeeId).then(() =>{
                this.triggerSuccessToast("Employee Removed");
            }).catch((error) => {
                this.triggerErrorToast(error.response.data.message);
            });
        },

        asyncGetKanbanEmployees() {
            return axios.get('get-kanban-employees');
        },

        asyncGetAllUsers() {
            return axios.get('get-all-users');
        },

        asyncGetSomeUsers(searchTerm) {
            if (searchTerm === '') {
                return axios.get('get-all-users');
            }
            return axios.get('get-some-users/' + searchTerm);
        },


        // Triggers

        triggerSuccessToast(message) {
            this.$toast.success(message, {
                position: 'bottom-right',
                timeout: 4000,
                closeOnClick: true,
                pauseOnFocusLoss: true,
                pauseOnHover: true,
                draggable: true,
                draggablePercent: 0.6,
                showCloseButtonOnHover: false,
                hideProgressBar: false,
                closeButton: 'button',
                icon: true,
                rtl: false
            });
        },

        //Trigger Toasts

        triggerErrorToast(message) {
            this.$toast.error(message, {
                position: 'bottom-right',
                timeout: 4000,
                closeOnClick: true,
                pauseOnFocusLoss: true,
                pauseOnHover: true,
                draggable: true,
                draggablePercent: 0.6,
                showCloseButtonOnHover: false,
                hideProgressBar: false,
                closeButton: 'button',
                icon: true,
                rtl: false
            });
        },

        triggerInfoToast(message) {
            this.$toast.info(message, {
                position: 'bottom-right',
                timeout: 4000,
                closeOnClick: true,
                pauseOnFocusLoss: true,
                pauseOnHover: true,
                draggable: true,
                draggablePercent: 0.6,
                showCloseButtonOnHover: false,
                hideProgressBar: false,
                closeButton: 'button',
                icon: true,
                rtl: false
            });
        }

    }
};
