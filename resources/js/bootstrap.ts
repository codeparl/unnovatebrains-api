import axios from 'axios';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
//add base url 
window.axios.defaults.baseURL = 'http://unnovate.test'
