document.getElementById("schedulingForm").addEventListener("submit", function(event) {
    const startDate = new Date(document.getElementById("startDate").value);
    const endDate = new Date(document.getElementById("endDate").value);

    if (endDate < startDate) {
        alert("End Date cannot be earlier than Start Date!");
        event.preventDefault(); // Prevent form submission
    }
});
