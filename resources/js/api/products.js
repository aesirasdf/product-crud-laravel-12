import { _URL } from "./config"

export const ProductIndex = async (form) => {
    const url = new URL(`${_URL}/products`)
    Object.keys(form).forEach(k => {
        url.searchParams.set(k, form[k])
    })
    const res = await fetch(url, {
        method: "GET",
        headers:{
            Accept: 'application/json'
        }
    })

    return await res.json()
}