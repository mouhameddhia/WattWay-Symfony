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
    document.querySelector('.card-container-browse').addEventListener('change', (event) => {
        if (event.target.classList.contains('price-toggle')) {
            const card = event.target.closest('.car-card');
            card.querySelectorAll('.purchase-price, .rent-price, .purchase-label, .rent-label, .buy-btn, .rent-btn')
                .forEach(el => el.classList.toggle('hidden'));
        }
    });

    // Rent button functionality
    document.querySelector('.card-container-browse').addEventListener('click', (event) => {
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
    document.querySelector('.card-container-browse').addEventListener('click', (event) => {
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


  

//REBINDING
function rebindCardFunctions() {
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

    document.querySelector('.card-container-browse').addEventListener('change', (event) => {
        if (event.target.classList.contains('price-toggle')) {
            const card = event.target.closest('.car-card');
            card.querySelectorAll('.purchase-price, .rent-price, .purchase-label, .rent-label, .buy-btn, .rent-btn')
                .forEach(el => el.classList.toggle('hidden'));
        }
    });

    document.querySelector('.card-container-browse').addEventListener('click', (event) => {
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

    document.querySelector('.card-container-browse').addEventListener('click', (event) => {
        if (event.target.classList.contains('proceed-btn')) {
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
    //overlay.addEventListener('click', closeModals);
    //cancelBtnRent.addEventListener('click', closeModals);
    //cancelBtnPurchase.addEventListener('click', closeModals);

    function closeModals() {
        overlay.classList.remove('active');
        floatingContainerRent.classList.add('hidden');
        floatingContainerPurchase.classList.add('hidden');
    }

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
    document.querySelectorAll('.ajax-buy-btn').forEach(button => {
    button.addEventListener('click', async function(e) {
        e.preventDefault();
        
        try {
            const response = await fetch('{{ path("Front") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Accept': 'application/json', 
                    'X-Requested-With': 'XMLHttpRequest' 
                },
                body: new URLSearchParams({
                    car_id: this.dataset.carId
                })
            });

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                throw new Error(`Server returned HTML: ${text.substring(0, 100)}...`);
            }
            
            const data = await response.json();
            
            if (data.error) {
                throw new Error(data.message);
            }
            this.disabled = true;
            const slidingButtons = this.closest('.car-card').querySelector('.sliding-buttons');
            slidingButtons.classList.add('active');
            slidingButtons.querySelector('.proceed-btn').classList.remove('hidden');
            slidingButtons.querySelector('.canceling-btn').classList.remove('hidden');

        } catch (error) {
            console.error('Error:', error);
            alert(error.message);
        }
    });
});
document.querySelectorAll('.ajax-canceling-btn').forEach(button => {
    button.addEventListener('click', async function(e) {
        e.preventDefault();
        const card = this.closest('.car-card');
        const slidingButtons = card.querySelector('.sliding-buttons');
        
        try {
            const response = await fetch('{{ path("Front") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest' // Crucial for detecting AJAX
                },
                body: new URLSearchParams({
                    deleteBill: this.dataset.deleteBill
                })
            });

            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

         
            const data = await response.json();
            
            if (data.success) {
               
                slidingButtons.classList.remove('active');
                card.querySelector('.buy-btn').disabled = false;
                
              
                console.log('Bill deleted successfully');
            } else {
                throw new Error(data.message || 'Operation failed');
            }

        } catch (error) {
            console.error('Error:', error);
            // Show error to user without reloading
            alert('Error: ' + error.message);
        }
    });
});
document.querySelectorAll('.view-specs-btn').forEach(button => {
    button.addEventListener('click', async () => {
      const brand = button.dataset.brand.toLowerCase();
      const model = button.dataset.model.toLowerCase();
      const summary = await getGeminiSpecs(brand, model);
      showSpecsModal(summary);
  });
});
function showSpecsModal(summary) {
    const modal = document.getElementById('specs-modal');
    const body = document.getElementById('specs-modal-body');
  
    body.innerHTML = ''; 
    modal.classList.remove('hidden');
  
    setTimeout(() => {
      modal.classList.add('show');
      typeTextEffect(body, summary);
    }, 10);
  }
  
  function closeSpecsModal() {
    const modal = document.getElementById('specs-modal');
    modal.classList.remove('show');
    setTimeout(() => {
      modal.classList.add('hidden');
    }, 300);
  }
  function typeTextEffect(container, text, speed = 50) {
    const words = text.split(' ');
    let index = 0;
    container.innerHTML = '';
  
    function type() {
      if (index < words.length) {
        const word = words[index];
        container.innerHTML += word + ' ';
        index++;
        setTimeout(type, speed);
      }
    }
  
    type();
  }
  
async function getGeminiSpecs(brand, model) {
    const apiKey = 'AIzaSyC0socyIe4-vp1GmcJUhK_itlEdW_Xnnxk'; // store securely
    const url = `https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=${apiKey}`;
  
    const prompt = `Give a complete and clear specification summary for the car: ${brand} ${model}.***Important***, do not say anything other than the features. I want it in this format,
        Brand: Toyota
        Model: Camry
        Engine: 2.5L 4-cylinder Dynamic Force,
        Horsepower: 203,
        Torque: 184,
        Transmission: 8-speed automatic,
        Dimensions: 
            Length: 489.458 cm
            Width: 183.896 cm
            Height: 144.526 cm
        Fuel Economy: 
            City: 28 mpg,
            Highway: 39 mpg
        Features: 
            Toyota Safety Sense 2.5+,
            8-inch touchscreen,
            Apple CarPlay/Android Auto
        {add some other specifications of your own, don't make this too long}
    `;
  
    const body = {
      contents: [
        {
          role: "user",
          parts: [{ text: prompt }]
        }
      ]
    };
  
    try {
      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(body)
      });
  
      if (!response.ok) {
        throw new Error(`Gemini API error: ${response.status}`);
      }
  
      const data = await response.json();
      const text = data.candidates?.[0]?.content?.parts?.[0]?.text || 'No response';
      return text;
  
    } catch (error) {
      console.error('Gemini fetch error:', error);
      return 'Failed to fetch specifications.';
    }
  }
}

// AJAX for Buy Button
