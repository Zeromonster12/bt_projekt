import axios from 'axios';

const api = axios.create({
    baseURL: 'http://localhost:8000/api',
    withCredentials: true, // Enables sending cookies with requests
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    }
});

// Add a request interceptor to add the XSRF-TOKEN from cookies to the X-XSRF-TOKEN header
api.interceptors.request.use(config => {
    // Get the XSRF token from the cookie
    const token = getCookie('XSRF-TOKEN');
    if (token) {
        config.headers['X-XSRF-TOKEN'] = decodeURIComponent(token);
    }
    return config;
});

// Helper function to get cookies
function getCookie(name) {
    const match = document.cookie.match(new RegExp('(^|;\\s*)(' + name + ')=([^;]*)'));
    return match ? match[3] : null;
}

export default api;