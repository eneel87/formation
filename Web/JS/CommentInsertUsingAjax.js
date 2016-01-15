$("#submit").click(function(){

    var url = $("#insertCommentForm").data('ajax-validation');
    var id = $("#insertCommentForm").data('newsid');
    var member_id = $("#insertCommentForm").data('memberid');
    var contenu = $("#contenu").val();

    $.ajax({
        url : url,
        type : 'POST', // Le type de la requête HTTP, ici devenu POST
        data : 'contenu='+contenu+'&id='+id+'&member_id='+member_id, // On fait passer nos variables, exactement comme en GET, au script more_com.php
        dataType : 'json',
        success : function(data, statut){

            if(data['comment_id']!=null)
            {
                $("#insertCommentForm").after($('<fieldset>', { 'class' : data['comment_id']}));

                var $element = $('fieldset[class="'+data['comment_id']+'"]');

                $element.append($('<legend>', { 'class' : data['comment_id']}));

                var $input = $('legend[class="'+data['comment_id']+'"]').append('Posté par <strong>' + data['comment_member_login'] + '</strong> le ' + data['comment_date_ajout']);

                if (data['comment_date_ajout']!=data['comment_date_modif'])
                {
                     $input = $input.append(' (<em>Modifié le '+ data['comment_date_modif'] +'</em>)');
                }

                $input.append(' <a href="'+data['comment_update_url']+'">Modifier</a> | <a href="'+data['comment_delete_url']+'">Supprimer</a>');

                $element.append('<p>' +data['comment_content']+ '</p>');
            }
            else
            {
                $('p .showError').remove();

                for(key in data)
                {
                    var element = document.getElementById(key);
                    $(element).before("<p style='color:red' class='showError'>"+data[key]+"</p>");
                }
            }

        }
    });

    return false;

});
