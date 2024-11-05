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


function closeModal() {
    document.getElementById('infoModal').classList.remove('fade-in');
}

function Liked(likeId, sentence_id) {
    const element = document.getElementById(likeId);
    const isLiked = element.classList.contains("liked");
    const action = isLiked ? 'remove' : 'add';

    fetch('liked.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `sentence_id=${sentence_id}&action=${action}`
    })
        .then(response => response.text())
        .then(result => {
            if (result === 'Success') {
                if (action === 'add') {
                    element.classList.add('liked');
                    element.style.color = 'red';
                } else {
                    element.classList.remove('liked');
                    element.style.color = '#ddd';
                }
            } else {
                console.error('Failed to update like status.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function deleteSentences() {
    const modal = document.getElementById('infoModal');
    const sentenceId = modal.getAttribute('data-sentences-id');
    const listElement = document.getElementById(document.getElementById('infoModal').dataset.listId)

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
                    id: sentenceId
                },
                success: function (response) {
                    swalWithBootstrapButtons.fire({
                        title: 'Deleted!',
                        text: `The dictionary entry has been deleted.`,
                        icon: 'success'
                    }).then(() => {
                        closeModal();
                        listElement.remove()
                        const listItems = document.querySelectorAll('ul > div.list > li');
                        const itemCount = listItems.length;
                        if (itemCount == 0) {
                            location.reload()
                        }
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

function showInfo(data) {
    document.getElementById("modalWord").innerText = data.sentence;
    document.getElementById("modalTranslation").innerText = data.translation;
    document.getElementById('infoModal').dataset.listId = data.list_id;

    const modal = document.getElementById("infoModal");
    modal.setAttribute('data-sentences-id', data.id);
    modal.classList.add("fade-in");
    modal.style.display = "block";

    fetch('getWordName.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            'id': data.id
        })
    })
        .then(response => response.json())
        .then(data => {
            let title = document.getElementById('modalTitle');
            title.textContent = data.word
        })
        .catch(error => console.error('Error:', error));
}

function toggleExpand(element) {
    const isExpanded = element.classList.contains('expanded');

    document.querySelectorAll('.vocabulary li').forEach(el => el.classList.remove('expanded'));

    if (!isExpanded) {
        element.classList.add('expanded');
    }
}


