
let isSpeaking = false;
let currentUtterance;

function speakText(id) {
    let text;

    if (id === 'modal') {
        text = document.getElementById('modalWord').innerText;
    } else {
        text = document.getElementById(id).innerText;
    }

    if (isSpeaking) {
        speechSynthesis.cancel();
        isSpeaking = false;
    } else {
        currentUtterance = new SpeechSynthesisUtterance(text);
        currentUtterance.lang = 'en-US';
        currentUtterance.onend = () => {
            isSpeaking = false;
        };

        speechSynthesis.speak(currentUtterance);
        isSpeaking = true;
    }
}

function Liked(likeId, textId) {
    const icon = document.getElementById(likeId);
    const isLiked = icon.classList.contains('liked');
    const action = isLiked ? 'remove' : 'add';
    icon.classList.toggle('liked');

    fetch('liked.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `text_id=${textId}&action=${action}`
    });
}

function showInfo(textInfo) {
    const info = textInfo;
    document.getElementById('modalTitle').textContent = `${info.title}`;
    document.getElementById('modalWord').textContent = `${info.word}`;
    document.getElementById('modalTranslation').textContent = `${info.translation}`;
    document.getElementById('infoModal').dataset.TextId = info.id;
    document.getElementById('infoModal').classList.add('fade-in');
}

function closeModal() {
    document.getElementById('infoModal').classList.remove('fade-in');
}

function toggleExpand(element) {
    const isExpanded = element.classList.contains('expanded');

    document.querySelectorAll('.vocabulary li').forEach(el => el.classList.remove('expanded'));

    if (!isExpanded) {
        element.classList.add('expanded');
    }
}

document.addEventListener('DOMContentLoaded', () => { });

function deleteText() {
    const TextId = document.getElementById('infoModal').dataset.TextId;
    const word = document.getElementById('modalWord').textContent;

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    });

    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: `You won't be able to revert this action!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'delete.php',
                type: 'POST',
                data: {
                    id: TextId
                },
                success: function (response) {
                    swalWithBootstrapButtons.fire({
                        title: 'Deleted!',
                        text: `You won't be able to revert this action!`,
                        icon: 'success'
                    }).then(() => {
                        closeModal();
                        location.reload();
                    });
                },
                error: function (xhr, status, error) {
                    console.error('Error deleting definition:', status, error);
                    swalWithBootstrapButtons.fire({
                        title: 'Failed!',
                        text: 'Failed to delete the dictionary entry. Please try again.',
                        icon: 'error'
                    });
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire({
                title: 'Cancelled',
                text: 'The dictionary entry is safe :)',
                icon: 'info'
            });
        }
    });
}
