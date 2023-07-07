import "./bootstrap";
import "../css/app.css";
import "flowbite";
import xmeCreateInertiaApp from '@/micro-apps/create-app';

import { useAppStore } from '@/store/index'
import { DeepObject } from "@/DeepObjects.ts";

const axios = window.axios;

axios.interceptors.response.use(
    function (response) {
        const useAppStoreObj = useAppStore();
         
        if ("user-auth" in response.headers) {
            let userAuth = JSON.parse(response.headers["user-auth"]);
            useAppStoreObj.user_authorization = userAuth
        }
        if ("user" in response.headers) {
            let user = JSON.parse(response.headers["user"]);
            useAppStoreObj.user = user;
        }
        return response;
    },
    function (error) {
        if (error.hasOwnProperty("response")) {
            const useAppStoreObj = useAppStore();
        
            let errorMsg = null;

            if ("user-auth" in error.response.headers) {
                let userAuth = JSON.parse(error.response.headers["user-auth"]);
                useAppStoreObj.user_authorization = userAuth
            }
            if ("user" in error.response.headers) {
                let user = JSON.parse(error.response.headers["user"]);
                useAppStoreObj.user = user;
            }
            if (error.response.status == 422) {
                let errors = error.response.data.errors;
                errorMsg = errors[Object.keys(errors)[0]][0];
            } else {
                errorMsg = error.response.data.message;
            }
            let notification = {
                heading: "Failed !",
                subHeading: errorMsg,
                type: "error",
            };
            useAppStoreObj.setNotification(notification);
        }

        return Promise.reject(error);
    }
);

const el = document.getElementById("app");
const createInstance = () => {
    xmeCreateInertiaApp()
};
// on initial fetch the php and wp versions.
(async () => {
    try {
        createInstance();
    } catch (e) {
        console.log(e);
        // Deal with the fact the chain failed
    }
})();
