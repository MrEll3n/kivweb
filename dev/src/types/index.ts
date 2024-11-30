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
}