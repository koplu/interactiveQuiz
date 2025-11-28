document.getElementById("form").addEventListener("submit", function(e) {
    let errors = [];

    const questionText = document.getElementById('question_text').value.trim();
    if (!questionText) {
        errors.push("Question text cannot be empty.");
    }

    const leftInputs = Array.from(document.querySelectorAll('input[name="left[]"]'));
    const rightInputs = Array.from(document.querySelectorAll('input[name="right[]"]'));

    let filledPairs = 0;

    for (let i = 0; i < leftInputs.length; i++) {
        const left = leftInputs[i].value.trim();
        const right = rightInputs[i].value.trim();

        if ((left && !right) || (!left && right)) {
            errors.push(`Row ${i + 1}: Both left and right must be filled if one is filled.`);
        }

        if (left && right) {
            filledPairs++;
        }
    }

    if (filledPairs < 2) {
        errors.push("You must fill at least 2 matching pairs.");
    }

    if (errors.length > 0) {
        e.preventDefault();
        alert(errors.join("\n"));
    }
});
