document.addEventListener('DOMContentLoaded', function(){
    // Search functionality for index.php
    const searchInput = document.getElementById('searchInput');
    if(searchInput){
        const table = document.getElementById('pengembalianTable');
        if(table){
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            searchInput.addEventListener('keyup', function(){
                const filter = searchInput.value.toLowerCase();
                for(let i = 0; i < rows.length; i++){
                    const row = rows[i];
                    const cells = row.getElementsByTagName('td');
                    let found = false;
                    for(let j = 0; j < cells.length - 1; j++){
                        const cellText = cells[j].textContent || cells[j].innerText;
                        if(cellText.toLowerCase().indexOf(filter) > -1){
                            found = true;
                            break;
                        }
                    }
                    row.style.display = found ? '' : 'none';
                }
            });
        }
    }

    // Row click effect for index.php
    const tableBody = document.querySelector('#pengembalianTable tbody');
    if(tableBody){
        const rows = tableBody.getElementsByTagName('tr');
        Array.from(rows).forEach(row => {
            row.addEventListener('click', function(e){
                if(!e.target.closest('.btn')){
                    this.style.backgroundColor = '#e8f4f8';
                    setTimeout(() => { this.style.backgroundColor = ''; }, 200);
                }
            });
        });
    }
});
