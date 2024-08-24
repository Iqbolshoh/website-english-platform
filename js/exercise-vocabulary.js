document.getElementById('testForm').addEventListener('submit', function (event) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);
    const answers = formData.getAll('answers[]');
    const totalQuestions = parseInt(formData.get('total_questions'), 10);
    const wordsData = JSON.parse(formData.get('words_data'));

    let correctCount = 0;
    let allCorrect = true;

    wordsData.forEach((word, index) => {
        const correctWord = word.word;
        const selectedAnswer = answers[index] || '';

        const questionDiv = form.querySelector(`.question:nth-child(${index + 1})`);
        const labels = questionDiv.querySelectorAll('label');

        labels.forEach(label => {
            const radio = label.querySelector('input[type="radio"]');
            if (radio.value === correctWord) {
                label.classList.add('correct');
                if (radio.checked) {
                    correctCount++;
                }
            } else if (radio.checked && radio.value !== correctWord) {
                label.classList.add('error');
                allCorrect = false;
            }
        });
    });

    Swal.fire({
        title: "Test Results",
        text: `You got ${correctCount} out of ${totalQuestions} correct!`,
        icon: "success",
        confirmButtonText: 'OK'
    });

    form.querySelectorAll('input[type="radio"]').forEach(input => {
        input.disabled = true;
    });
});

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('refresh-test').addEventListener('click', () => {
        window.location.reload();
    });
});