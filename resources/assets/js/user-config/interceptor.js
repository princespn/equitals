import Vue from 'vue';
import axios from 'axios';
import { domain } from '../user-config/config';

export default function setup() {
    axios.interceptors.request.use(function(config) {
        const token = localStorage.getItem('user-token');
        if (token != null) {
            config.headers.Authorization = "Bearer " + token;
        }
        return config;
    }, function(err) {
        return Promise.reject(err);
    });
    axios.interceptors.response.use(function(config) {
        if (config.data.code == 403) {
            localStorage.removeItem('user_token');
            localStorage.removeItem('user-token');
            setTimeout(function() {
                window.location.href = domain;
            }, 2000);
            setTimeout(function() {
                location.reload();
            }, 3000);

        } else {
            return config;
        }

    }, function(err) {
        if (err.response.status == 401 || err.response.status == 403) {
            localStorage.removeItem('user-token');
            location.reload();
            //return;
        }
    });
}