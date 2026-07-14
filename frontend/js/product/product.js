import * as api from '../api.js';

export default class ProductService {
    async createProduct(product) {
        this.validateProduct(product);

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

    validateProduct(product) {
        if (!product || typeof product !== 'object') {
            throw new Error('Los datos del producto no son válidos');
        }

        if (
            typeof product.nombre !== 'string' ||
            product.nombre.trim() === ''
        ) {
            throw new Error('El nombre del producto es obligatorio');
        }

        if (
            typeof product.descripcion !== 'string' ||
            product.descripcion.trim() === ''
        ) {
            throw new Error('La descripción del producto es obligatoria');
        }

        const price = Number(product.precio);

        if (!Number.isFinite(price) || price <= 0) {
            throw new Error('El precio debe ser mayor a cero');
        }
    }

    getProductFromResponse(response, errorMessage) {
        if (!response?.data || typeof response.data !== 'object') {
            throw new Error(errorMessage);
        }

        return response.data;
    }
}