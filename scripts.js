document.addEventListener('DOMContentLoaded', function () {
    const salaryInput = document.getElementById('salary');
    const commitmentsInput = document.getElementById('commitments');
    const savingsInput = document.getElementById('savings');
    const savingsAmount = document.getElementById('savingsAmount');
    const surplus = document.getElementById('surplus');

    function calculate() {
        const salary = parseFloat(salaryInput.value) || 0;
        const commitments = parseFloat(commitmentsInput.value) || 0;
        const savingsPercentage = parseFloat(savingsInput.value) || 0;

        const savings = (salary * savingsPercentage) / 100;
        const remainingSurplus = salary - commitments - savings;

        savingsAmount.textContent = savings.toFixed(2);
        surplus.textContent = remainingSurplus.toFixed(2);
    }

    salaryInput.addEventListener('input', calculate);
    commitmentsInput.addEventListener('input', calculate);
    savingsInput.addEventListener('input', calculate);
});