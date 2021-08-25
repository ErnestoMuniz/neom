// Função que requisita e trata os dados das onus de uma pon
function getPON(id, pon, srch){
    document.getElementById('pon-index').innerHTML = `<b> — ONU List — ${pon}</b>`;
    document.getElementById('btn-refresh').setAttribute('onclick', `getPON(${id}, '${pon}', 1500)`);
    // cria icone de carregando
    document.getElementById('pon').innerHTML = "<div class='text-center'><i class=\"las la-spinner\" style='animation:spin 4s linear infinite;'></i> <span>Carregando</span></div>";
    // faz a requisição
    let slotPon = pon;
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
                let desc = onu[5];
                if (desc.length >= 21) {
                    desc = desc + '...';
                }
                let sinal = onu[4].split('/')[0];
                if (sinal == "-") {
                    sinal = "-40.00";
                }
                if (sinal != "-40.00"){
                    status = "<span class='badge badge-success'>Active</span>";
                } else {
                    status = "<span class='badge badge-danger'>Inactive</span>";
                }
                if (sinal > -25) {
                    sinal = `<span class='badge badge-success'>${sinal}</span>`;
                } else if ((sinal >= -28) && (sinal <= -25)) {
                    sinal = `<span class='badge badge-warning'>${sinal}</span>`;
                } else {
                    sinal = `<span class='badge badge-danger'>${sinal}</span>`;
                }
                let serial = "HWTC-" + onu[1][8] + onu[1][9] + onu[1][10] + onu[1][11] + onu[1][12] + onu[1][13] + onu[1][14] + onu[1][15];
                if (onu[0] == srch){
                    pon.innerHTML += `<tr class="border-2 table-active font-bold">
                                        <td>${pos}</td><td>${status}</td>
                                        <td>${desc}</td>
                                        <td>${sinal}</td>
                                        <td>${serial}</td>
                                        <td>
                                            <button type="submit" class="btn btn-transparent p-0" onclick="modalReboot('${slotPon}', '${pos}')">
                                                <i class="las la-power-off text-danger"></i>
                                            </button>
                                            <button type="submit" class="btn btn-transparent p-0" onclick="modalRemove('${slotPon}', '${pos}')">
                                                <i class="las la-times-circle text-danger"></i>
                                            </button>
                                        </td>
                                    </tr>`;
                } else {
                    pon.innerHTML += `<tr class="border-b">
                                        <td>${pos}</td>
                                        <td>${status}</td>
                                        <td>${desc}</td>
                                        <td>${sinal}</td>
                                        <td>${serial}</td>
                                        <td>
                                            <button type="submit" class="btn btn-transparent p-0" onclick="modalReboot('${slotPon}', '${pos}')">
                                                <i class="las la-power-off text-danger"></i>
                                            </button>
                                            <button type="submit" class="btn btn-transparent p-0" onclick="modalRemove('${slotPon}', '${pos}')">
                                                <i class="las la-times-circle text-danger"></i>
                                            </button>
                                        </td>
                                    </tr>`;
                }
            }
        })
        .catch(function (error){
            new Noty({
                type: 'error',
                text: '<b>ERROR ON PON VERIFICATION</b>'
            }).show();
            console.log(error);
        });
}

function getONU(id){
    let param = document.getElementById('search').value;
    document.getElementById('btn-search').innerHTML = "<i class=\"las la-spinner\" style='animation:spin 4s linear infinite;'></i>";
    axios.get(`/get/huawei/onu?id=${id}&onu=${param}`).then(function (response){
        let pon = response.data.split('/');
        getPON(id, `${pon[1]}/${pon[2]}`, pon[3].replace('\n', ''));
        document.getElementById('btn-search').innerHTML = "<i class=\"las la-search\"></i>";
        pon.innerHTML = "";
    })
    .catch(function (error){
        document.getElementById('btn-search').innerHTML = "<i class=\"las la-search\"></i>";
        new Noty({
            type: 'error',
            text: '<b>ONU NOT FOUND</b>'
        }).show();
    });
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

function modalRemove(pon, pos) {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const olt = urlParams.get('olt');
    Swal.fire({
        title: 'Remove ONU',
        html: `<label class="mr-1">ONU PON:</label><input type="text" value="${pon}" disabled>
            <br><label class="mr-1">ONU Position:</label><input type="text" value="${pos}" disabled>`,
        showCancelButton: true,
        cancelButtonText: 'Cancel',
        confirmButtonText: 'Remove',
        preConfirm: () => {
            return {
                onu_pon: pon,
                onu_pos: pos,
                olt_id: olt
            }
        }
    }).then((result) => {
        axios.get(`/get/huawei/remove?id=${result.value.olt_id}&pon=${result.value.onu_pon}&pos=${result.value.onu_pos}`).then((response) => {
            Swal.fire({
                title: 'ONU Removed',
                icon: 'success',
                showConfirmButton: false,
                timer: 2000
            });
            getPON(result.value.olt_id, pon, 1500);
        });
    });
}

function modalReboot(pon, pos) {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const olt = urlParams.get('olt');
    Swal.fire({
        title: 'Reboot  ONU',
        html: `<label class="mr-1">ONU PON:</label><input type="text" value="${pon}" disabled>
            <br><label class="mr-1">ONU Position:</label><input type="text" value="${pos}" disabled>`,
        showCancelButton: true,
        cancelButtonText: 'Cancel',
        confirmButtonText: 'Reboot',
        preConfirm: () => {
            return {
                onu_pon: pon,
                onu_pos: pos,
                olt_id: olt
            }
        }
    }).then((result) => {
        axios.get(`/get/huawei/reboot?id=${result.value.olt_id}&pon=${result.value.onu_pon}&pos=${result.value.onu_pos}`).then((response) => {
            Swal.fire({
                title: 'ONU Rebooted',
                icon: 'success',
                showConfirmButton: false,
                timer: 2000
            });
            getPON(result.value.olt_id, pon, 1500);
        });
    });
}
