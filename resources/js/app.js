


window.$ = window.jQuery = require('jquery');
import '@popperjs/core';
import 'bootstrap';
import 'datatables.net-bs5';
require('./bootstrap');
require('./admin');


// Require Vue
import { createApp } from 'vue';
import App from './components/ExampleComponent.vue';
createApp(App).mount("#app");

// Register Vue Components
app.component('example-component', ExampleComponent);
app.mount('#app');




