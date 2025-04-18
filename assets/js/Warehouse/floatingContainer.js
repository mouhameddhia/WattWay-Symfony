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
});
