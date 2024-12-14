import { defineStore } from "pinia";
import { type Article } from "@/types";


export const useArticleStore = defineStore('article', {
    state: () => ({
        articles: [] as Article[] | null,
        countAccepted: 0 as number,
        countReview: 0 as number,
        numberOfContentInPage: 5, // must be same as in backend
        page: 1 as number,
    }),
    getters: {
        getArticlesToReview: (state) => { // Fixed typo in getter name
            return state.articles?.filter(article => article.accepted === false);
        }
    }
});
