function getPON(id, pon){
    document.getElementById('pon').innerHTML = "<div class='text-center'><i class='gg-loadbar inline-block mx-auto align-middle mr-1'></i><span>Carregando</span></div>";
    axios.get(`/pon?id=${id}&pon=${pon}`)
        .then(function (response){
            //console.log(response.data);
            parser = new DOMParser();
            xml = parser.parseFromString(response.data, "text/xml");
            xml = xml.getElementsByTagName("instance");
            pon = document.getElementById('pon');
            pon.innerHTML = "<table class='table-auto mx-auto rounded-l'><thead><tr class='bg-gray-300 uppercase text-sm'><th class='py-1 px-2'>Num</th><th class='py-1 px-2'>Status</th><th class='py-1 px-2'>Descrição</th><th class='py-1 px-2'>Sinal</th><th>Serial</th></tr></thead><tbody></tbody></table>";
            pon = pon.children[0].children[1];
            let flip = false;
            for (let ont in xml){
                let pos = xml[ont].children[1].innerHTML.replace("1/1/1/", "").split('/')[1];
                let status = xml[ont].children[4].innerHTML;
                let desc = xml[ont].children[7].innerHTML;
                let sinal = xml[ont].children[5].innerHTML;
                let serial = xml[ont].children[2].innerHTML;
                if (flip){
                    pon.innerHTML += `<tr class="bg-gray-100"><td class='py-1 px-2'>${pos}</td><td class='py-1 px-2'>${status}</td><td class='py-1 px-2'>${desc}<td class='py-1 px-2'>${sinal}</td><td>${serial}</td></tr>`;
                } else {
                    pon.innerHTML += `<tr class="bg-white"><td class='py-1 px-2'>${pos}</td><td class='py-1 px-2'>${status}</td><td class='py-1 px-2'>${desc}<td class='py-1 px-2'>${sinal}</td><td>${serial}</td></tr>`;
                }
                flip = !flip;
            }
        })
        .catch(function (error){
            console.log(error);
        });
}
