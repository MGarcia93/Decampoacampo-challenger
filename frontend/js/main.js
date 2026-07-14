import * as api from './api.js';
import ProductService from './product/product.js';
import actionsProduct from './product/actions.js';
import message from './ui/message.js';
import table from './ui/table.js';

const mapColumns = {
    id: 'id',
    nombre: 'name',
    descripcion: 'description',
    precio: 'price',
    precio_usd: 'price_usd',
    created_at: 'created_at',
    updated_at: 'updated_at',
};
class main {
    
    constructor() {
        this.productService = new ProductService();
        this.table=new table('#product-table', '#product-row-template', mapColumns);
        this.init();
    }

    async init() {
        try{

            const products = await this.productService.getProducts();
            this.table.setData(products);   
        }catch (error) {
            message.showMessage(error.message || 'Ocurrió un error al obtener los productos');
        }
        this.actionsProduct = new actionsProduct(this.productService, this.table);
        this.actionsProduct.setActions();
    }
          
}

new main();