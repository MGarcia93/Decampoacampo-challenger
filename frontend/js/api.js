import { config } from '../config.js';

async function fetchData(endpoint, method = 'GET', body = null) {
    const url = `${config.url_api}/${endpoint}`;

    const options = {
        method,
        headers: {
            Accept: 'application/json',
        },
    };

    if (body !== null) {
        options.headers['Content-Type'] = 'application/json';
        options.body = JSON.stringify(body);
    }

    let response;

    try {
        response = await fetch(url, options);
    } catch (error) {
        console.error('Error de conexión:', error);

        throw {
            error: 'No se pudo conectar con el servidor',
            code: 0,
        };
    }

    if (response.status === 204) {
        return null;
    }

    let data;

    try {
        data = await response.json();
    } catch {
        throw {
            error: 'El servidor devolvió una respuesta inválida',
            code: response.status,
        };
    }

    if (!response.ok) {
        throw {
            error: data.error ?? 'Ocurrió un error',
            code: data.code ?? response.status,
        };
    }

    return data;
}

function createProduct(product) {
    return fetchData('productos', 'POST', product);
}

function getProducts() {
    return fetchData('productos');
}

function updateProduct(id, product) {
    return fetchData(`productos/${id}`, 'PUT', product);
}

function deleteProduct(id) {
    return fetchData(`productos/${id}`, 'DELETE');
}

function getProductById(id) {
    return fetchData(`productos/${id}`);
}

export {
    createProduct,
    getProducts,
    updateProduct,
    deleteProduct,
    getProductById,
};