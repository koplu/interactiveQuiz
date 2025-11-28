document.getElementById("form").addEventListener("submit", function(e) {
    const question = document.getElementById("question_text").value.trim();
    const answers = [...document.querySelectorAll('input[name="answer[]"]')].map(i => i.value.trim());
    const orders = [...document.querySelectorAll('input[name="order[]"]')].map(i => i.value.trim());
    let errorMessages = [];
    let filledCount = 0;
    const usedAnswers = new Set();
    const usedOrders = new Set();

    if (!question) {
        errorMessages.push("Question cannot be empty!")
    }

    for (let i = 0; i < answers.length; i++) {
        const ans = answers[i];
        const ord = orders[i];

        if (ans !== "") filledCount++;

        if ((ans !== "" && ord === "") || (ans === "" && ord !== "")) {
            errorMessages.push("Each answer must have an order and vice-versa.");
        }

        if (ans !== "") {
            if (usedAnswers.has(ans.toLowerCase())) {
                errorMessages.push(`Duplicate answer found: "${ans}"`);
            }
            usedAnswers.add(ans.toLowerCase());
        }

        if (ord !== "") {
            if (usedOrders.has(ord)) {
                errorMessages.push(`Duplicate order number: ${ord}`);
            }
            usedOrders.add(ord);

            if (ord < 1 || ord > 4) {
                errorMessages.push("Order numbers must be between 1 and 4.");
            }
        }
    }

    if (filledCount < 2) {
        errorMessages.push("You must provide at least 2 answers.");
    }

    if (errorMessages.length > 0) {
        e.preventDefault();
        alert(errorMessages.join("\n"));
    }
});
