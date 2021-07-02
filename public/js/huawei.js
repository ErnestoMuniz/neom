// Função que requisita e trata os dados das onus de uma pon
function getPON(id, pon, srch){
    // cria icone de carregando
    document.getElementById('pon').innerHTML = "<div class='text-center'><i class='gg-loadbar inline-block mx-auto align-middle mr-1'></i><span>Carregando</span></div>";
    // faz a requisição
    axios.get(`/get/huawei/pon?id=${id}&pon=${pon}`)
        .then(function (response){
            let json = response.data;
            // remove o ícone de carregando
            pon = document.getElementById('pon');
            // cria a tabela de ONUs
            pon.innerHTML = "";
            // Loop para preencher a tabela
            for (let ont in json){
                let onu = json[ont].split(' ');
                onu = onu.filter(function (el) {
                    return el != "";
                });
                console.log(onu);
                let pos = onu[0];
                let status;
                if (onu[4].split('/')[0] != "-40.00"){
                    status = "<span class='badge badge-success'>Active</span>";
                } else {
                    status = "<span class='badge badge-danger'>Inactive</span>";
                }
                let desc = onu[5];
                let sinal = onu[4].split('/')[0];
                let serial = "HWTC" + onu[1][8] + onu[1][9] + onu[1][10] + onu[1][11] + onu[1][12] + onu[1][13] + onu[1][14] + onu[1][15];
                if (onu[0] === srch){
                    pon.innerHTML += `<tr class="border-2 table-active font-bold"><td>${pos}</td><td>${status}</td><td>${desc}<td>${sinal}</td><td>${serial}</td></tr>`;
                } else {
                    pon.innerHTML += `<tr class="border-b"><td>${pos}</td><td>${status}</td><td>${desc}<td>${sinal}</td><td>${serial}</td></tr>`;
                }
            }
            document.getElementById('onus').className = 'table table-striped w-full mx-auto overflow-hidden m-0 table-sort';
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
    axios.get(`/get/huawei/pending?id=${id}`).then(function (response){
        let json = response.data;
        let pon = document.getElementById('request');
        pon.innerHTML = "";
        for (let ont in json){
            let onu = json[ont].split(' ');
            onu = onu.filter(function (el) {
                return el != "";
            });
            console.log(onu);
            let pos = onu[6].replace('\r\n', '');
            let serial = onu[11].split(')')[0].replace('(', '');
            pon.innerHTML += `<tr class="border-b"><td>${pos}</td><td>${serial}</td></tr>`;
        }
    })
}
