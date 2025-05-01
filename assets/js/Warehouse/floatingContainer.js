document.addEventListener("DOMContentLoaded", function () {

    const openModal = document.getElementById("openModal");
    const closeModal = document.getElementById("closeModal");
    const floatingContainer = document.getElementById("floatingContainer");
    const overlay = document.getElementById("overlay");
    const body = document.body;
    function openModalFunc() {
        floatingContainer.classList.add("active");
        overlay.classList.add("active");
        body.classList.add("modal-open");
    }
    
    
    function closeModalFunc() {
        floatingContainer.classList.remove("active");
        overlay.classList.remove("active");
        body.classList.remove("modal-open");    
    }

    openModal.addEventListener("click", openModalFunc);
    closeModal.addEventListener("click", closeModalFunc);
    
    // Close modal when clicking outside
    overlay.addEventListener("click", closeModalFunc);
    // Zoom in functionality

    document.querySelectorAll('.zoomIn').forEach(img => {
        img.addEventListener('click', function () {
            const overlay = document.getElementById('imageZoomOverlay');
            const zoomedImage = document.getElementById('zoomedImage');
    
            if (overlay && zoomedImage) {
                zoomedImage.src = this.src;
                overlay.style.display = 'flex';
    
                zoomedImage.style.opacity = '0';
                zoomedImage.style.transform = 'scale(0.8)';
    
                setTimeout(() => {
                    zoomedImage.style.opacity = '1';
                    zoomedImage.style.transform = 'scale(1)';
                }, 10);
            }
        });
    });
    
    const imageOverlay = document.getElementById('imageZoomOverlay');
    if (imageOverlay) {
        imageOverlay.addEventListener('click', function () {
            const zoomedImage = document.getElementById('zoomedImage');
            if (zoomedImage) {
                zoomedImage.style.opacity = '0';
                zoomedImage.style.transform = 'scale(0.8)';
            }
    
            setTimeout(() => {
                this.style.display = 'none';
                if (zoomedImage) {
                    zoomedImage.src = '';
                }
            }, 300); 
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
});
