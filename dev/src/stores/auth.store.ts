import { defineStore } from "pinia";
import { type UserData, type Perm } from "@/types";


export const useAuthStore = defineStore('auth', {
    state: () => ({
        isUserLogged: false as boolean,
        authError: false as boolean,
        userData: null as (UserData | null),
        userPerm: null as (Perm | null),
    })
});