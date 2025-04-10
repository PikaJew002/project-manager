import { http } from './defaults.js';

const apiUrl = `${import.meta.env.VITE_APP_URL}/api/user`;

const loginUrl = `${import.meta.env.VITE_APP_URL}/api/login`;

const csrfTokenUrl = `${import.meta.env.VITE_APP_URL}/sanctum/csrf-cookie`;

async function getUser() {
    return await http.get(apiUrl);
}

async function postLogin(credentials) {
    return http.get(csrfTokenUrl).then(() => {
        return http.post(loginUrl, credentials);
    }).catch((err) => {
        console.log(err);
        throw err;
    });
}

export { getUser, postLogin };
