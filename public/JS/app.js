
let $ville = $('#sortie_ville');
// When ville selected ...
$ville.change(function() {
    console.log("Dans la fonction Change");

    // ... retrieve the corresponding form.
    let $form = $(this).closest('form');
    // Simulate form data, but only include the selected espece value.
    let data = {};
    data[$ville.attr('name')] = $ville.val();

    // Submit data via AJAX to the form's action path.
    $.ajax({
        url : $form.attr('action'),
        type: $form.attr('method'),
        data : data,
        success: function(html) {
            // Replace current race field ...
            $('#sortie_lieu').replaceWith(
                // ... with the returned one from the AJAX response.
                $(html).find('#sortie_lieu')
            );
            // race field now displays the appropriate positions.
        }
    });
});