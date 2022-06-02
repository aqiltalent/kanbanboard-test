import Vue from 'vue';
import VModal from 'vue-js-modal'

require('./bootstrap');

Vue.config.productionTip = false;

Vue.use(VModal);

Vue.component('kanban-board', require('./pages/KanbanBoard.vue').default);

new Vue({
  el: '#app'
});