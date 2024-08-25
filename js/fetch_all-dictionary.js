let isSpeaking = false;
let currentUtterance;
let iconElement;

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

function Liked(likeId, wordId) {
    const icon = document.getElementById(likeId);
    const isLiked = icon.classList.contains('liked');
    const action = isLiked ? 'remove' : 'add';
    icon.classList.toggle('liked');

    fetch('liked.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `word_id=${wordId}&action=${action}`
    });
}

function showInfo(wordInfo) {
    const info = wordInfo;
    document.getElementById('modalWord').textContent = `${info.word}`;
    document.getElementById('modalTranslation').textContent = `${info.translation}`;
    document.getElementById('modalDefinition').textContent = `${info.definition}`;
    document.getElementById('infoModal').dataset.wordId = info.id;
    document.getElementById('infoModal').classList.add('fade-in');
}

function closeModal() {
    document.getElementById('infoModal').classList.remove('fade-in');
}


function deleteDefinition() {
    const wordId = document.getElementById('infoModal').dataset.wordId;
    const word = document.getElementById('modalWord').textContent.replace('Word: ', '');

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
                    id: wordId
                },
                success: function (response) {
                    swalWithBootstrapButtons.fire({
                        title: 'Deleted!',
                        text: `The dictionary entry "${word}" has been deleted.`,
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

function toggleExpand(element) {
    const isExpanded = element.classList.contains('expanded');

    document.querySelectorAll('.vocabulary li').forEach(el => el.classList.remove('expanded'));

    if (!isExpanded) {
        element.classList.add('expanded');
    }
}

document.addEventListener('DOMContentLoaded', () => { });

function sentences() {
    const wordId = document.getElementById('infoModal').dataset.wordId;

    window.location.href = '../sentences/sentences.php?word_id=' + wordId;
}
