export interface UserData {
    user_id: number,
    user_name: string,
    user_email: string
    perm_id: number,
    //perm_name: string,
    //perm_weight: number
}

export interface Perm {
    perm_id: number
    perm_name: string,
    perm_weight: number,
    disallowed_routes: string[]
}

export interface Article {
    article_id: number
    article_header: string
    article_content: string
    article_image: string
    article_created: Date
    article_author: string
    accepted: boolean
    reviewed: boolean
}

export interface Review {
    review_id: number,
    finished: boolean,
    article_id: number,
    article_header: string,
    article_content: string,
    article_image: string,
    article_created: Date,
    article_author: string,
    user_id: number,
    user_name: string,
    user_email: string
}

/*                
:review_id="review_item.review_id"
:article_id="review_item.article_id"
:article_header="review_item.article_header"
:article_content="review_item.article_content"
:article_image="review_item.article_image" */