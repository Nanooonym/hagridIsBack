// When ville selected ...
document.getElementById("sortie_lieu").addEventListener("change", function(){

    console.log("test");
    let e = document.getElementById("sortie_lieu");
    let idLieu = e.options[e.selectedIndex].value;

    createCookie("IdLieu", idLieu, "1");

    let ajax = new XMLHttpRequest();
    let method = "GET";
    let url = "getLieuInfos.php";
    let asynchronous = true;

    ajax.open(method, url, asynchronous);
    ajax.send();

    ajax.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            let data = JSON.parse(this.responseText);
            let html = '';
            for (let a = 0; a < data.length; a++) {
                let rue = data[a].rue;
                let latitude = data[a].latitude;
                let longitude = data[a].longitude;
            }

            document.getElementById("rue").innerHTML += rue;
            document.getElementById("latitude").innerHTML += latitude;
            document.getElementById("longitude").innerHTML += longitude;
        }

    }
});