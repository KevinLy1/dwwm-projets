document.addEventListener("DOMContentLoaded", function() {
    let vehicleSelection = document.querySelector('#order_idVehicle');
    let totalPriceSpan = document.querySelector('#totalPrice');

    function calculateTotalDays(startDate, endDate) {
        const oneDay = 24 * 60 * 60 * 1000;
        const start = new Date(startDate);
        const end = new Date(endDate);
        const totalDays = Math.round(Math.abs((start - end) / oneDay));
        if(totalDays == 0) totalDays = 1;
        return totalDays;
    }

    function updateTotalPrice() {
        const startDate = document.getElementById("order_dateTimeDeparture_date_year").value + '-' +
                          document.getElementById("order_dateTimeDeparture_date_month").value + '-' +
                          document.getElementById("order_dateTimeDeparture_date_day").value;
        const endDate = document.getElementById("order_dateTimeEnd_date_year").value + '-' +
                        document.getElementById("order_dateTimeEnd_date_month").value + '-' +
                        document.getElementById("order_dateTimeEnd_date_day").value;

        const totalDays = calculateTotalDays(startDate, endDate);
        const days = document.getElementById("totalDays");
        days.innerText = "Nombre de jour(s) : " + totalDays;

        let selectedVehicles = vehicleSelection.querySelectorAll('input:checked');
        let totalDailyPrice = 0;

        selectedVehicles.forEach(vehicle => {
            let price = parseFloat(vehicle.getAttribute('data-price'));
            totalDailyPrice += price;
        });

        const daily = document.getElementById("totalDailyPrice");
        daily.innerText = "Prix journalier : " + totalDailyPrice + " €";

        const totalPrice = totalDailyPrice * totalDays;
        totalPriceSpan.innerText = "Prix total : " + totalPrice + " €";
    }

    let vehicleCheckboxes = vehicleSelection.querySelectorAll('input[type="checkbox"]');
    vehicleCheckboxes.forEach(checkbox => {
        checkbox.addEventListener("change", updateTotalPrice);
    });

    document.getElementById("order_dateTimeDeparture_date_year").addEventListener("change", updateTotalPrice);
    document.getElementById("order_dateTimeDeparture_date_month").addEventListener("change", updateTotalPrice);
    document.getElementById("order_dateTimeDeparture_date_day").addEventListener("change", updateTotalPrice);
    document.getElementById("order_dateTimeEnd_date_year").addEventListener("change", updateTotalPrice);
    document.getElementById("order_dateTimeEnd_date_month").addEventListener("change", updateTotalPrice);
    document.getElementById("order_dateTimeEnd_date_day").addEventListener("change", updateTotalPrice);

    updateTotalPrice();
});
