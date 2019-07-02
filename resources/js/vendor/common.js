import {HTTP} from "../bootstrap"
import httpBuildQuery from 'http-build-query'

/**
 * @returns {string}
 */
export function searchID() {
    let path = window.location.pathname;
    let arr = path.split('/');
    return arr[arr.length - 1];
}

/**
 *
 * @param url
 * @param params
 * @param headers
 * @returns {Promise<any>}
 */
export async function toSeek(url, params = null, headers = {}) {

    if(params !== null) url = url + '?' + httpBuildQuery(params)

    return await new Promise((resolve, reject) => {
        HTTP.get(url, {headers: headers}).then(
            response => resolve(response.data)
        ).catch(
            e => reject(e)
        )
    })
}

/**
 * @param url
 * @param dataForm
 * @param _method
 * @param headers
 * @returns {Promise<any>}
 */
export async function sendCommon(url, dataForm, _method, headers = {}) {
    let data = new FormData()

    for (let key in dataForm) {
        data.set(key, dataForm[key])
    }

    return await new Promise((resolve, reject) => {
        HTTP({
            method: _method,
            url: url,
            data: data,
            headers: headers
        }).then(
            response => {
                resolve(response.data)
            }
        ).catch(
            error => {
                reject(error)
            }
        )
    })
}

/**
 * @param url
 * @param data
 * @param headers
 * @returns {Promise<any>}
 */
export function submitFormVue(url, data, headers = {}) {

    return new Promise((resolve, reject) => {
        HTTP({
            method: 'POST',
            url: url,
            data: data,
            headers: headers
        }).then(
            response => {
                resolve(response)
            }
        ).catch(
            error => {
                reject(error)
            }
        )
    })
}

/**
 * @param url
 * @param data
 * @param method
 * @param headers
 * @returns {Promise<any>}
 */
export async function sendAPI(url, data, method, headers = {}) {
    let promise = new Promise((resolve, reject) => {
        HTTP({
            method: method,
            url: url,
            data: data,
            headers: headers
        }).then(
            response => {
                resolve(response)
            }
        ).catch(
            error => {
                reject(error)
            }
        )
    });

    return await promise
}

/**
 * @param url
 * @param config
 * @returns {Promise<any>}
 */
export async function sendAPIDELETE(url, config = {}) {
    let promise = new Promise((resolve, reject) => {
        HTTP.delete(url, config).then(result => resolve(result)).catch((error) => reject(error))
    });

    return await promise
}

/**
 * @param arr
 */
export function sendAlert(arr) {
    $.NotificationApp.send(arr.title, arr.message, arr.position | 'top-right', 'rgba(0,0,0,0.2)', arr.type, arr.time | 3000)
}

/**
 * @param error
 */
export function exceptionError(error) {
    if (_.isObject(error.response)) {
        let message = error.response.data.errors ? error.response.data.errors.detail : error.response.data.message

        $.NotificationApp.send("Ops, algo deu errado!", message, 'top-right', 'rgba(0,0,0,0.2)', 'error')
    } else {
        console.dir(error)

        $.NotificationApp.send(
            "Algo está errado!",
            "Atualize a página e tente novamente. Se persistir o erro, entre em contato com a gente!",
            'top-right', 'rgba(0,0,0,0.2)', 'error'
        )
    }
}
