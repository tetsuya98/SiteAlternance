$(document).ready(function() {
    console.log('ok');
    $('.select2').select2();

    $('#offre_file_file, #candidature_file_file').each(function (){
        let input = $(this);
        let parent = input.closest('.vich-file');
        let del = parent.find('#offre_file_delete');
        if (del) {
            $('#suppr-file').click(function (e){
                e.preventDefault();
                console.log('check');
                if (del.prop('checked')){
                    del.prop( "checked", false );
                    $('#suppr-file').text("Supprimer le fichier");
                } else {
                    del.prop( "checked", true );
                    $('#suppr-file').text("Annuler la suppression");
                }
            });
            $('#add-file').click(function (e){
                e.preventDefault();
                console.log('add');
                input.click();
            });
        }
        input.change(function (e){
            $('#select-file').text(e.target.value.substring(e.target.value.lastIndexOf('\\') + 1));
        });
    });
});