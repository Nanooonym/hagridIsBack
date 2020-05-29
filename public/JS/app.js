let $ville = $('#sortie_ville');
let $lieu = $('#sortie_lieu');
let $campus = $('#sortie_campus');
// When ville selected ...
/*$ville.change(function() {*/
document.getElementById("sortie_ville").addEventListener("change", function () {

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

    }
);