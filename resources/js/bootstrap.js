import axios from 'axios';
import 'sortablejs';
import flatpickr from "flatpickr";
import jalaliPlugin from "jalali-plugin-dayjs";
import dayjs from "dayjs";
import * as jalaali from "jalaali-js";

window.flatpickr = flatpickr;
window.jalaliPlugin = jalaliPlugin;
window.dayjs = dayjs;
window.jalaali = jalaali;
window.axios = axios;
// window.Sortable = Sortable
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Import SortableJS
import Sortable from 'sortablejs';
window.Sortable = Sortable;
