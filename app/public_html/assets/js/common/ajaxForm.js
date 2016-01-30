// use the native FormData
function sendForm(input, successCallback, errorCallback, beforeSubmit) {
    // validate the form
    if ($(input.form).data('validate') === 'parsley' && !$(input.form).parsley('validate')) {
        return false;
    }

    if (typeof beforeSubmit === 'function') {
        beforeSubmit();
    }

    // if the feature isn't undefined
    var formData = new FormData(input.form);

    // create the AJAX request
    var xhr = new XMLHttpRequest();
    xhr.open(input.form.method, input.form.action, true); // use the 'action' in the form
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest'); // server checks this to consider it AJAX
    xhr.onreadystatechange = function() {
      // everything is good, the response is received
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status >= 200 && xhr.status < 300) {
          if (typeof successCallback === 'function') {
              successCallback();
          }
        } else {
          if (typeof errorCallback === 'function') {
              errorCallback();
          }
        }
      }
    }

    // set the error callback
    if (typeof errorCallback === 'function') {
        xhr.onerror = errorCallback;
    }

    // send the AJAX call
    xhr.send(formData);
    return false; // Prevent page from submitting.
}
