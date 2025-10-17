document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.querySelector('#modal-overlay');
    const body = document.body;
    const dismissButton = document.querySelector('#modal-dismiss');
    const callerButton = document.querySelector('#modal-caller');

    function showModal() {
        overlay.classList.remove('d-none');
        overlay.classList.add('d-flex');
        body.classList.add('modal-open');
    }

    function closeModal() {
        overlay.classList.add('d-none');
        overlay.classList.remove('d-flex');
        body.classList.remove('modal-open');
    }

    dismissButton.addEventListener('click', function (){
        closeModal();
    });
    if (callerButton) {
        callerButton.addEventListener('click', function() {
            showModal();
        })
    }
});


