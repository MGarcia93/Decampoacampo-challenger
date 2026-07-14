class message{
    constructor(){
    }
    showMessage(message, type = 'success') {
        const messageDialog = document.getElementById('message-dialog');
        const messageDialogTitle = document.getElementById('message-dialog-title');
        const messageDialogMessage = document.getElementById('message-dialog-message');
        const messageDialogCloseButton = document.getElementById('btn-close-message');
        messageDialogCloseButton.addEventListener('click', () => {
            messageDialog.close();
        });
        messageDialogTitle.textContent = type === 'error' ? 'Error' : 'Mensaje';
        messageDialogMessage.textContent = message;
        messageDialog.showModal();
    }
}

export default new message();