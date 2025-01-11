document.addEventListener('DOMContentLoaded', function() {
    const dateConfig = {
        dateFormat: "Y-m-d",
        minDate: "today",
        locale: "it"
    };
    
    const checkIn = flatpickr("#check_in", {
        ...dateConfig,
        onChange: function(selectedDates) {
            checkOut.set('minDate', selectedDates[0]);
        }
    });
    
    const checkOut = flatpickr("#check_out", {
        ...dateConfig,
        minDate: "today"
    });
});