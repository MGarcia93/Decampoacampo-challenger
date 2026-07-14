import message from '../ui/message.js';
export default class actionsProduct {
    constructor(productService, table) {
        this.productService = productService;
        this.table = table;
         
    }

    async setActions() {
        this.btnAddProduct = document.getElementById('btn-add-product');
        this.btnAddProduct.onclick = () => this.showForm();
        this.btnSaveProduct = document.getElementById('btn-save-product');
        this.btnSaveProduct.onclick = () => this.saveProduct();
        this.btnCancelProduct = document.getElementById('btn-cancel-product');
        this.btnCancelProduct.onclick = () => this.cancelForm();
        const editButtons = document.querySelectorAll('.btn-edit');
        editButtons.forEach(button => {
            button.addEventListener('click', async (event) => {
                try{
                    const productId = this.getRowElementByChild(event.target).dataset.id;
                    this.product = await this.productService.getProductById(productId);
                    this.showForm();
                } catch (error) {
                    message.showMessage(error.message || 'Ocurrió un error al obtener el producto');
                }
            });
        });
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', async (event) => {
                const productId = this.getRowElementByChild(event.target).dataset.id;
                const confirmDelete = document.getElementById('confirm-dialog');
                confirmDelete.showModal();
                const btnConfirmDelete = document.getElementById('btn-confirm-delete');
                const btnCancelDelete = document.getElementById('btn-cancel-delete');
                btnConfirmDelete.onclick = async () => {
                    await this.deleteProduct(productId);
                    confirmDelete.close();
                };
                btnCancelDelete.onclick = () => {
                    confirmDelete.close();
                };
            });
        });
    }

    showForm() {
        const formDialog = document.getElementById('product-dialog');
        formDialog.showModal();
        if(this.product){
            const form = document.getElementById('product-form');
            form.elements['product-name'].value = this.product.nombre;
            form.elements['product-description'].value = this.product.descripcion;
            form.elements['product-price'].value = this.product.precio;
        }
    }
  
   
    async cancelForm() {
        const formDialog = document.getElementById('product-dialog');
        formDialog.close();
        const form = document.getElementById('product-form');
        form.reset();
        this.product=null;
    } 
    getRowElementByChild(child) {
        let row = child.closest('tr');
        return row;
    }

     async deleteProduct(id) {
        try{

            await this.productService.deleteProduct(id);
            message.showMessage('Producto eliminado correctamente');
            const products = await this.productService.getProducts();
            this.table.setData(products);
        }catch (error) {
            message.showMessage(error.message || 'Ocurrió un error al eliminar el producto');
        }
    }
    async saveProduct() {
        try{
        
            const form = document.getElementById('product-form');
            const product = {
                nombre: form.elements['product-name'].value,
                descripcion: form.elements['product-description'].value,
                precio: parseFloat(form.elements['product-price'].value),
            };
            if(this.product){
                await this.productService.updateProduct(this.product.id, product);
                message.showMessage('Producto actualizado correctamente');
            }           
            else{
                await this.productService.createProduct(product);
                message.showMessage('Producto creado correctamente');
            }
            const products = await this.productService.getProducts();
            this.table.setData(products);
        } catch (error) {
            message.showMessage(error.message || 'Ocurrió un error al guardar el producto');
        } finally {
            this.cancelForm();
        }
    }
}