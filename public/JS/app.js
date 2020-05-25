

let $ville = $('#sortie_ville');
let $lieu = $('#sortie_lieu');
let $campus = $('#sortie_campus');
// When ville selected ...
/*$ville.change(function() {*/
    document.getElementById("sortie_ville").addEventListener("change", function(){

        // ... retrieve the corresponding form.
        let $form = $(this).closest('form');
        console.log($form);
        // Simulate form data, but only include the selected espece value.
        let data = {};
        data[$ville.attr('name')] = $ville.val();

        // Submit data via AJAX to the form's action path.
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            data: data,
            success: function (html) {
                // Replace current race field ...
                $('#sortie_lieu').replaceWith(
                    // ... with the returned one from the AJAX response.
                    $(html).find('#sortie_lieu')
                );
                // race field now displays the appropriate positions.
            }
        });

/*        // Return Id of selected Ville
        let e = document.getElementById("sortie_ville");
        let idVille = e.options[e.selectedIndex].value;

        // Function to create the cookie to send to PHP file id of selected Ville
        createCookie("IdVille", idVille, "1");

        // Request to Ajax (to get json ville with selected id)
        let ajax = new XMLHttpRequest();
        let method = "GET";
        let url = "getVilleInfos.php";
        let asynchronous = true;

        ajax.open(method, url, asynchronous);
        ajax.send();

        ajax.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                let data = JSON.parse(this.responseText);
                let html = '';
                for (let a = 0; a < data.length; a++) {
                    let codePostal = data[a].code_postal;
                    html += codePostal;
                }

                document.getElementById("codePostal").innerHTML = html;
                $(document.body).remove('onChangeLieu').append('<script id="onChangeLieu" src="../JS/onChangeLieu.js"></script>')*!/
            }

        }*/
    }

);

function createCookie(name, value, days) {
    var expires;

    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    }
    else {
        expires = "";
    }

    document.cookie = escape(name) + "=" +
        escape(value) + expires + "; path=/";

}