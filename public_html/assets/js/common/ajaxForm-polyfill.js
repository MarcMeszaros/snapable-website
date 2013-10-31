/* we don't have native FormData, so use the jquery.form library */
function sendForm(input, successCallback, errorCallback) {
    console.log('sendForm polyfill');
    var params = {};
    if (typeof successCallback === 'function') {
        params.success = successCallback;
    } 
    if (typeof errorCallback === 'function') {
        params.error = errorCallback;
    }

    // execute the ajax
    $(input.form).ajaxForm(params);
}