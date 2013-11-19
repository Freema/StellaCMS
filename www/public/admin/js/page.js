jQuery.extend({
    nette: {
        updateSnippet: function (id, html) {
            $("#" + id).html(html);
        },
        success: function (payload) {
            if (payload.redirect) {
                window.location.href = payload.redirect;
                return;
            }
            if (payload.snippets) {
                for (var i in payload.snippets) {
                    jQuery.nette.updateSnippet(i, payload.snippets[i]);
                }
            }
        }
    }
});

jQuery.ajaxSetup({
	success: jQuery.nette.success,
	dataType: "json"
});

$("a.ajax").on("click", function (event) {
    event.preventDefault();
    $.get(this.href);
});

$('form.ajax').on('submit', function (event){
    event.preventDefault();
    $.post(this.action, $(this).serialize());
});

$('.b-select').selectpicker();

$(".alert").alert();

/**
 * @param {Object} $object
 * @param {String} snippet
 * @returns {Bool}
 */
function alertBox($object, snippet)
{
    var $box = $object.find("#snippet-form-message-box");
    $box.hide().html(snippet).fadeIn();
    $(".alert").alert();
}