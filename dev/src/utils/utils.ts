import type { Ref } from 'vue';
import { isTokenExpired, removeToken } from './rest-api';
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import router from '@/router';
import { useAuthStore } from '@/stores/auth.store';
import { type UserData, type Perm } from '@/types';


export function checkPerms() {
	const authStore = useAuthStore();
	//const userPerm = getCurrentUserPerms();
	//const UserData = 
	
	
	//return userPerm;
}

export function sessionManager() {
	//let intervalId: number | null = null;
	const isSessionExpired = ref(false);
	const intervalId: Ref<number | null> = localStorage.getItem('intervalId') ? ref(parseInt(localStorage.getItem('intervalId') as string)) : ref(null);

	const checkTimeOut = async () => {
		//console.log('Checking session');
		if (intervalId.value === null) {return;}
		if ((localStorage.getItem('isUserLogged') === 'true')) {
			const expired = await isTokenExpired();
			//console.log(expired);
			if (expired) {
				clearIntervalId();
				isSessionExpired.value = true;
				window.scrollTo(0, 0);
				localStorage.setItem('isSessionExpired', 'true');
				localStorage.setItem('isUserLogged', 'false');
				document.body.style.overflow = 'hidden';
				logout();
				router.go(0);
				// Optionally, clear the interval if you only need to check once
			}
		}
	}

	const startSessionCheck = async () => {
		if (intervalId.value == null) {return;}
		if (intervalId.value) {
			clearIntervalId();
		}

		const temp_intervalId = setInterval(await checkTimeOut, 1000);
		localStorage.setItem('intervalId', temp_intervalId.toString());

		//console.log('Session check started');
		
	}

	const stopSessionCheck = () => {
		if (intervalId.value == null) {return;}
		if (intervalId.value) {
			clearIntervalId();
			//console.log('Session check concluded');
		}
	}

	const login = async () => {
		localStorage.setItem("isUserLogged", "true");
		//startSessionCheck();
	}

	const logout = async () => {
		//stopSessionCheck();
		await removeToken();
		localStorage.setItem("isUserLogged", "false");
	}

	const clearIntervalId = () => {
		clearInterval(intervalId.value as number);
		localStorage.setItem('intervalId', 'null');
	}

	return {
		startSessionCheck,
		stopSessionCheck,
		logout,
		login
	};
}

export function isUserStored(): boolean {
	return sessionStorage.getItem('userData') === 'true';
}

export function isPermStored(): boolean {
	return sessionStorage.getItem('userPerm') === 'true';
}

export function isLoginStatus(): boolean {
	return sessionStorage.getItem('isUserLogged') === 'true';
}

export function getLoginStatus(): boolean {
    return localStorage.getItem('isUserLogged') ? JSON.parse(localStorage.getItem('isUserLogged') as string) : false; 
}

