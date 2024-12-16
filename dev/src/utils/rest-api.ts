const router = () => import('@/router');
import { type UserData, type Article, type Perm, type Review } from '@/types';
import axios from 'axios';

const BASE_URL = 'http://localhost:8080/kivweb/backend/api/index.php';

export async function login(user_email: string, user_password: string): Promise<UserData | null> {
    try {
        const res = await axios.post(BASE_URL + '/login', {
            user_email: user_email,
            user_password: user_password
        });
        
        if (res.status === 200) {
            //console.log("Login successful");
            localStorage.setItem('isUserLogged', 'true');
            console.log(res.data);
            return res.data;
        } else {
            //console.log("Invalid credentials");
            return null;
        }
    } catch (error) {
        console.error("An error occurred during login:", error);
        return null;
    }
}

export async function submitNewArticle(article_header: string, article_content: string, article_image: File) {
    try {
        const formData = new FormData();
        formData.append('article_header', article_header); // Append the article header
        formData.append('article_content', article_content); // Append the article content
        formData.append('article_image', article_image); // Append the image file

        const res = await axios.post(BASE_URL + '/article', formData, {
            headers: {
                'Content-Type': 'multipart/form-data' // Set the content type to multipart/form-data
            },
            withCredentials: true // Include credentials if needed
        });

        if (res.status === 200) {
            return true; // Indicate success
        }
    } catch (error) {
        console.error("An error occurred while submitting new article:", error);
        return false; // Indicate failure
    }
    return false; // Default return value
}


export async function getArticle(id: number): Promise<Article | null> {
    try {
        const res = await axios.get(BASE_URL + '/article/' + id, {
            withCredentials: true
        });

        if (res.status === 200) {
            //authStore.isUserLogged = true;
            return res.data;
        }      
    } catch (error) {
        console.log("An error occurred while loading article:", error);
        return null;
    }
    return null;
}

export async function getArticles(page: number): Promise<Article[] | null> {
    try {
        const res = await axios.get(BASE_URL + '/article', {
            params: {
                // Add your query parameters heresteam
                // Example: page: 1, limit: 10
                page: page,
            }
        });

        if (res.status === 200) {
            //authStore.isUserLogged = true;
            return res.data;
        }  

    } catch (error) {
        console.log("An error occurred while loading articles:", error);
        return null;
    }
    return null;
}

export async function getAllArticles(): Promise<Article[] | null> {
    try {
        const res = await axios.get(BASE_URL + '/article', {
        });

        if (res.status === 200) {
            //authStore.isUserLogged = true;
            return res.data;
        }  

    } catch (error) {
        console.log("An error occurred while loading articles:", error);
        return null;
    }
    return null;
}

export async function getAcceptedArticle(id: number, accepted: boolean): Promise<Article | null> {
    try {
        const res = await axios.get(BASE_URL + '/article', {
            params: {
                // Add your query parameters heresteam
                // Example: page: 1, limit: 10
                id: id,
                accepted: accepted
            },
            withCredentials: true
        });

        if (res.status === 200) {
            //authStore.isUserLogged = true;
            return res.data;
        }      
    } catch (error) {
        console.log("An error occurred while loading article:", error);
        return null;
    }
    return null;
}

export async function getAcceptedArticles(accepted: boolean, page?: number): Promise<Article[] | null> {
    try {
        const res = await axios.get(BASE_URL + '/article', {
            params: {
                // Add your query parameters heresteam
                // Example: page: 1, limit: 10
                ...(page && {
                    page: page,
                }),
                accepted: accepted
            },
            withCredentials: true
        });

        if (res.status === 200) {
            //authStore.isUserLogged = true;
            return res.data;
        }      
    } catch (error) {
        console.log("An error occurred while loading article:", error);
        return null;
    }
    return null;
}

export async function getReviewedArticle(id: number, accepted: boolean): Promise<Article | null> {
    try {
        const res = await axios.get(BASE_URL + '/article', {
            params: {
                // Add your query parameters heresteam
                // Example: page: 1, limit: 10
                page: id,
                accepted: accepted
            },
            withCredentials: true
        });

        if (res.status === 200) {
            //authStore.isUserLogged = true;
            return res.data;
        }      
    } catch (error) {
        console.log("An error occurred while loading article:", error);
        return null;
    }
    return null;
}

export async function getArticlesModeration(): Promise<Article[] | null> {
    try {
        const res = await axios.get(BASE_URL + '/article', {
            params: {
                // Add your query parameters heresteam
                // Example: page: 1, limit: 10
                moderation: true
            },
            withCredentials: true
        });

        if (res.status === 200) {
            //authStore.isUserLogged = true;
            return res.data;
        }      
    } catch (error) {
        console.log("An error occurred while loading article:", error);
        return null;
    }
    return null;
}

export async function getReviewedArticles(page?: number): Promise<Article[] | null> {
    try {
        const res = await axios.get(BASE_URL + '/article', {
            params: {
                // Add your query parameters heresteam
                // Example: page: 1, limit: 10
                ...(page && {
                    page: page,
                }),
                accepted: false
            },
            withCredentials: true
        });

        if (res.status === 200) {
            //authStore.isUserLogged = true;
            return res.data;
        }      
    } catch (error) {
        console.log("An error occurred while loading article:", error);
        return null;
    }
    return null;
}

export async function getReviews(page?: number): Promise<Review[] | null> {
    try {
        const res = await axios.get(BASE_URL + '/review', {
            params: {
                // Add your query parameters heresteam
                // Example: page: 1, limit: 10
                ...(page && {
                    page: page,
                }),
            }
        });

        if (res.status === 200) {
            //authStore.isUserLogged = true;
            return res.data;
        }      
    } catch (error) {
        console.log("An error occurred while loading articles:", error);
        return null;
    }
    return null;
}

export async function getAllReviewers(): Promise<Review[] | null> {
    try {
        const res = await axios.get(BASE_URL + '/users', {

        });

        if (res.status === 200) {
            //authStore.isUserLogged = true;
            return res.data;
        }      
    } catch (error) {
        console.log("An error occurred while loading articles:", error);
        return null;
    }
    return null;
}

export async function getReviewers(): Promise<UserData[] | null> {
    try {
        const res = await axios.get(BASE_URL + '/users', {
            params: {
                // Add your query parameters heresteam
                // Example: page: 1, limit: 10
            },
            withCredentials: true
        });

        if (res.status === 200) {
            //authStore.isUserLogged = true;
            return res.data;
        }      
    } catch (error) {
        console.log("An error occurred while loading article:", error);
        return null;
    }
    return null;
}

export async function auth(sessionToken: string): Promise<boolean> {
    try {
        const res = await axios.post(BASE_URL + '/auth', {}, {
            headers: {
                'Authorization': sessionToken
            }
        });

        if (res.status === 200) {
            return true;
        }
    } catch (error) {
        console.error("An error occurred while authenticating user:", error);
        return false;
    }
    return false;
}

// Function to get user data
export async function refreshToken(): Promise<boolean> {
    try {
        const res = await axios.post(BASE_URL + '/token/refresh', {}, {
            withCredentials: true,
        });

        if (res.status === 200) {
            localStorage.setItem('isUserLogged', 'true');
            return true;
        }
    } catch (error) {
        localStorage.setItem('isUserLogged', 'false');
        //console.error("An error occurred while authenticating user:", error);
        return false;
    }
    return false;
}

// Function to get user data
export async function removeToken(): Promise<boolean> {
    try {
        const res = await axios.post(BASE_URL + '/token/remove', {}, {
            withCredentials: true,
        });

        if (res.status === 200) {
            return true;
        }
    } catch (error) {
        console.error("An error occurred while authenticating user:", error);
        return false;
    }
    return false;
}

// Function to get user data
export async function isTokenExpired(): Promise<boolean> {
    try {
        const res = await axios.post(BASE_URL + '/token/checkExpiration', {}, {
            withCredentials: true,
        });

        if (res.status === 200) {
            return false;
        }
    } catch (error) {
        console.error("An error occurred while authenticating user:", error);
        return true;
    }
    return true;
}

export async function getCurrentPerm(): Promise<Perm|null> {
    return await axios.post(BASE_URL + '/perms', {
        withCredentials: true,
    }).then(res => {
        if (res.status === 200) {
            //sessionStorage.setItem('userPerm', JSON.stringify(res.data.data));
            //console.log('Perm store');
            //console.log(res.data.data);
            return res.data;
        }   
    }).catch(err => {
        if (err.response.status === 401) {
            localStorage.setItem('isUserLogged', 'false');
            console.log('Unauthorized');
            return null
        } else {
            //console.error("An error occurred while loading current user:", error);
            // TODO: Add logout logic
            localStorage.setItem('isUserLogged', 'false');
            console.log("storeCurrentUser() - something fucked up - ");
            //authStore.authError = true;
            //console.log("Redirecting to login page");
            //router.push({ name: 'Login' });
            return null;
        }
    })
}

export async function getCurrentUser(): Promise<UserData|null> {
    return await axios.post(BASE_URL + '/users', {
        withCredentials: true,
    }).then(res => {
        if (res.status === 200) {
            //sessionStorage.setItem('userData', JSON.stringify(res.data.data));
            //console.log('User store');
            //console.log(res.data);
            return res.data;
        }
    }).catch(err => {
        if (err.response.status === 401) {
            localStorage.setItem('isUserLogged', 'false');
            console.log('Unauthorized');
            return null;
        } else {
            //console.error("An error occurred while loading current user:", error);
            // TODO: Add logout logic
            localStorage.setItem('isUserLogged', 'false');
            console.log("storeCurrentUser() - something fucked up - ");
            //authStore.authError = true;
            //console.log("Redirecting to login page");
            //router.push({ name: 'Login' });
            return null;
        }
    });
}

// Function to get user data
export async function getToken(): Promise<string | null> {
    try {
        const res = await axios.get(BASE_URL + '/token');

        if (res.status === 200) {
            return res.data;
        }
    } catch (error) {
        console.error("An error occurred while authenticating user:", error);
        return null;
    }
    return null;
}

// Function to get user data
export async function acceptReview(article_id: number, review_id: number): Promise<boolean> {
    const path1 = BASE_URL + '/article/' + article_id + '/update';
    const path2 = BASE_URL + '/review/' + review_id + '/update';

    //console.log(path);
    try {
        const res1 = await axios.post(path1, {
            accepted: 1,
        });

        if (res1.status === 200) {
            const res2 = await axios.post(path2, {
                finished: 1,
            });

            if (res2.status === 200) {
                return true;
            }
            return false
        }
    } catch (error) {
        console.error("An error occurred while authenticating user:", error);
        return false;
    }
    return false;
}

export async function createReview(article_id: number, user_id: number): Promise<boolean> {
    try {
        const res = await axios.post(BASE_URL + '/review/create', {
            article_id: article_id,
            user_id: user_id,
        });

        if (res.status === 200) {
            return true;
        }
    } catch (error) {
        console.error("An error occurred while authenticating user:", error);
        return false;
    }
    return false;
}

export async function updateArticleReviewed(article_id: number, reviewed: number): Promise<boolean> {
    try {
        const res = await axios.post(BASE_URL + '/article/'+ article_id +'/reviewed', {
            article_id: article_id,
            reviewed: reviewed,
        });

        if (res.status === 200) {
            console.log(res.data);
            return true;
        }
    } catch (error) {
        console.error("An error occurred while authenticating user:", error);
        return false;
    }
    console.log('Damn');
    return false;
}

/*
interface GetUserDataResponse {
    data: UserData;
}

// Function to get user data
export async function getUserData(sessionToken: string): Promise<GetUserDataResponse | null> {
    sessionToken = trimToken(sessionToken);

    // Check if the user is authenticated
    if (!auth(sessionToken)) {
        localStorage.removeItem('authorization');
        store.sessionToken = '';
        router.push({ name: 'Login' });
        return null;
    }

    try {
        const response = await axios.post<GetUserDataResponse>(
            'http://localhost:8080/kivweb/backend/api/index.php/user',
            {},
            {
                headers: {
                    'Authorization': sessionToken
                }
            }
        );

        if (response.status === 200) {
            return response.data;
        }
    } catch(error) {
        //console.error('Error:', error);
        return null;
    }
    return null;
    
}


// Function to get user data
export function login(user_email: string, user_password: string): void {
    try {
        axios({
            method: 'post',
            url: 'http://localhost:8080/kivweb/backend/api/index.php/login',
            data: {
                user_email: user_email,
                user_password: user_password
            }
        }).then(response => {
            const authorization = response.headers["authorization"];

            if (localStorage.getItem('authorization') != null) {
                localStorage.removeItem('authorization');
            }
            
            if (authorization) {
                localStorage.setItem('authorization', authorization);
                store.sessionToken = authorization;
                store.isUserLogged = true;
                router.push({name: 'Home'});
            }
        });
    } catch (error) {
        console.error('Error logging in:', error);
        return;
    }
}

// Function to get user data
export function refreshToken(sessionToken: string): void {
    // Check if the user is authenticated
    sessionToken = trimToken(sessionToken); 
    
    if (!auth(sessionToken)) {
        localStorage.removeItem('authorization');
        store.sessionToken = '';
        router.push({name: 'Login'}); 
    }

    axios({
        method: 'post',
        url: 'http://localhost:8080/kivweb/backend/api/index.php/refreshToken',
        headers: {
            'Authorization': sessionToken,
        }
    }).then(response => {
        //console.log(response.headers["authorization"]);
        if (localStorage.getItem('authorization') != null) {
            localStorage.removeItem('authorization');
        }
        localStorage.setItem('authorization', response.headers["authorization"]);
        
        router.push({name: 'Home'})
    });
}

// Function to authenticate user
// If the token is valid, it returns the session token
export function auth(sessionToken: string): boolean {
    sessionToken = trimToken(sessionToken);
    
    console.log(sessionToken);
    
    try {
        axios({
            method: 'post',
            url: 'http://localhost:8080/kivweb/backend/api/index.php/auth', 
            headers: {
                'Authorization': sessionToken
            }
        }).then(response => {
            if (response.status == 200) {
                return true;
            }
            
        }).catch(error => {
            if (error.status == 408) {
                //localStorage.removeItem('authorization');
                //router.push({name: 'Login'});
                return false;
            }
        });
    } catch (error) {
        console.error('Error authenticating user:', error);
        return false;
    }
    
    return false;
}

export function trimToken(token: string): string {
    let trimmedToken: string = '';

    if (token.substring(0, 6) == 'Bearer') {
        trimmedToken = token.substring(6).trim();
    } else {
        trimmedToken = token;
    }

    return trimmedToken;
}
*/
