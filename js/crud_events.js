 // Plant Records Table Functionality
        document.getElementById('add-row').addEventListener('click', function() {
            const table = document.getElementById('plant-records-table').getElementsByTagName('tbody')[0];
            const newRow = table.insertRow(table.rows.length);
            const cells = [];
            
            for (let i = 0; i < 5; i++) {
                cells.push(newRow.insertCell(i));
                if (i < 4) {
                    const input = document.createElement('input');
                    input.type = i === 0 ? 'date' : 'text';
                    input.className = 'form-control';
                    cells[i].appendChild(input);
                } else {
                    const saveIcon = document.createElement('i');
                    saveIcon.className = 'bi bi-check-circle text-success action-btn';
                    saveIcon.onclick = function() { saveRow(newRow); };
                    
                    const cancelIcon = document.createElement('i');
                    cancelIcon.className = 'bi bi-x-circle text-danger action-btn';
                    cancelIcon.onclick = function() { cancelEdit(newRow); };
                    
                    cells[i].appendChild(saveIcon);
                    cells[i].appendChild(cancelIcon);
                }
            }
        });

        function editRow(row) {
            const cells = row.cells;
            for (let i = 0; i < cells.length - 1; i++) {
                const text = cells[i].textContent;
                cells[i].textContent = '';
                const input = document.createElement('input');
                input.type = i === 0 ? 'date' : 'text';
                input.className = 'form-control';
                input.value = text;
                cells[i].appendChild(input);
            }
            
            const actionsCell = cells[cells.length - 1];
            actionsCell.innerHTML = '';
            const saveIcon = document.createElement('i');
            saveIcon.className = 'bi bi-check-circle text-success action-btn';
            saveIcon.onclick = function() { saveRow(row); };
            
            const cancelIcon = document.createElement('i');
            cancelIcon.className = 'bi bi-x-circle text-danger action-btn';
            cancelIcon.onclick = function() { cancelEdit(row); };
            
            actionsCell.appendChild(saveIcon);
            actionsCell.appendChild(cancelIcon);
        }

        function saveRow(row) {
            const cells = row.cells;
            for (let i = 0; i < cells.length - 1; i++) {
                const input = cells[i].getElementsByTagName('input')[0];
                cells[i].textContent = input.value;
            }
            
            const actionsCell = cells[cells.length - 1];
            actionsCell.innerHTML = '';
            const editIcon = document.createElement('i');
            editIcon.className = 'bi bi-pencil-square text-primary action-btn edit-row';
            editIcon.onclick = function() { editRow(row); };
            
            const deleteIcon = document.createElement('i');
            deleteIcon.className = 'bi bi-trash text-danger action-btn delete-row';
            deleteIcon.onclick = function() { deleteRow(row); };
            
            actionsCell.appendChild(editIcon);
            actionsCell.appendChild(deleteIcon);
        }

        function cancelEdit(row) {
            if (row.rowIndex === row.parentNode.rows.length - 1 && row.cells[0].getElementsByTagName('input').length > 0) {
                row.parentNode.removeChild(row);
            } else {
                const cells = row.cells;
                for (let i = 0; i < cells.length - 1; i++) {
                    const input = cells[i].getElementsByTagName('input')[0];
                    cells[i].textContent = input.defaultValue || '';
                }
                
                const actionsCell = cells[cells.length - 1];
                actionsCell.innerHTML = '';
                const editIcon = document.createElement('i');
                editIcon.className = 'bi bi-pencil-square text-primary action-btn edit-row';
                editIcon.onclick = function() { editRow(row); };
                
                const deleteIcon = document.createElement('i');
                deleteIcon.className = 'bi bi-trash text-danger action-btn delete-row';
                deleteIcon.onclick = function() { deleteRow(row); };
                
                actionsCell.appendChild(editIcon);
                actionsCell.appendChild(deleteIcon);
            }
        }

        function deleteRow(row) {
            if (confirm('Are you sure you want to delete this record?')) {
                row.parentNode.removeChild(row);
            }
        }

        document.querySelectorAll('.edit-row').forEach(icon => {
            icon.addEventListener('click', function() {
                editRow(this.closest('tr'));
            });
        });

        document.querySelectorAll('.delete-row').forEach(icon => {
            icon.addEventListener('click', function() {
                deleteRow(this.closest('tr'));
            });
        });
