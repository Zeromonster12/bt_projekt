import axios from 'axios';

const csrf = axios.create({
    baseURL: 'http://localhost:8000',
    withCredentials: true,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    }
});

export default csrf;