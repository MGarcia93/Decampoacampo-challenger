export default class table{    

    constructor(selector, templateRowsSelector,map) {
        this.table = document.querySelector(selector);
        this.countColumns = this.table.querySelectorAll('thead th').length;
        this.map = map;
        this.templateRows = document.querySelector(templateRowsSelector);
    }
    setLoading(loading) {
        this.loading = loading;
        const tbody = this.table.querySelector('tbody');
        tbody.innerHTML = '';
        if (loading) {
            tbody.innerHTML = `<tr><td colspan="${this.countColumns}">Loading...</td></tr>`;
        } 
    }
    setData(data) {
        const tbody = this.table.querySelector('tbody');
        tbody.innerHTML = '';
        if (data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="${this.countColumns}">No se encontro ningun resultado</td></tr>`;
            return;
        }
        if (this.map) {
            data.forEach(item => {
                this.addRow(item);
            });
        }
    }
    addRow(item) {
        const tbody = this.table.querySelector('tbody');
        const row = this.templateRows.content.cloneNode(true);
        row.querySelector('tr').dataset.id = item.id;
        this.mapper(row, item);
        tbody.appendChild(row);
    }

    mapper(row, item) {        
        for (const [key, value] of Object.entries(item)) {
            const name = this.map[key];
            const cell = row.querySelector(`td[data-name="${name}"]`);
            if (cell) {
                cell.textContent = value;
            }
        }        
    }
}