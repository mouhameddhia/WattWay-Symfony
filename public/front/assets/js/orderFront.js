class ShopCart {
    constructor() {
        this.cart = [];
        this.currentItem = null;
        this.visibleItems = 10;
        this.initElements();
        this.bindEvents();
        this.initLoadMore();
    }

    initElements() {
        // Quantity dialog elements
        this.quantityDialog = document.getElementById('quantityDialog');
        this.quantityInput = document.getElementById('quantityInput');
        this.minusBtn = document.querySelector('.quantity-btn.minus');
        this.plusBtn = document.querySelector('.quantity-btn.plus');
        this.confirmQuantityBtn = document.getElementById('confirmQuantityBtn');
        this.cancelQuantityBtn = document.getElementById('cancelQuantityBtn');
        
        // Cart dialog elements
        this.cartDialog = document.getElementById('cartDialog');
        this.viewCartBtn = document.getElementById('viewCartBtn');
        this.closeCartBtn = document.getElementById('closeCartBtn');
        this.checkoutBtn = document.getElementById('checkoutBtn');
        this.cartItemsContainer = document.getElementById('cartItemsContainer');
        this.cartTotalElement = document.getElementById('cartTotal');
        this.cartCountElement = document.querySelector('.cart-count');
        
        // Product elements
        this.addToCartButtons = document.querySelectorAll('.add-to-cart-button');
        this.items = document.querySelectorAll('.product-card');
        
        // Filter elements
        this.searchInput = document.getElementById('searchBar');
        this.categoryFilter = document.querySelector('.category-filter');
        this.loadMoreBtn = document.getElementById('loadMoreBtn');

         // AI Chat elements
         this.aiChatDialog = document.getElementById('aiChatDialog');
         this.aiChatMessages = document.getElementById('aiChatMessages');
         this.aiChatInput = document.getElementById('aiChatInput');
         this.aiChatSend = document.getElementById('aiChatSend');
         this.quickAccessButton = document.getElementById('quickAccessButton');
         this.suggestedItemsBtn = document.getElementById('suggestedItemsBtn');
    }

    bindEvents() {
        // Quantity controls
        this.minusBtn.addEventListener('click', () => this.adjustQuantity(-1));
        this.plusBtn.addEventListener('click', () => this.adjustQuantity(1));
        
        // Add to cart buttons with notification
        this.addToCartButtons.forEach(button => {
            button.addEventListener('click', () => {
                this.handleAddToCart(button);
            });
        });
        
        // Dialog buttons
        this.confirmQuantityBtn.addEventListener('click', () => this.confirmQuantity());
        this.cancelQuantityBtn.addEventListener('click', () => this.closeQuantityDialog());
        this.viewCartBtn.addEventListener('click', () => this.openCartDialog());
        this.closeCartBtn.addEventListener('click', () => this.closeCartDialog());
        this.checkoutBtn.addEventListener('click', () => {
            this.handleCheckout();
        });
    
        // Search and filter
        this.searchInput.addEventListener('input', () => this.handleSearch());
        this.categoryFilter.addEventListener('change', () => this.handleCategoryFilter());
        
        // Close dialogs when clicking outside
        [this.quantityDialog, this.cartDialog].forEach(dialog => {
            dialog.addEventListener('click', (e) => {
                if (e.target === dialog) this.closeDialog(dialog);
            });
        });

        // AI Chat events
        this.suggestedItemsBtn.addEventListener('click', () => this.openAIChatDialog());
        this.aiChatSend.addEventListener('click', () => this.handleAIChatSend());
        this.aiChatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.handleAIChatSend();
        });

         // New chat UI events
         this.closeChatBtn = document.getElementById('closeChatBtn');
         this.closeChatBtn.addEventListener('click', () => this.closeAIChatDialog());
         
         // Suggestion chips
         document.querySelectorAll('.suggestion-chip').forEach(chip => {
             chip.addEventListener('click', () => {
                 this.aiChatInput.value = chip.getAttribute('data-prompt');
                 this.handleAIChatSend();
             });
         });
    }

    initLoadMore() {
        if (this.items.length > 10) {
            Array.from(this.items).slice(this.visibleItems).forEach(item => {
                item.style.display = 'none';
            });

            this.loadMoreBtn.addEventListener('click', () => {
                const nextItems = Array.from(this.items).slice(this.visibleItems, this.visibleItems + 10);
                nextItems.forEach(item => item.style.display = 'block');
                this.visibleItems += nextItems.length;
                if (this.visibleItems >= this.items.length) {
                    this.loadMoreBtn.style.display = 'none';
                }
            });
        } else {
            this.loadMoreBtn.style.display = 'none';
        }
    }

    adjustQuantity(change) {
        const currentValue = parseInt(this.quantityInput.value);
        const newValue = currentValue + change;
        
        if (newValue >= 1) {
            this.quantityInput.value = newValue;
        }
    }

    async handleAddToCart(button) {
        const itemId = button.getAttribute('data-item-id');
        const availableQuantity = await this.getAvailableQuantityByItemId(itemId);
    
        this.currentItem = {
            id: itemId,
            name: button.closest('.product-card').querySelector('.product-title').textContent,
            price: parseFloat(button.closest('.product-card').querySelector('.product-price').textContent.replace('$', '')),
            quantity: 1, // ← Temporary, will be overwritten in confirmQuantity()
            maxQuantity: availableQuantity
        };
    
        this.quantityInput.value = 1;
        this.quantityInput.max = availableQuantity;
        this.quantityDialog.style.display = 'flex';
    }
    
    
    async getAvailableQuantityByItemId(itemId) {
        try {
            const response = await fetch(`/api/item/${itemId}/quantity`);
            if (!response.ok) throw new Error('Network response was not ok');
            const data = await response.json();
            return data.availableQuantity;
        } catch (error) {
            console.error('Error fetching available quantity:', error);
            return 0; // fallback
        }
    }
    
    async confirmQuantity() {
        if (!this.currentItem) return;
    
        const requestedQuantity = parseInt(this.quantityInput.value);
    
        // Log the available quantity to see if it's correct
        console.log('Available Quantity:', this.currentItem.maxQuantity);
    
        // Check if the requested quantity is greater than the available quantity
        if (requestedQuantity > this.currentItem.maxQuantity) {
            alert(`Only ${this.currentItem.maxQuantity} items available in stock.`);
            return;
        }
    
        const existingItemIndex = this.cart.findIndex(item => item.id === this.currentItem.id);
    
        if (existingItemIndex >= 0) {
            // Combine quantities if the item already exists in the cart
            const combinedQuantity = this.cart[existingItemIndex].quantity + requestedQuantity;
    
            if (combinedQuantity > this.currentItem.maxQuantity) {
                alert(`Cannot add more than ${this.currentItem.maxQuantity} items of this product.`);
                return;
            }
    
            this.cart[existingItemIndex].quantity = combinedQuantity;
        } else {
            // Ensure the quantity doesn't exceed available stock when adding a new item
            const safeQuantity = Math.min(requestedQuantity, this.currentItem.maxQuantity);
            this.cart.push({ ...this.currentItem, quantity: safeQuantity });
        }
    
        this.updateCartDisplay();
        this.closeQuantityDialog();
    }
    
    
    

    updateCartDisplay() {
        const totalItems = this.cart.reduce((total, item) => total + item.quantity, 0);
        this.cartCountElement.textContent = totalItems;
    
        // Sync cart with backend
        this.syncCartWithServer();
    
        // Display the cart items
        if (this.cart.length === 0) {
            this.cartItemsContainer.innerHTML = '<p class="empty-cart-message">Your cart is empty</p>';
        } else {
            this.cartItemsContainer.innerHTML = '';
            this.cart.forEach(item => {
                const cartItemElement = document.createElement('div');
                cartItemElement.className = 'cart-item';
                cartItemElement.innerHTML = `
                    <div class="cart-item-info">
                        <div class="cart-item-name">${item.name}</div>
                        <div class="cart-item-price">$${item.price.toFixed(2)} each</div>
                    </div>
                    <div class="cart-item-quantity">
                        <button class="decrease-quantity" data-item-id="${item.id}">-</button>
                        <span>${item.quantity}</span>
                        <button class="increase-quantity" data-item-id="${item.id}">+</button>
                        <button class="remove-item" data-item-id="${item.id}">×</button>
                    </div>
                `;
                this.cartItemsContainer.appendChild(cartItemElement);
            });
            this.bindCartItemEvents();
        }
    
        const total = this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        this.cartTotalElement.textContent = `$${total.toFixed(2)}`;
    }
    
    async syncCartWithServer() {
        // Sync the cart state with the server for persistence
        try {
            const response = await fetch('/update-cart', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ cart: this.cart })
            });
            if (!response.ok) {
                throw new Error('Failed to update cart');
            }
        } catch (error) {
            console.error('Error syncing cart:', error);
        }
    }
    

    async bindCartItemEvents() {
        document.querySelectorAll('.decrease-quantity').forEach(button => {
            button.addEventListener('click', async () => {
                const itemId = button.getAttribute('data-item-id');
                const itemIndex = this.cart.findIndex(item => item.id === itemId);
    
                if (this.cart[itemIndex].quantity > 1) {
                    this.cart[itemIndex].quantity--;
                } else {
                    this.cart.splice(itemIndex, 1);
                }
    
                await this.syncCartWithServer();
                this.updateCartDisplay();
            });
        });
    
        document.querySelectorAll('.increase-quantity').forEach(button => {
            button.addEventListener('click', async () => {
                const itemId = button.getAttribute('data-item-id');
                const itemIndex = this.cart.findIndex(item => item.id === itemId);
    
                const availableQuantity = await this.getAvailableQuantityByItemId(itemId);
                const totalRequested = this.cart[itemIndex].quantity + 1;
    
                if (totalRequested > availableQuantity) {
                    alert(`Only ${availableQuantity} items available in stock.`);
                    return;
                }
    
                this.cart[itemIndex].quantity++;
                await this.syncCartWithServer();
                this.updateCartDisplay();
            });
        });
    
        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', async () => {
                const itemId = button.getAttribute('data-item-id');
                this.cart = this.cart.filter(item => item.id !== itemId);
                await this.syncCartWithServer();
                this.updateCartDisplay();
            });
        });
    }
    

    async handleCheckout() {
        if (this.cart.length === 0) {
            alert('Your cart is empty!');
            return;
        }

        try {
            const response = await fetch('/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    items: this.cart,
                    totalAmount: this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0)
                })
            });

            if (response.ok) {
                const result = await response.json();
                alert('Order created successfully!');
                this.cart = [];
                this.updateCartDisplay();
                this.closeCartDialog();
                // Optionally redirect to order confirmation page
                // window.location.href = `/order/${result.orderId}`;
            } else {
                throw new Error('Failed to create order');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('There was an error processing your order. Please try again.');
        }
    }

    handleSearch() {
        const searchTerm = this.searchInput.value.toLowerCase();
        this.items.forEach(item => {
            const title = item.querySelector('.product-title').textContent.toLowerCase();
            const category = item.querySelector('.product-category').textContent.toLowerCase();
            item.style.display = (title.includes(searchTerm) || category.includes(searchTerm)) ? 'block' : 'none';
        });
    }

    handleCategoryFilter() {
        const selectedCategory = this.categoryFilter.value.toLowerCase();
        this.items.forEach(item => {
            const itemCategory = item.getAttribute('data-category');
            item.style.display = (selectedCategory === '' || itemCategory.includes(selectedCategory)) ? 'block' : 'none';
        });
    }

    openCartDialog() {
        this.updateCartDisplay();
        this.cartDialog.style.display = 'flex';
    }

    closeCartDialog() {
        this.cartDialog.style.display = 'none';
    }

    closeQuantityDialog() {
        this.quantityDialog.style.display = 'none';
        this.currentItem = null;
    }

    closeDialog(dialog) {
        dialog.style.display = 'none';
        if (dialog === this.quantityDialog) {
            this.currentItem = null;
        }
    }
    openAIChatDialog() {
        this.aiChatMessages.innerHTML = `
        <div class="ai-welcome-message">
        <p>Hello! I'm WattAI, your smart assistant for electric vehicles and car management. How can I help you today?</p>
        <div class="quick-suggestions">
            <button class="suggestion-chip" data-prompt="Show me available electric cars">Available EVs</button>
            <button class="suggestion-chip" data-prompt="Recommend a car based on range">Best range</button>
            <button class="suggestion-chip" data-prompt="Help me choose a charging station">Charging stations</button>
        </div>
        </div>`;
        
        // Add event listeners to new suggestion chips
        document.querySelectorAll('.suggestion-chip').forEach(chip => {
            chip.addEventListener('click', () => {
                this.aiChatInput.value = chip.getAttribute('data-prompt');
                this.handleAIChatSend();
            });
        });
        
        this.quickAccessButton.style.display = 'none';
        this.aiChatDialog.style.display = 'flex';
        this.aiChatInput.focus();
    }

    closeAIChatDialog() {
        this.aiChatDialog.style.display = 'none';
    }

    async handleAIChatSend() {
        const userMessage = this.aiChatInput.value.trim();
        if (!userMessage) return;

        this.addChatMessage(userMessage, 'user');
        this.aiChatInput.value = '';
        
        // Show typing indicator
        this.showTypingIndicator(true);

        try {
            const response = await this.getAIResponse(userMessage);
            const aiResponse = this.extractTextFromResponse(response,userMessage);
            
            // Simulate typing effect
            await this.typeMessage(aiResponse, 'ai');
            
            const foundItemName = this.containsItem(aiResponse);
            if (foundItemName) {
                this.setupQuickAccessButton(foundItemName);
            }
        } catch (error) {
            console.error('AI Error:', error);
            this.addChatMessage("Sorry, I'm having trouble responding. Please try again later.", 'ai');
        } finally {
            this.showTypingIndicator(false);
        }
    }

    showTypingIndicator(show) {
        const indicator = document.getElementById('typingIndicator');
        indicator.style.display = show ? 'flex' : 'none';
    }

    async typeMessage(message, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `ai-chat-message ${sender} typing`;
        this.aiChatMessages.appendChild(messageDiv);
        
        let i = 0;
        const typingSpeed = 20; // ms per character
        
        return new Promise(resolve => {
            const typingInterval = setInterval(() => {
                if (i < message.length) {
                    messageDiv.textContent = message.substring(0, i + 1);
                    i++;
                    this.aiChatMessages.scrollTop = this.aiChatMessages.scrollHeight;
                } else {
                    clearInterval(typingInterval);
                    messageDiv.classList.remove('typing');
                    resolve();
                }
            }, typingSpeed);
        });
    }

    addChatMessage(message, sender) {
        // Don't add if we're going to type it
        if (sender === 'ai' && this.settings.typingEffect) return;
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `ai-chat-message ${sender}`;
        
        if (sender === 'ai') {
            // Format AI responses with potential markdown
            messageDiv.innerHTML = this.formatResponse(message);
        } else {
            messageDiv.textContent = message;
        }
        
        this.aiChatMessages.appendChild(messageDiv);
        this.aiChatMessages.scrollTop = this.aiChatMessages.scrollHeight;
    }

    formatResponse(text) {
        // Simple markdown formatting
        return text
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>') // bold
            .replace(/\*(.*?)\*/g, '<em>$1</em>') // italic
            .replace(/\n/g, '<br>'); // line breaks
    }
    
    async getAIResponse(prompt) {

        
        const apiKey = 'AIzaSyDekMV2YjTBB5GTfwNjwVSxYB9EH9FKCIw'; // Consider moving this to a config
        const apiUrl = `https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro-latest:generateContent?key=${apiKey}`;
        
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                contents: [{
                    parts: [{
                        text: `${prompt} please keep the answer short and straight to the point with a small clarification`
                    }]
                }]
            })
        });

        if (!response.ok) {
            throw new Error(`API error: ${response.status}`);
        }

        return await response.json();
    }

    extractTextFromResponse(response,prompt) {
        // First check if question is car-related
        const isCarRelated = this.checkCarRelated(prompt);
        
        if (!isCarRelated) {
            return "I'm sorry, I'm specialized as a Car Expert and can only answer automotive-related questions.";
        }
        
        try {
            if (response.candidates && response.candidates[0] && response.candidates[0].content && 
                response.candidates[0].content.parts && response.candidates[0].content.parts[0]) {
                return response.candidates[0].content.parts[0].text;
            }
            return "Invalid response format";
        } catch (error) {
            console.error('Error parsing AI response:', error);
            return "Error processing response";
        }
    }

    containsItem(text) {
        const inventoryItems = ["brake pads", "battery", "brake pad", "charge controller"]; // Your items
        const lowerText = text.toLowerCase();
        
        for (const item of inventoryItems) {
            if (lowerText.includes(item.toLowerCase())) {
                return item;
            }
        }
        return null;
    }

    setupQuickAccessButton(itemName) {
        this.quickAccessButton.style.display = 'block';
        this.quickAccessButton.textContent = `Quick Access ${itemName}`;
        
        // Clear previous click handlers
        this.quickAccessButton.onclick = null;
        
        // Add new click handler
        this.quickAccessButton.onclick = () => {
            // Find the product card that contains this item name
            const productCards = document.querySelectorAll('.product-card');
            let targetCard = null;
            
            // Find the card with matching product name (case insensitive)
            productCards.forEach(card => {
                const titleElement = card.querySelector('.product-title');
                if (titleElement && titleElement.textContent.toLowerCase().includes(itemName.toLowerCase())) {
                    targetCard = card;
                }
            });
            
            if (targetCard) {
                // Get the add to cart button
                const addButton = targetCard.querySelector('.add-to-cart-button');
                if (addButton) {
                    // Create and dispatch a click event
                    const clickEvent = new MouseEvent('click', {
                        view: window,
                        bubbles: true,
                        cancelable: true
                    });
                    addButton.dispatchEvent(clickEvent);
                }
            }
            
            // Close the AI chat dialog
            this.aiChatDialog.style.display = 'none';
        };
    }
    checkCarRelated(prompt) {
        const carKeywords = [
            'car', 'vehicle', 'automobile', 'engine', 'transmission', 'brake', 'brakes', 'tire', 'tires',
            'oil', 'fuel', 'gas', 'diesel', 'hybrid', 'electric vehicle', 'ev', 'charging', 'battery',
            'steering', 'wheel', 'dashboard', 'speedometer', 'mileage', 'odometer', 'gear', 'gearbox',
            'clutch', 'accelerator', 'brake pedal', 'gas pedal', 'parking brake', 'suspension', 'axle',
            'camshaft', 'crankshaft', 'cylinder', 'piston', 'radiator', 'coolant', 'thermostat', 'timing belt',
            'drive belt', 'spark plug', 'alternator', 'starter', 'air filter', 'cabin filter', 'fuel filter',
            'transmission fluid', 'engine oil', 'exhaust', 'muffler', 'catalytic converter', 'tailpipe',
            'bumper', 'headlight', 'taillight', 'turn signal', 'fog light', 'windshield', 'wipers', 'mirror',
            'rearview mirror', 'side mirror', 'door', 'trunk', 'hood', 'sunroof', 'seatbelt', 'airbag',
            'dashboard light', 'check engine', 'service', 'maintenance', 'alignment', 'tuning', 'turbo',
            'supercharger', 'horsepower', 'torque', 'rpm', 'drivetrain', '4wd', 'awd', 'fwd', 'rwd',
            'license plate', 'registration', 'inspection', 'insurance', 'dealership', 'car warranty',
            'sedan', 'suv', 'truck', 'convertible', 'coupe', 'van', 'minivan', 'roadster', 'hatchback'
        ];
    
        const lowerPrompt = prompt.toLowerCase();
        return carKeywords.some(keyword => lowerPrompt.includes(keyword));
    }

    

    
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new ShopCart();
});