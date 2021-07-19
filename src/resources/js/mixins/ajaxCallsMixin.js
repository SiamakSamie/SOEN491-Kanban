import axios from 'axios';

export const ajaxCalls = {

    methods: {
        asyncGetBoards() {
            return axios.get('get-boards');
        },
    }
};
