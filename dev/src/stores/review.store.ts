import { defineStore } from "pinia";
import { type Review } from "@/types";


export const useReviewStore = defineStore('review', {
    state: () => ({
        reviews: [] as Review[] | null,
        count: 0 as number,
        numberOfContentInPage: 5, // must be same as in backend
        page: 1 as number,
    })
});