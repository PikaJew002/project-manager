import { http } from './defaults.js';

const apiUrl = `${import.meta.env.VITE_APP_URL}/api/task`;

async function getTasks() {
    return await http.get(apiUrl);
}

async function postTask(task) {
    return await http.post(apiUrl, task);
}

export { getTasks, postTask, apiUrl };
