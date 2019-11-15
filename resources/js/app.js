/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import App from './views/App'
import Stories from './views/Stories'
import AddEditStory from './views/AddEditStory'

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'stories',
            component: Stories
        },
        {
            path: '/stories',
            name: 'stories',
            component: Stories,
        },
        {
            path: '/stories/add',
            name: 'stories.add',
            component: AddEditStory,
        },
        {
            path: '/stories/edit/:id',
            name: 'stories.edit',
            component: AddEditStory
        },
    ],
});


new Vue({
    router,
    render: h => h(App),
}).$mount('#app')
