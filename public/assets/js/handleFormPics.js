$('#add-images').click(function(){
    //je récupère le numéro des futurs champs que je vais créer
    const index = $('#trick_images div.form-group').length;
    //je récupère le prototype des entrées
    const tmpl = $('#trick_images').data('prototype').replace(/__name__/g, index);
    //j'injecte ce code au sein de la div
    $('#trick_images').append(tmpl);

    handleDeleteButtons();
});

$('#add_videos').click(function(){
    //je récupère le numéro des futurs champs que je vais créer
    const index = $('#trick_videos div.form-group').length;
    //je récupère le prototype des entrées
    const tmpl = $('#trick_videos').data('prototype').replace(/__name__/g, index);
    //j'injecte ce code au sein de la div
    $('#trick_videos').append(tmpl);

    handleDeleteButtons();
});

function handleDeleteButtons() {
    $('button[data-action="delete"]').click(function(){
        const target = this.dataset.target;
        //console.log(target);
        $(target).remove();
    });
}

handleDeleteButtons();
