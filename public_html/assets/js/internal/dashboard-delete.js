function deleteCallback(e) {
    var msg = new Object();
    if (this.status == 204) {
        msg.type = 'success';
        msg.title = 'Resource Deleted';
        msg.text = 'The resource was successfully deleted.';
    } else if (this.status == 404) {
        msg.type = 'error';
        msg.title = 'Resource Not Deleted';
        msg.text = 'Cannot find the resource. Make sure it exists before trying to delete it.';
    } else if (this.status == 400) {
        msg.type = 'info';
        msg.title = 'Resource Not Deleted';
        msg.text = 'The resource was not deleted because of a bad request.';
    } else {
        msg.type = 'error';
        msg.title = 'Resource Not Deleted';
        msg.text = 'There was an error while trying to delete the resource.';
    }

    $.pnotify({
        type: msg.type,
        title: msg.title,
        text: msg.text
    });
}

function sendForm(input, callback) {
    var formData = new FormData(input.form);

    var xhr = new XMLHttpRequest();
    xhr.open(input.form.method, input.form.action, true); // use the 'action' in the form
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest'); // server checks this to consider it AJAX 
    if (typeof callback === 'function') {
        xhr.onload = callback;
    }

    xhr.send(formData);

    return false; // Prevent page from submitting.
}
