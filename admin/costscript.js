document.addEventListener("DOMContentLoaded", function() {
    const busRouteSelect = document.getElementById("busRoute");
    const totalCostInput = document.getElementById("totalCost");
    const paymentInput = document.getElementById("payment");
    const paymentResult = document.getElementById("paymentResult");
    const pendingResult = document.getElementById("pendingResult");

    // Event listener for bus route selection
    busRouteSelect.addEventListener("change", function() {
        const selectedCost = busRouteSelect.value;
        totalCostInput.value = selectedCost;
        updatePending();
    });

    // Event listener for payment input
    paymentInput.addEventListener("input", updatePending);

    function updatePending() {
        const totalCost = parseFloat(totalCostInput.value) || 0;
        const payment = parseFloat(paymentInput.value) || 0;
        const pendingAmount = totalCost - payment;

        paymentResult.textContent = `Amount Paid: ₹${payment.toFixed(2)}`;
        pendingResult.textContent = `Pending Amount: ₹ ${pendingAmount.toFixed(2)}`;
    }
});
