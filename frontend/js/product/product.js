import * as api from '../api.js';

export default class ProductService {
    async createProduct(product) {

        const response = await api.createProduct(product);

        return this.getProductFromResponse(
            response,
            'La respuesta al crear el producto no es válida'
        );
    }

    async getProducts() {
        const response = await api.getProducts();

        if (!Array.isArray(response?.data)) {
            throw new Error(
                'La respuesta de productos no contiene una lista válida'
            );
        }

        return response.data;
    }

    async updateProduct(id, product) {
        this.validateId(id);
        this.validateProduct(product);

        const response = await api.updateProduct(id, product);

        return this.getProductFromResponse(
            response,
            'La respuesta al actualizar el producto no es válida'
        );
    }

    async deleteProduct(id) {
        this.validateId(id);

        await api.deleteProduct(id);
    }

    async getProductById(id) {
        this.validateId(id);

        const response = await api.getProductById(id);

        return this.getProductFromResponse(
            response,
            'La respuesta del producto no es válida'
        );
    }

    validateId(id) {
        const productId = Number(id);

        if (!Number.isInteger(productId) || productId <= 0) {
            throw new Error('El identificador del producto no es válido');
        }
    }


    getProductFromResponse(response, errorMessage) {
        if (!response?.data || typeof response.data !== 'object') {
            throw new Error(errorMessage);
        }

        return response.data;
    }
}