// Função que requisita e trata os dados das onus de uma pon
function getPON(id, pon, srch){
    // cria icone de carregando
    document.getElementById('pon').innerHTML = "<div class='text-center'><i class=\"las la-spinner\" style='animation:spin 4s linear infinite;'></i> <span>Carregando</span></div>";
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
                if (desc.length >= 21) {
                    desc = desc + '...';
                }
                let sinal = onu[4].split('/')[0];
                if (sinal > -25) {
                    sinal = `<span class='badge badge-success'>${sinal}</span>`;
                } else if ((sinal >= -28) && (sinal <= -25)) {
                    sinal = `<span class='badge badge-warning'>${sinal}</span>`;
                } else {
                    sinal = `<span class='badge badge-danger'>${sinal}</span>`;
                }
                let serial = "HWTC" + onu[1][8] + onu[1][9] + onu[1][10] + onu[1][11] + onu[1][12] + onu[1][13] + onu[1][14] + onu[1][15];
                if (onu[0] === srch){
                    pon.innerHTML += `<tr class="border-2 table-active font-bold"><td>${pos}</td><td>${status}</td><td>${desc}<td>${sinal}</td><td>${serial}</td><td class="text-center"><i class="las la-times-circle text-danger"></i></td></tr>`;
                } else {
                    pon.innerHTML += `<tr class="border-b"><td>${pos}</td><td>${status}</td><td>${desc}<td>${sinal}</td><td>${serial}</td><td class="text-center"><button type="button" class="btn btn-transparent"><i class="las la-times-circle text-danger" data-toggle="modal" data-target="#remove-modal"></i></button></td></tr>`;
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
    axios.get(`/get/nokia/onu?id=${id}&onu=${param}`).then(function (response){
        let pon = response.data.split('/');
        getPON(id, `${pon[2]}/${pon[3]}`, response.data);
        document.getElementById('btn-search').innerHTML = "<i class=\"las la-search\"></i>";
        pon.innerHTML = "";
    })
}

function getPending(id){
    let param = document.getElementById('search').value;
    document.getElementById('refresh-pending').style = "animation:spin 4s linear infinite;";
    axios.get(`/get/huawei/pending?id=${id}`).then(function (response){
        let json = response.data;
        let pon = document.getElementById('request');
        pon.innerHTML = "";
        document.getElementById('refresh-pending').style = "";
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

function modalRemove() {
    Swal.fire({
        title: 'Error!',
        text: 'Do you want to continue',
        icon: 'error',
        confirmButtonText: 'Cool'
    });
}
