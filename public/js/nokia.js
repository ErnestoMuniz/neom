// Função que requisita e trata os dados das onus de uma pon
function getPON(id, pon, srch){
    // cria icone de carregando
    document.getElementById('pon').innerHTML = "<div class='text-center'><i class='gg-loadbar inline-block mx-auto align-middle mr-1'></i><span>Carregando</span></div>";
    // faz a requisição
    axios.get(`/get/nokia/pon?id=${id}&pon=${pon}`)
        .then(function (response){
            // trata o XML recebido
            let parser = new DOMParser();
            let xml = parser.parseFromString(response.data, "text/xml");
            xml = xml.getElementsByTagName("instance");
            // remove o ícone de carregando
            pon = document.getElementById('pon');
            // cria a tabela de ONUs
            pon.innerHTML = "";
            // Loop para preencher a tabela
            for (let ont in xml){
                let pos = xml[ont].children[1].innerHTML.replace("1/1/", "").split('/')[2];
                let status;
                if (xml[ont].children[4].innerHTML === "up"){
                    status = "<span class='badge badge-success'>Active</span>";
                } else {
                    status = "<span class='badge badge-danger'>Inactive</span>";
                }
                let desc = xml[ont].children[7].innerHTML;
                let sinal;
                if (xml[ont].children[5].innerHTML === "invalid"){
                    sinal = "-40.0";
                } else {
                    sinal = xml[ont].children[5].innerHTML;
                }
                if (sinal > -25) {
                    sinal = `<span class='badge badge-success'>${sinal}</span>`;
                } else if ((sinal >= -28) && (sinal <= -25)) {
                    sinal = `<span class='badge badge-warning'>${sinal}</span>`;
                } else {
                    sinal = `<span class='badge badge-danger'>${sinal}</span>`;
                }
                let serial = xml[ont].children[2].innerHTML;
                if (xml[ont].children[1].innerHTML === srch){
                    pon.innerHTML += `<tr class="border-2 table-active font-bold"><td>${pos}</td><td>${status}</td><td>${desc}<td>${sinal}</td><td>${serial}</td></tr>`;
                } else {
                    pon.innerHTML += `<tr class="border-b"><td>${pos}</td><td>${status}</td><td>${desc}<td>${sinal}</td><td>${serial}</td></tr>`;
                }
            }
            document.getElementById('onus').className = 'table-sort';
        })
        .catch(function (error){
            console.log(error);
        });
}

function getONU(id){
    let param = document.getElementById('search').value;
    axios.get(`/get/nokia/onu?id=${id}&onu=${param}`).then(function (response){
        let pon = response.data.split('/');
        getPON(id, `${pon[2]}/${pon[3]}`, response.data);
        pon.innerHTML = "";
    })
}

function getPending(id){
    let param = document.getElementById('search').value;
    document.getElementById('request').innerHTML = "<div class='text-center'><i class='gg-loadbar inline-block mx-auto align-middle mr-1'></i><span>Carregando</span></div>";
    axios.get(`/get/nokia/pending?id=${id}`).then(function (response){
        let parser = new DOMParser();
        let xml = parser.parseFromString(response.data, "text/xml");
        xml = xml.getElementsByTagName("instance");
        let pon = document.getElementById('request');
        pon.innerHTML = "";
        for (let ont in xml){
            let pos = xml[ont].children[1].innerHTML;
            let serial = xml[ont].children[2].innerHTML;
            pon.innerHTML += `<tr class="border-b"><td>${pos}</td><td>${serial}</td></tr>`;
        }
    })
}
