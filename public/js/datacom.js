// Função que requisita e trata os dados das onus de uma pon
function getPON(id, pon, srch){
    // cria icone de carregando
    document.getElementById('pon').innerHTML = "<div class='text-center'><i class=\"las la-spinner\" style='animation:spin 4s linear infinite;'></i> <span>Carregando</span></div>";
    // faz a requisição
    axios.get(`/get/datacom/pon?id=${id}&pon=${pon}`)
        .then(function (response){
            // trata o JSON recebido
            let json = response.data['data']['dmos-base:config']['interface']['interface-gpon:gpon'][0]['onu:onu'];
            if (srch === '0'){
                srch = -1;
            }
            // remove o ícone de carregando
            pon = document.getElementById('pon');
            // cria a tabela de ONUs
            pon.innerHTML = "";
            // Loop para preencher a tabela
            for (let ont in json){
                let pos = json[ont]['id'];
                let status;
                if (json[ont]['onu-status:onu-status']['oper-state'] === "Up"){
                    status = "<span class='badge badge-success'>Active</span>";
                } else {
                    status = "<span class='badge badge-danger'>Inactive</span>";
                }
                let desc = json[ont]['profile-references']['line-profile'];
                let sinal;
                if (json[ont]['onu-status:onu-status']['rx-optical-pw'] == "0.00"){
                    sinal = "-40.0";
                } else {
                    sinal = json[ont]['onu-status:onu-status']['rx-optical-pw'];
                }
                if (sinal > -25) {
                    sinal = `<span class='badge badge-success'>${sinal}</span>`;
                } else if ((sinal >= -28) && (sinal <= -25)) {
                    sinal = `<span class='badge badge-warning'>${sinal}</span>`;
                } else {
                    sinal = `<span class='badge badge-danger'>${sinal}</span>`;
                }
                let serial = json[ont]['auth-method']['serial-number'];
                if (json[ont]['id'] == srch){
                    pon.innerHTML += `<tr class="border-2 table-active font-bold"><td>${pos}</td><td>${status}</td><td>${desc}<td>${sinal}</td><td>${serial}</td><td class="text-center"><i class="las la-times-circle text-danger"></i></td></tr>`;
                } else {
                    pon.innerHTML += `<tr class="border-b"><td>${pos}</td><td>${status}</td><td>${desc}<td>${sinal}</td><td>${serial}</td><td class="text-center"><i class="las la-times-circle text-danger"></i></td></tr>`;
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
    document.getElementById('btn-search').innerHTML = "<i class=\"las la-spinner\" style='animation:spin 4s linear infinite;'></i>";
    axios.get(`/get/datacom/onu?id=${id}&onu=${param}`).then(function (response){
        let pon = response.data.split('/');
        getPON(id, `${pon[1]}/${pon[2]}`, pon[3]);
        document.getElementById('btn-search').innerHTML = "<i class=\"las la-search\"></i>";
    })
}
