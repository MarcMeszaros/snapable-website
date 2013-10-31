// use the native FormData
function sendForm(input, successCallback, errorCallback) {
    console.log('sendForm');
    // if the feature isn't undefined
    var formData = new FormData(input.form);

    // create the AJAX request
    var xhr = new XMLHttpRequest();
    xhr.open(input.form.method, input.form.action, true); // use the 'action' in the form
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest'); // server checks this to consider it AJAX 
    if (typeof successCallback === 'function') {
        xhr.onload = successCallback;
    }
    if (typeof errorCallback === 'function') {
        xhr.onerror = errorCallback;
    }

    // send the AJAX call
    xhr.send(formData);
    return false; // Prevent page from submitting.
}