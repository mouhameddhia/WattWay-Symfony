// Wait for the DOM to fully load before executing any scripts
document.addEventListener('DOMContentLoaded', function () {



    // Scroll smoothly to the highlighted row (e.g., a recently updated or selected order)
    const highlighted = document.querySelector('.highlighted-row');
    if (highlighted) {
        setTimeout(() => {
            highlighted.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 400);
    }



// Initialize the Bootstrap modal used for editing orders
const editOrderModal = new bootstrap.Modal(document.getElementById('editOrderModal'));

// Attach click listeners to all "edit" buttons on the page
document.querySelectorAll('.edit-order-btn').forEach(button => {
    button.addEventListener('click', function (e) {
        e.preventDefault();

        const orderId = this.getAttribute('data-order-id');
        const supplier = this.getAttribute('data-supplier');
        const date = this.getAttribute('data-date');
        const status = this.getAttribute('data-status');
        const total = this.getAttribute('data-total');
        const address = this.getAttribute('data-address');

        document.getElementById('editOrderId').textContent = orderId;
        document.getElementById('editSupplier').value = supplier;
        document.getElementById('editDate').value = date;
        document.getElementById('editStatus').value = status;
        document.getElementById('editTotal').value = total;
        document.getElementById('editAddress').value = address;

        const editOrderModalElement = document.getElementById('editOrderModal');
        const modal = new bootstrap.Modal(editOrderModalElement);
        modal.show();

        fetch(`/order/items/${orderId}`)
            .then(response => response.json())
            .then(data => {
                if (data.items) {
                    const orderItemsTableBody = document.getElementById('editOrderItemsList');
                    orderItemsTableBody.innerHTML = '';

                    data.items.forEach(item => {
                        const row = document.createElement('tr');
                        row.setAttribute('data-item-id', item.id);

                        row.innerHTML = `
                            <td>${item.name}</td>
                            <td><input type="number" class="form-control item-quantity" value="${item.quantity}" min="1" step="1" pattern="\\d*" /></td>
                            <td>$${item.price}</td>
                            <td>
                                <button class="btn btn-danger delete-item-btn">Delete</button>
                                <button class="btn btn-success save-quantity-btn">Save Quantity</button>
                            </td>
                        `;

                        orderItemsTableBody.appendChild(row);
                    });

                    // Integer input restriction
                    document.querySelectorAll('.item-quantity').forEach(input => {
                        input.addEventListener('input', function () {
                            this.value = this.value.replace(/[^\d]/g, '');
                            if (parseInt(this.value) < 1 || this.value === '') {
                                this.value = 1;
                            }
                        });
                    });

                    // Save quantity
                    document.querySelectorAll('.save-quantity-btn').forEach(button => {
                        button.addEventListener('click', function () {
                            const row = this.closest('tr');
                            const itemId = row.getAttribute('data-item-id');
                            const newQuantity = row.querySelector('.item-quantity').value;

                            fetch(`/order/item/update/${itemId}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({ quantity: newQuantity }),
                            })
                            .then(response => {
                                if (!response.ok) {
                                    return response.json().then(data => {
                                        throw new Error(data.message || 'Unknown error');
                                    });
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    console.log('Quantity updated:', data.newQuantity);

                                    // Recalculate total
                                    let totalAmount = 0;
                                    document.querySelectorAll('#editOrderItemsList tr').forEach(row => {
                                        const quantity = parseInt(row.querySelector('.item-quantity').value);
                                        const priceText = row.querySelector('td:nth-child(3)').textContent.replace('$', '');
                                        const price = parseFloat(priceText);
                                        if (!isNaN(quantity) && !isNaN(price)) {
                                            totalAmount += quantity * price;
                                        }
                                    });
                                    document.getElementById('editTotal').value = totalAmount.toFixed(2);
                                } else {
                                    throw new Error(data.message || 'Update failed');
                                }
                            })
                            .catch(error => {
                                console.error('Error updating quantity:', error.message);
                            });
                        });
                    });

                    // Delete item
                    document.querySelectorAll('.delete-item-btn').forEach(button => {
                        button.addEventListener('click', function () {
                            const row = this.closest('tr');
                            const itemId = row.getAttribute('data-item-id');

                            fetch(`/order/item/delete/${itemId}`, {
                                method: 'DELETE'
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    row.remove();
                                } else {
                                    alert(data.message || 'Failed to delete item.');
                                }
                            })
                            .catch(error => {
                                console.error('Error deleting item:', error);
                                alert('An error occurred while deleting the item.');
                            });
                        });
                    });
                } else {
                    alert('Error fetching order items');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while fetching order items.');
            });
    });
});


    // Handle save button click inside the modal
    document.getElementById('saveOrderChanges').addEventListener('click', function () {
        const orderId = document.getElementById('editOrderId').textContent;

        // Collect updated data from the modal input fields
        const formData = {
            supplier: document.getElementById('editSupplier').value.trim(),
            date: document.getElementById('editDate').value,
            status: document.getElementById('editStatus').value,
            total: parseFloat(document.getElementById('editTotal').value),
            address: document.getElementById('editAddress').value.trim()
        };

        // Validate required fields manually (in case HTML5 validation is skipped)
        if (!formData.supplier || !formData.date || !formData.status || isNaN(formData.total) || !formData.address) {
            alert("Please fill in all required fields correctly.");
            return;
        }

        // Send an AJAX request to update the order
        fetch(`/order/update/${orderId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(formData)
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Order updated successfully!');
                    bootstrap.Modal.getInstance(document.getElementById('editOrderModal')).hide();
                    location.reload(); // Reload the page to reflect changes
                } else {
                    alert('Error updating order: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the order.');
            });
    });


    // Chart.js config (unchanged)
    const ctx = document.getElementById('ordersChart').getContext('2d');
    let ordersChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [
                {
                    label: 'Admin Orders',
                    data: window.adminOrdersData,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    tension: 0.3,
                    fill: true,
                    borderWidth: 2,
                    pointBackgroundColor: '#007bff',
                    pointRadius: 4,
                    pointHoverRadius: 6
                },
                {
                    label: 'Client Orders',
                    data: window.clientOrdersData,
                    borderColor: '#17a2b8',
                    backgroundColor: 'rgba(23, 162, 184, 0.1)',
                    tension: 0.3,
                    fill: true,
                    borderWidth: 2,
                    pointBackgroundColor: '#17a2b8',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        boxWidth: 12,
                        padding: 20,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: '#fff',
                    titleColor: '#333',
                    bodyColor: '#666',
                    borderColor: 'rgba(0,0,0,0.1)',
                    borderWidth: 1,
                    padding: 12,
                    boxPadding: 6
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false,
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        precision: 0
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'nearest'
            }
        }
    });


    // Store the original data and alternate finance data
    const expensesData = window.expensesData;
    const revenuesData = window.revenuesData;
    const originalData = {
        admin: window.adminOrdersData,
        client: window.clientOrdersData
    };

    let currentView = 'orders'; // Track which chart view is currently active

    // Add event listeners to the left/right arrow buttons for toggling between chart views
    document.getElementById('arrowLeft').addEventListener('click', toggleChartView);
    document.getElementById('arrowRight').addEventListener('click', toggleChartView);

    // Function to toggle between 'orders' and 'finance' data in the chart
    function toggleChartView() {
        if (currentView === 'orders') {
            // Switch to finance view (Expenses & Revenues)
            ordersChart.data.datasets[0].data = expensesData;
            ordersChart.data.datasets[0].label = 'Expenses';
            ordersChart.data.datasets[0].borderColor = '#dc3545';
            ordersChart.data.datasets[0].backgroundColor = 'rgba(220, 53, 69, 0.1)';
            ordersChart.data.datasets[0].pointBackgroundColor = '#dc3545';

            ordersChart.data.datasets[1].data = revenuesData;
            ordersChart.data.datasets[1].label = 'Revenues';
            ordersChart.data.datasets[1].borderColor = '#28a745';
            ordersChart.data.datasets[1].backgroundColor = 'rgba(40, 167, 69, 0.1)';
            ordersChart.data.datasets[1].pointBackgroundColor = '#28a745';

            document.getElementById('chartTitle').textContent = 'Expenses & Revenues';
        } else {
            // Switch back to order statistics view
            ordersChart.data.datasets[0].data = originalData.admin;
            ordersChart.data.datasets[0].label = 'Admin Orders';
            ordersChart.data.datasets[0].borderColor = '#007bff';
            ordersChart.data.datasets[0].backgroundColor = 'rgba(0, 123, 255, 0.1)';
            ordersChart.data.datasets[0].pointBackgroundColor = '#007bff';

            ordersChart.data.datasets[1].data = originalData.client;
            ordersChart.data.datasets[1].label = 'Client Orders';
            ordersChart.data.datasets[1].borderColor = '#17a2b8';
            ordersChart.data.datasets[1].backgroundColor = 'rgba(23, 162, 184, 0.1)';
            ordersChart.data.datasets[1].pointBackgroundColor = '#17a2b8';

            document.getElementById('chartTitle').textContent = 'Order Statistics';
        }

        // Toggle the view type and update the chart
        currentView = currentView === 'orders' ? 'finance' : 'orders';
        ordersChart.update();
    }

    // Time range and year selectors for filtering chart data
    const timeRangeSelect = document.getElementById('statsTimeRange');
    const yearSelect = document.getElementById('statsYear');

    // Attach change listeners to reload the page with selected filters
    if (timeRangeSelect && yearSelect) {
        timeRangeSelect.addEventListener('change', () => {
            updateChartData(timeRangeSelect.value, yearSelect.value);
        });

        yearSelect.addEventListener('change', () => {
            updateChartData(timeRangeSelect.value, yearSelect.value);
        });
    }

    // Redirects the page with updated query parameters for filtering
    function updateChartData(timeRange, year) {
        const url = new URL(window.location.href);
        url.searchParams.set('timeRange', timeRange);
        url.searchParams.set('year', year);
        window.location.href = url.toString(); // Triggers page reload with filters
    }

    
});


// Dropdown toggle
function toggleDropdownFilter() {
    const dropdown = document.getElementById("dropdownFilterMenu");
    dropdown.classList.toggle("show");
}


function toggleDropdownSort() {
        const dropdown = document.getElementById("dropdownSortMenu");
        dropdown.classList.toggle("show");
    }

window.onclick = function(event) {
    if (!event.target.matches('.dropdown-button')) {
      const dropdownFilter = document.getElementById("dropdownFilterMenu");
      if (dropdownFilter && dropdownFilter.classList.contains("show")) {
        dropdownFilter.classList.remove("show");
      }
      const dropdownSort = document.getElementById("dropdownSortMenu");
      if (dropdownSort && dropdownSort.classList.contains("show")) {
        dropdownSort.classList.remove("show");
      }
    }
}
document.addEventListener("DOMContentLoaded", function () {
      const input = document.getElementById("searchInput");
      input.addEventListener("keydown", function (e) {
        if (e.key === "Enter") {
          e.preventDefault();
          triggerAjax(input.value);
        }
          });
});
function triggerAjax(query) {
      console.log("AJAX triggered with:", query);
}

document.getElementById("searchIconButton").addEventListener("click", function() {
    triggerAjax(document.getElementById("searchInput").value);
});

document.getElementById('monthFilter').addEventListener('change', function() {
    const selectedMonth = this.value;
    const currentURL = new URL(window.location.href); // Get the current URL

    // Update the month query parameter
    currentURL.searchParams.set('month', selectedMonth);

    // Reload the page with the updated query parameter
    window.location.href = currentURL.toString();
});

