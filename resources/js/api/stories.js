export default {
    all() {
        return axios.get('/api/stories');
    },
    find(id) {
        return axios.get(`/api/stories/${id}`);
    },
    saveItems(data) {
        return axios.post('/api/stories/save',
            data,
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
        ).then(res => {
            return res;
        })
            .catch(err => {
                return err;
            });
    },
    removeItem(id) {
        return axios.delete(`/api/stories/removeItem/${id}`)
            .then(res => {
                return res;
            })
            .catch(err => {
                return err;
            });
    },
};
