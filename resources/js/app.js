require('./bootstrap');

window.Vue = require('vue')
import router from './router'
import store from './store'
import ViewUI from 'view-design';
import common  from "./common";
import 'view-design/dist/styles/iview.css';
import Editor from 'vue-editor-js';
import jsonToHtml from './jsonToHtml';

Vue.use(Editor)

Vue.use(ViewUI);
Vue.mixin(common)
Vue.mixin(jsonToHtml)



Vue.component('mainapp', require('./components/mainapp.vue').default)
const app = new Vue({
   el:'#app' ,
   router,
   store

})
