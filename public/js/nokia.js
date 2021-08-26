// Função que requisita e trata os dados das onus de uma pon
function getPON(id, pon, srch){
    document.getElementById('pon-index').innerHTML = `<b> — ONU List — ${pon}</b>`;
    document.getElementById('btn-refresh').setAttribute('onclick', `getPON(${id}, '${pon}', 1500)`);
    // cria icone de carregando
    document.getElementById('pon').innerHTML = "<div class='text-center'><i class=\"las la-spinner\" style='animation:spin 4s linear infinite;'></i><span> Carregando</span></div>";
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
                if (xml[ont] >= 0 && xml[ont] <= 128) {
                    break;
                }
                let pos_full = xml[ont].children[1].innerHTML;
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
                    pon.innerHTML += `<tr class="table-active font-bold">
                                        <td>${pos}</td><td>${status}</td>
                                        <td>${desc}</td>
                                        <td>${sinal}</td>
                                        <td>${serial}</td>
                                        <td>
                                            <button type="submit" class="btn btn-transparent p-0" onclick="modalReboot('${pos_full}')">
                                                <i class="las la-power-off text-danger"></i>
                                            </button>
                                            <button type="submit" class="btn btn-transparent p-0" onclick="modalRemove('${pos_full}')">
                                                <i class="las la-times-circle text-danger"></i>
                                            </button>
                                        </td>
                                    </tr>`;
                } else {
                    pon.innerHTML += `<tr class="">
                                        <td>${pos}</td>
                                        <td>${status}</td>
                                        <td>${desc}</td>
                                        <td>${sinal}</td>
                                        <td>${serial}</td>
                                        <td>
                                            <button type="submit" class="btn btn-transparent p-0" onclick="modalReboot('${pos_full}')">
                                                <i class="las la-power-off text-danger"></i>
                                            </button>
                                            <button type="submit" class="btn btn-transparent p-0" onclick="modalRemove('${pos_full}')">
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
    let param = document.getElementById('search').value.toUpperCase();
    document.getElementById('btn-search').innerHTML = "<i class=\"las la-spinner\" style='animation:spin 4s linear infinite;'></i>";
    axios.get(`/get/nokia/onu?id=${id}&onu=${param}`).then(function (response){
        let pon = response.data.split('/');
        getPON(id, `${pon[2]}/${pon[3]}`, response.data);
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
    axios.get(`/get/nokia/pending?id=${id}`).then(function (response){
        let parser = new DOMParser();
        let xml = parser.parseFromString(response.data, "text/xml");
        xml = xml.getElementsByTagName("instance");
        let pon = document.getElementById('request');
        pon.innerHTML = "";
        document.getElementById('refresh-pending').style = "";
        for (let ont in xml){
            let pos = xml[ont].children[1].innerHTML;
            let serial = xml[ont].children[2].innerHTML.split('');
            serial = serial[0] + serial[1] + serial[2] + serial[3] + ':' + serial[4] + serial[5] + serial[6] + serial[7] + serial[8] + serial[9] + serial[10] + serial[11];
            pon.innerHTML += `<tr class="border-b"><td>${pos}</td><td>${serial}</td><td class="text-right"><button type="submit" class="btn btn-transparent p-0" onclick="modalAdd('${pos}', '${serial}')"><i class="las la-plus-circle text-success"></i></button></td></tr>`;
        }
    })
}

function modalRemove(pos) {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const olt = urlParams.get('olt');
    Swal.fire({
        title: 'Remove ONU',
        html: `<label class="mr-1">ONU Position:</label><input type="text" value="${pos}" disabled>`,
        showCancelButton: true,
        cancelButtonText: 'Cancel',
        confirmButtonText: 'Remove',
        preConfirm: () => {
            return {
                onu_pos: pos,
                olt_id: olt
            }
        }
    }).then((result) => {
        axios.get(`/get/nokia/remove?id=${result.value.olt_id}&pos=${result.value.onu_pos}`).then((response) => {
            Swal.fire({
                title: 'ONU Removed',
                icon: 'success',
                showConfirmButton: false,
                timer: 2000
            });
            let pon = result.value.onu_pos.split('/')
            getPON(result.value.olt_id, `${pon[2]}/${pon[3]}`, 1500);
        });
    });
}

function modalAdd(pos, serial) {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const olt = urlParams.get('olt');
    Swal.fire({
        title: 'Add ONU',
        html: `<label class="mr-1">ONU Position:<div class="input-group"><div class="input-group-prepend"><div class="input-group-text bg-gray-400 text-white">${pos}</div></div></label><input type="number" class="form-control" id="onu-pos"></div><br>
               <label class="mr-1">ONU Serial:</label><input type="text" value="${serial}" class="form-control" disabled><br>
               <label class="mr-1">ONU Description:</label><input type="text" class="form-control" id="onu-desc"><br>
               <label class="mr-1">ONU VLAN:</label><input type="number" class="form-control" id="onu-vlan"><br>
               <label class="mr-1">ONU Type:</label><select class="form-control" id="onu-type"><option value="router">Router</option><option value="bridge">Bridge</option></select>`,
        showCancelButton: true,
        cancelButtonText: 'Cancel',
        confirmButtonText: 'Add',
        preConfirm: () => {
            return {
                onu_pos: `${pos}/${document.getElementById('onu-pos').value}`,
                olt_id: olt,
                onu_serial: serial,
                onu_description: document.getElementById('onu-desc').value,
                onu_vlan: document.getElementById('onu-vlan').value,
                onu_type: document.getElementById('onu-type').value
            }
        }
    }).then((result) => {
        axios.get(`/get/nokia/add?id=${result.value.olt_id}&pos=${result.value.onu_pos}&serial=${result.value.onu_serial}&vlan=${result.value.onu_vlan}&description=${result.value.onu_description}&type=${result.value.onu_type}`).then((response) => {
            Swal.fire({
                title: 'ONU Added',
                icon: 'success',
                showConfirmButton: false,
                timer: 2000
            });
            let pon = result.value.onu_pos.split('/')
            getPON(result.value.olt_id, `${pon[2]}/${pon[3]}`, 1500);
        });
    });
}

function modalReboot(pos) {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const olt = urlParams.get('olt');
    Swal.fire({
        title: 'Reboot  ONU',
        html: `<label class="mr-1">ONU Position:</label><input type="text" value="${pos}" disabled>`,
        showCancelButton: true,
        cancelButtonText: 'Cancel',
        confirmButtonText: 'Reboot',
        preConfirm: () => {
            return {
                onu_pos: pos,
                olt_id: olt
            }
        }
    }).then((result) => {
        axios.get(`/get/nokia/reboot?id=${result.value.olt_id}&pos=${result.value.onu_pos}`).then((response) => {
            Swal.fire({
                title: 'ONU Rebooted',
                icon: 'success',
                showConfirmButton: false,
                timer: 2000
            });
            let pon = result.value.onu_pos.split('/')
            getPON(result.value.olt_id, `${pon[2]}/${pon[3]}`, 1500);
        });
    });
}
