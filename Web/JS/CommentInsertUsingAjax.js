// Variables //

formulaire_valid = true;
$main = $('#main');
news_id = $main.children('h2[data-news-id]').attr('data-news-id');



// Listener ////

setInterval(function()
{
    var last_insert_id = $('fieldset:first').attr('data-comment-id');
    commentStreamUpdate(parseInt(last_insert_id), news_id );
}, 500);

function insertComment(e)
{
    var $textarea = $('#contenu');
    var contenu = $textarea.val().trim();
    var $Form = $("#insertCommentForm");
    var url = $Form.data('ajax-validation');
    var last_insert_id = $('fieldset:first').attr('data-comment-id');

    if(!contenu)
    {
        $('.showError').remove();
        $('#validation_message').remove();

        $textarea.before(
            $('<p></p>')
                .addClass('showError')
                .css('color', 'red')
                .append(
                'Merci de spécifier votre commentaire'
            )
        );

        e.preventDefault();
        return;
    }

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            contenu: contenu,
            last_insert_id: last_insert_id
        },
        dataType: 'json',
        success: function(data){

            $('p .showError').remove();
            $('#validation_message').remove();

            if (!data.success) {

                for (key in data.erreurs) {

                    var $element = $('#'+key);
                    $element.before(
                        $('<p></p>')
                            .addClass('showError')
                            .css('color', 'red')
                            .append(
                            data.erreurs[key]
                        )
                    )
                }
                return;
            }

            $Form.append(
                $('<p></p>')
                    .attr('id', 'validation_message')
                    .css('color', 'green')
                    .append(
                    data.validation_message
                )
            );


            $Form.after(data.html_value);

            $textarea.val('');

            //window.last_insert_id = $('fieldset:first').attr('data-comment-id');
        }
    });
}

$('#submit').click(function(e)
{
    e.preventDefault();
    insertComment(e);
    return false;
});

$main.on('click', 'a[data-ajax-update]', function(e){
    comment_updateClickEventManager(e);
});

$main.on('click', 'a[data-ajax-delete]', function(e){
    comment_deleteClickEventManager(e);
});

// Manager d'événements //

function comment_updateClickEventManager(e) {

    $element = $(e.target);

    var url = $element.attr('data-ajax-update');
    var comment_id = $element.attr('data-id');

    if (formulaire_valid)
    {
        $element = $(e.target);

        var url = $element.attr('data-ajax-update');
        var comment_id = $element.attr('data-id');

        $fieldset = $element.parent().parent();
        $p = $fieldset.children('p');

        var comment_content = $p.text();

        $p.remove();

        $input = $('<input/>')
            .val(comment_content);

        $form = $('<form></form>');

        $form
            .append($input);

        $fieldset
            .append($form);

        e.preventDefault();
    }

    $input.on('focus', function(e)
    {
        $element = $(e.target);
        $fieldset = $element.parent().parent();
        $legend = $fieldset.children('legend');
        $ab = $legend.children('a[data-ajax-update]');
        $ab = $($ab[0]);
        url = $ab.attr('data-ajax-update');
    });

    $input.focus();

    $input.keypress(function(e)
    {
        if(e.which==13)
        {
            event_submitUpdateManager(e, url, $(this).val());
            e.preventDefault();
        }
    });

    $input.on('focusout', function(e)
    {
        event_submitUpdateManager(e, url, $(this).val());
        e.preventDefault();
    });

    e.preventDefault();

}


function comment_deleteClickEventManager(e)
{
    $element = $(e.target);
    var url = $element.data('ajax-delete');

    $.ajax({
        url: url,
        type: 'POST', // Le type de la requête HTTP, ici devenu POST
        data: {}, // On fait passer nos variables, exactement comme en GET, au script more_com.php
        dataType: 'json',
        success: function (data, statut) {

            if(!data.success)
            {
                alert(data.erreurs);
                return;
            }

            $element.parent().parent().remove();

            alert(data.validation_message);
        }
    });

    e.preventDefault();
}

function event_submitUpdateManager(e, url, content)
{
    var $Form = $("#insertCommentForm");
    var id = $Form.data('newsid');
    var member_id = $Form.data('memberid');

    $element = $(e.target);
    $fieldset = $element.parent().parent();
    $legend = $fieldset.children('legend');

    content = content.trim();

    if(!content)
    {
        $('.showError').remove();

        $element.after(
            $('<p></p>')
                .append(
                'Merci de spécifier un commentaire'
            )
                .css('color', 'red')
                .addClass('showError')
        );

        formulaire_valid = false;

        return;
    }

    $.ajax({
        url: url,
        type: 'POST', // Le type de la requête HTTP, ici devenu POST
        data:
        {
            contenu: content
        }, // On fait passer nos variables, exactement comme en GET, au script more_com.php
        dataType: 'json',
        success: function (data, statut) {

            $('.showError').remove();

            if(!data.success)
            {
                $element.after(
                    $('<p></p>')
                        .append(
                            data.erreurs
                    )
                        .css('color', 'red')
                        .addClass('showError')
                );

                formulaire_valid = false;

               // $element.focus();

                return;
            }

            $fieldset.children('form').remove();
            $fieldset.append(
                $('<p></p>')
                    .append(
                    data.comment.contenu
                )
            );

            if($legend.children('em').length == 0)
            {
                $a = $legend.children('a');
                $a = $($a[0]);

                $a.before(
                    $('<em></em>')
                        .append(
                        ' (Modifié le '+ data.comment.dateModif + ') '
                    )
                );

                return;
            }

            $legend.children('em').text( ' (Modifié le '+ data.comment.dateModif + ') ');

            formulaire_valid = true;
        }



    });
}

function commentStreamUpdate(last_insert_id, news_id )
{
    var $Form = $("#insertCommentForm");

    var old_id = last_insert_id;
    var current_last_insert_id = parseInt($('fieldset:first').attr('data-comment-id'));


   $.ajax({
        url: '/comment-stream-updating.php',
        type: 'POST', // Le type de la requête HTTP, ici devenu POST
        data:
        {
            last_insert_id: last_insert_id,
            news_id: news_id
        }, // On fait passer nos variables, exactement comme en GET, au script more_com.php
        dataType: 'json',
        success: function (data, statut) {

            if(!data.success)
            {
                return;
            }

           // window.last_insert_id = $('fieldset:first').attr('data-comment-id');

            $Form.after(data.html_value);

            //$Form.after(data.html_value);
        }
    });
}

/*function buildComments(comments)
{
    for(var i=0; i<comments.length; i++)
    {
        buildComment(comments[i]);
    }
}

function buildComment(comment)
{
    var $Form = $("#insertCommentForm");
    var $Main = $('#main');
    var $Fieldset = $Main.children('fieldset:first');

    if($Form.length==0)
    {
        if($Fieldset.length==0)
        {
            $Main.append(
                $('<fieldset></fieldset>')
                    .attr('data-comment-id', comment.id)
                    .append(
                    $('<legend></legend>')
                        .append(
                        'Posté par '
                    )
                        .append(
                        $('<strong></strong>')
                            .append(
                            comment.Membre.membre_login
                        )
                    )
                        .append(
                        ' le ' +
                        comment.dateAjout +
                        ' '
                    )
                )
                    .append(
                    $('<p></p>')
                        .append(
                        comment.contenu
                    )
                )
            );
        }
        else
        {
            if($('fieldset[data-comment-id="'+comment.id+'"]').length==0)
            {
                $Fieldset.before(
                    $('<fieldset></fieldset>')
                        .attr('data-comment-id', comment.id)
                        .append(
                        $('<legend></legend>')
                            .append(
                            'Posté par '
                        )
                            .append(
                            $('<strong></strong>')
                                .append(
                                comment.Membre.membre_login
                            )
                        )
                            .append(
                            ' le ' +
                            comment.dateAjout +
                            ' '
                        )
                    )
                        .append(
                        $('<p></p>')
                            .append(
                            comment.contenu
                        )
                    )
                );
            }
        }
    }
    else
    {
        if($('fieldset[data-comment-id="'+comment.id+'"]').length==0)
        {
            $Form.after(
                $('<fieldset></fieldset>')
                    .attr('data-comment-id', comment.id)
                    .append(
                    $('<legend></legend>')
                        .append(
                        'Posté par '
                    )
                        .append(
                        $('<strong></strong>')
                            .append(
                            comment.Membre.membre_login
                        )
                    )
                        .append(
                        ' le ' +
                        comment.dateAjout +
                        ' '
                    )
                )
                    .append(
                    $('<p></p>')
                        .append(
                        comment.contenu
                    )
                )
            );

            if(comment.modif_authorisation)
            {
                $('fieldset[data-comment-id="'+comment.id+'"]')
                    .children('legend')
                    .append(
                    $('<a></a>')
                        .attr('href', comment.update_url)
                        .attr('data-ajax-update', comment.ajax_update_url)
                        .append(
                        'Modifier'
                    )
                )
                    .append(
                    ' | '
                )
                    .append(
                    $('<a></a>')
                        .attr('href', comment.delete_url)
                        .attr('data-ajax-delete', comment.ajax_delete_url)
                        .append(
                        'Supprimer'
                    )
                );
            }
        }
    }
}
*/

