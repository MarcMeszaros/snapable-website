/* we don't have native FormData, so use the jquery.form library */
function sendForm(input, successCallback, errorCallback, beforeSubmit) {
    console.log('sendForm polyfill');
    // validate the form
    if ($(input.form).data('validate') === 'parsley' && !$(input.form).parsley('validate')) {
        return false;
    }

    var params = {};
    if (typeof successCallback === 'function') {
        params.success = function(data, status, xhr) {
            xhr.onload = successCallback;
            xhr.response = data;
            xhr.onload();
        };
    } 
    if (typeof errorCallback === 'function') {
        params.error = function(xhr, status, error) {
            xhr.onerror = errorCallback;
            xhr.onerror();
        }
    }
    if (typeof beforeSubmit === 'function') {
        params.beforeSubmit = beforeSubmit;
    }

    // execute the ajax
    $(input.form).ajaxSubmit(params);
    return false; // Prevent page from submitting.
}