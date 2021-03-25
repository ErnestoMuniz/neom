// Função que requisita e trata os dados das onus de uma pon
function getPON(id, pon){
    // cria icone de carregando
    document.getElementById('pon').innerHTML = "<div class='text-center'><i class='gg-loadbar inline-block mx-auto align-middle mr-1'></i><span>Carregando</span></div>";
    // faz a requisição
    axios.get(`/pon?id=${id}&pon=${pon}`)
        .then(function (response){
            // trata o XML recebido
            parser = new DOMParser();
            xml = parser.parseFromString(response.data, "text/xml");
            xml = xml.getElementsByTagName("instance");
            // remove o ícone de carregando
            pon = document.getElementById('pon');
            // cria a tabela de ONUs
            pon.innerHTML = "<table id='onus' class='table-auto mx-auto rounded-l'><thead><tr class='bg-gray-300 uppercase text-sm'><th onclick='sortNum(0)' class='py-1 px-2 cursor-pointer'>Num</th><th class='py-1 px-2 cursor-pointer' onclick='sortStr(1)'>Status</th><th class='py-1 px-2 cursor-pointer' onclick='sortStr(2)'>Descrição</th><th class='py-1 px-2 cursor-pointer' onclick='sortNum(3)'>Sinal</th><th onclick='sortStr(4)' class='cursor-pointer'>Serial</th></tr></thead><tbody></tbody></table>";
            pon = pon.children[0].children[1];
            let flip = false;
            // Loop para preencher a tabela
            for (let ont in xml){
                let pos = xml[ont].children[1].innerHTML.replace("1/1/", "").split('/')[2];
                let status = xml[ont].children[4].innerHTML;
                let desc = xml[ont].children[7].innerHTML;
                let sinal;
                if (xml[ont].children[5].innerHTML == "invalid"){
                    sinal = "-40.0";
                } else {
                    sinal = xml[ont].children[5].innerHTML;
                }
                let serial = xml[ont].children[2].innerHTML;
                if (flip){
                    pon.innerHTML += `<tr class="bg-gray-100"><td class='py-1 px-2'>${pos}</td><td class='py-1 px-2'>${status}</td><td class='py-1 px-2'>${desc}<td class='py-1 px-2'>${sinal}</td><td>${serial}</td></tr>`;
                } else {
                    pon.innerHTML += `<tr class="bg-white"><td class='py-1 px-2'>${pos}</td><td class='py-1 px-2'>${status}</td><td class='py-1 px-2'>${desc}<td class='py-1 px-2'>${sinal}</td><td>${serial}</td></tr>`;
                }
                flip = !flip;
            }
            document.getElementById('onus').className = 'table-sort';
        })
        .catch(function (error){
            console.log(error);
        });
}

// organizar as tabelas de números
function sortNum(n) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("onus");
    switching = true;
    // Set the sorting direction to ascending:
    dir = "asc";
    /* Make a loop that will continue until
    no switching has been done: */
    while (switching) {
        // Start by saying: no switching is done:
        switching = false;
        rows = table.rows;
        /* Loop through all table rows (except the
        first, which contains table headers): */
        for (i = 1; i < (rows.length - 1); i++) {
            // Start by saying there should be no switching:
            shouldSwitch = false;
            /* Get the two elements you want to compare,
            one from current row and one from the next: */
            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i + 1].getElementsByTagName("TD")[n];
            /* Check if the two rows should switch place,
            based on the direction, asc or desc: */
            if (dir == "asc") {
                if (Number(x.innerHTML) > Number(y.innerHTML)) {
                    shouldSwitch = true;
                    break;
                }
            } else if (dir == "desc") {
                if (Number(x.innerHTML) < Number(y.innerHTML)) {
                    shouldSwitch = true;
                    break;
                }
            }
        }
        if (shouldSwitch) {
            /* If a switch has been marked, make the switch
            and mark that a switch has been done: */
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            // Each time a switch is done, increase this count by 1:
            switchcount ++;
        } else {
            /* If no switching has been done AND the direction is "asc",
            set the direction to "desc" and run the while loop again. */
            if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
}
// organizar as tabelas de texto
function sortStr(n) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("onus");
    switching = true;
    // Set the sorting direction to ascending:
    dir = "asc";
    /* Make a loop that will continue until
    no switching has been done: */
    while (switching) {
        // Start by saying: no switching is done:
        switching = false;
        rows = table.rows;
        /* Loop through all table rows (except the
        first, which contains table headers): */
        for (i = 1; i < (rows.length - 1); i++) {
            // Start by saying there should be no switching:
            shouldSwitch = false;
            /* Get the two elements you want to compare,
            one from current row and one from the next: */
            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i + 1].getElementsByTagName("TD")[n];
            /* Check if the two rows should switch place,
            based on the direction, asc or desc: */
            if (dir == "asc") {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                    // If so, mark as a switch and break the loop:
                    shouldSwitch = true;
                    break;
                }
            } else if (dir == "desc") {
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                    // If so, mark as a switch and break the loop:
                    shouldSwitch = true;
                    break;
                }
            }
        }
        if (shouldSwitch) {
            /* If a switch has been marked, make the switch
            and mark that a switch has been done: */
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            // Each time a switch is done, increase this count by 1:
            switchcount ++;
        } else {
            /* If no switching has been done AND the direction is "asc",
            set the direction to "desc" and run the while loop again. */
            if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
}
