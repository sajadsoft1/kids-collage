import axios from 'axios';
import 'sortablejs';
window.axios = axios;
// window.Sortable = Sortable
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Import SortableJS
import Sortable from 'sortablejs';
window.Sortable = Sortable;
