document.addEventListener('DOMContentLoaded', () => {
    const overlay = document.getElementById('overlay');
    const floatingContainerRent = document.getElementById('floatingContainerRent');
    const floatingContainerPurchase = document.getElementById('floatingContainer');
    const cancelBtnRent = document.getElementById('cancelBtn');
    const cancelBtnPurchase = document.getElementById('cancelBtn2');
    const confirmBtnRent = document.getElementById('confirmBtn');
    const startDateInput = document.getElementById('startDate');
    const daysCell = document.getElementById('days');
    const priceCell = document.getElementById('rentPrice');
    const modalPriceRent = document.getElementById('modalPriceRent');
    const modalCarImageRent = document.getElementById('modalCarImage');
    const modalCarImagePurchase = document.getElementById('modalCarImage2');
    const modalCarPrice = document.getElementById('modalCarPrice');
    const modalTax = document.getElementById('modalTax');
    const modalTotalPrice = document.getElementById('modalTotalPrice');
    const modalRentCarId = document.getElementById('modalRentCarId');
    const modalCarId = document.getElementById('modalCarId');

    // Toggle price visibility
    document.querySelector('.card-container').addEventListener('change', (event) => {
        if (event.target.classList.contains('price-toggle')) {
            const card = event.target.closest('.car-card');
            card.querySelectorAll('.purchase-price, .rent-price, .purchase-label, .rent-label, .buy-btn, .rent-btn')
                .forEach(el => el.classList.toggle('hidden'));
        }
    });

    // Rent button functionality
    document.querySelector('.card-container').addEventListener('click', (event) => {
        if (event.target.classList.contains('rent-btn')) {
            const card = event.target.closest('.car-card');
            const carImage = card.querySelector('.car-image').src;
            const carId = card.querySelector('input[name="idCar"]').value;
            const carPrice = parseFloat(card.querySelector('.rent-price').textContent);

            modalCarImageRent.src = carImage;
            modalRentCarId.value = carId;

            floatingContainerRent.classList.remove('hidden');
            overlay.classList.add('active');

            setupDatePicker(carPrice);
        }
    });
    // Purchase button functionality
    document.querySelector('.card-container').addEventListener('click', (event) => {
        if (event.target.classList.contains('proceed-btn')) {
            console.log('Purchase button clicked');
            const card = event.target.closest('.car-card');
            const carImage = card.querySelector('.car-image').src;
            const carId = card.querySelector('input[name="idCar"]').value;
            const carPrice = parseFloat(card.querySelector('.purchase-price').textContent);

            const taxRate = 0.08;
            const tax = carPrice * taxRate;
            const dealerFees = 500;
            const deliveryFees = 750;
            const registerFees = 250;
            const total = carPrice + tax + dealerFees + deliveryFees + registerFees;

            modalCarImagePurchase.src = carImage;
            modalCarPrice.textContent = carPrice;
            modalTax.textContent = tax.toFixed(2);
            modalTotalPrice.textContent = total.toFixed(2) + ' DT';
            modalCarId.value = carId;

            floatingContainerPurchase.classList.remove('hidden');
            overlay.classList.add('active');
        }
    });

    // Close modals
    overlay.addEventListener('click', closeModals);
    cancelBtnRent.addEventListener('click', closeModals);
    cancelBtnPurchase.addEventListener('click', closeModals);

    function closeModals() {
        overlay.classList.remove('active');
        floatingContainerRent.classList.add('hidden');
        floatingContainerPurchase.classList.add('hidden');
    }

    // Setup date picker for rent functionality
    function setupDatePicker(carPrice) {
        const today = new Date();
        const maxDate = new Date();
        maxDate.setDate(today.getDate() + 60);

        flatpickr(startDateInput, {
            mode: "range",
            minDate: today,
            maxDate: maxDate,
            dateFormat: "Y-m-d",
            onChange: (selectedDates) => {
                const [start, end] = selectedDates;

                if (start && end && end >= start) {
                    const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
                    daysCell.textContent = days;
                    const totalPrice = carPrice * days;
                    priceCell.textContent = totalPrice.toFixed(2) + ' DT';
                    modalPriceRent.value = totalPrice;
                    if(days > 0) confirmBtnRent.disabled = false;
                    
                } else {
                    daysCell.textContent = 0;
                    priceCell.textContent = '0 DT';
                    confirmBtnRent.disabled = true;
                }
                
            }
        });
    }
      
});