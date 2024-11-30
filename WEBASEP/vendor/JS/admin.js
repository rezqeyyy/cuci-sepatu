document.addEventListener("DOMContentLoaded", () => {
    // Function to display orders in the table
    function displayOrders(orders) {
        const orderTableBody = document.getElementById('orderTable').querySelector('tbody');
        orderTableBody.innerHTML = '';

        orders.forEach((order, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${order.name}</td>
                <td>${order.phone}</td>
                <td>${order.address}</td>
                <td>${order.delivery}</td>
                <td>${order.orderDetails.replace(/, /g, '<br>')}</td>
                <td>${order.totalItems}</td>
                <td>Rp ${order.total} rb</td>
                <td>${new Date(order.timestamp).toLocaleString()}</td>
                <td><button class="done-btn" onclick="markAsDone(${index})">Selesai</button></td>
            `;
            orderTableBody.appendChild(row);
        });
    }

    // Function to display ready for delivery orders
    function displayReadyForDeliveryOrders(orders) {
        const readyForDeliveryTableBody = document.getElementById('readyForDeliveryTable').querySelector('tbody');
        readyForDeliveryTableBody.innerHTML = '';

        orders.forEach((order, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${order.name}</td>
                <td>${order.phone}</td>
                <td>${order.address}</td>
                <td>${order.delivery}</td>
                <td>${order.orderDetails.replace(/, /g, '<br>')}</td>
                <td>${order.totalItems}</td>
                <td>Rp ${order.total} rb</td>
                <td>${new Date(order.timestamp).toLocaleString()}</td>
                <td><button class="delete-btn" onclick="deleteReadyOrder(${index})">Hapus</button></td>
            `;
            readyForDeliveryTableBody.appendChild(row);
        });
    }

    // Retrieve orders from localStorage and display them
    const orders = JSON.parse(localStorage.getItem('orders')) || [];
    const readyForDeliveryOrders = JSON.parse(localStorage.getItem('readyForDeliveryOrders')) || [];
    displayOrders(orders);
    displayReadyForDeliveryOrders(readyForDeliveryOrders);

    // Function to mark an order as done
    window.markAsDone = function(index) {
        const order = orders[index];
        if (order.delivery === 'Yes') {
            readyForDeliveryOrders.push(order);
            localStorage.setItem('readyForDeliveryOrders', JSON.stringify(readyForDeliveryOrders));
        }
        orders.splice(index, 1);
        localStorage.setItem('orders', JSON.stringify(orders));
        displayOrders(orders);
        displayReadyForDeliveryOrders(readyForDeliveryOrders);
    };

    // Function to delete a ready for delivery order
    window.deleteReadyOrder = function(index) {
        if (confirm("Are you sure you want to delete this order?")) {
            readyForDeliveryOrders.splice(index, 1);
            localStorage.setItem('readyForDeliveryOrders', JSON.stringify(readyForDeliveryOrders));
            displayReadyForDeliveryOrders(readyForDeliveryOrders);
        }
    };
});
