// Document Ready Event Listener
document.addEventListener('DOMContentLoaded', () => {
    // Swiper Carousel Initialization (Home Section)
    const swiper = new Swiper('.mySwiper', {
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });

    // Menu Fetching Function Call
    fetchMenuItems();

    // Contact Form Submission Handler
    const contactForm = document.getElementById('contact-form');
    if(contactForm) {
        contactForm.addEventListener('submit', (e) => {
            e.preventDefault();
            contactForm.submit();
        });
    }

    // Reservation Form Submission Handler
    const reservationForm = document.getElementById('reservation-form');
    if(reservationForm) {
        reservationForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const date = reservationForm.querySelector('[name="date_time"]').value;
            const guests = reservationForm.querySelector('[name="guests"]').value;
            makeReservation(date, guests);
        });
    }

    // Checkout Button Handler
    const checkoutBtn = document.getElementById('checkout');
    if(checkoutBtn) {
        checkoutBtn.addEventListener('click', () => {
            fetch('checkout.php', { method: 'POST' })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        alert('Order placed successfully!');
                        updateCartDisplay();
                    }
                });
        });
    }
});

// Menu Fetching Function
function fetchMenuItems() {
    fetch('get_menu.php')
        .then(response => response.json())
        .then(items => {
            const menuContainer = document.getElementById('menu-container');
            items.forEach(item => {
                const menuItem = document.createElement('div');
                menuItem.classList.add('col-md-4', 'mb-4', 'menu-item');
                menuItem.innerHTML = `
                    <img src="${item.image}" alt="${item.name}" class="img-fluid rounded mb-3">
                    <h3>${item.name}</h3>
                    <p>${item.description}</p>
                    <p>${item.price} DA</p>
                    <button class="btn btn-primary" onclick="addToFavorites(${item.id})">Add to Favorites</button>
                    <button class="btn btn-primary mt-2" onclick="addToCart(${item.id}, 1)">Add to Cart</button>
                `;
                menuContainer.appendChild(menuItem);
            });
        });
}

// Add to Favorites Function
function addToFavorites(itemId) {
    fetch('add_favorite.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ itemId: itemId })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) alert('Added to favorites!');
        else alert(data.message);
    });
}

// Add to Cart Function
function addToCart(itemId, quantity) {
    fetch('add_to_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ itemId: itemId, quantity: quantity })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) updateCartDisplay();
    });
}

// Make Reservation Function
function makeReservation(date, guests) {
    fetch('make_reservation.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ date: date, guests: guests })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) alert('Reservation made successfully!');
        else alert(data.message);
    });
}

// Update Cart Display Function
function updateCartDisplay() {
    window.location.reload();
}