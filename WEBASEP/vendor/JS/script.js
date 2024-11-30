document.addEventListener('DOMContentLoaded', function() {
    const guestForm = document.getElementById('guestForm'); // Mendapatkan elemen formulir dengan id 'guestForm'
    const guestList = document.getElementById('guestList'); // Mendapatkan elemen daftar tamu dengan id 'guestList'
    const pricePerItem = 50000; // Harga per item

    // Memuat daftar tamu yang ada dari localStorage
    loadGuestList();

    guestForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Mencegah form submit default

        const name = document.getElementById('name').value; // Mendapatkan nilai input nama
        const orderQuantity = document.getElementById('orderQuantity').value; // Mendapatkan nilai input jumlah pesanan
        const pickup = document.querySelector('input[name="pickup"]:checked').value; // Mendapatkan nilai input radio yang dipilih
        const orderNumber = generateOrderNumber(); // Menghasilkan nomor pesanan otomatis

        addGuest(orderNumber, name, orderQuantity, pickup); // Menambahkan tamu ke daftar

        guestForm.reset(); // Mereset formulir setelah submit
    });

    function generateOrderNumber() { // Fungsi untuk menghasilkan nomor pesanan otomatis
        const guests = getGuestsFromStorage(); // Mendapatkan daftar tamu dari localStorage
        return guests.length + 1; // Nomor pesanan adalah jumlah tamu saat ini + 1
    }

    function addGuest(orderNumber, name, orderQuantity, pickup) { // Fungsi untuk menambahkan tamu baru
        const guest = { orderNumber, name, orderQuantity, pickup }; // Membuat objek tamu baru
        const guests = getGuestsFromStorage(); // Mendapatkan daftar tamu dari localStorage
        guests.push(guest); // Menambahkan tamu baru ke daftar tamu
        saveGuestsToStorage(guests); // Menyimpan daftar tamu yang diperbarui ke localStorage
        displayGuest(guest); // Menampilkan tamu baru di halaman
    }

    function getGuestsFromStorage() { // Fungsi untuk mendapatkan daftar tamu dari localStorage
        const guests = localStorage.getItem('guestList'); // Mendapatkan item 'guestList' dari localStorage
        return guests ? JSON.parse(guests) : []; // Jika ada, parse JSON, jika tidak, kembalikan array kosong
    }

    function saveGuestsToStorage(guests) { // Fungsi untuk menyimpan daftar tamu ke localStorage
        localStorage.setItem('guestList', JSON.stringify(guests)); // Mengubah daftar tamu menjadi string JSON dan menyimpannya di localStorage
    }

    function loadGuestList() { // Fungsi untuk memuat dan menampilkan daftar tamu dari localStorage
        const guests = getGuestsFromStorage(); // Mendapatkan daftar tamu dari localStorage
        guests.forEach(displayGuest); // Menampilkan setiap tamu di halaman
    }

    function displayGuest(guest) { // Fungsi untuk menampilkan tamu di halaman
        const totalPrice = guest.orderQuantity * pricePerItem; // Menghitung total harga
        const guestDiv = document.createElement('div'); // Membuat elemen div baru
        guestDiv.classList.add('guest-entry'); // Menambahkan kelas 'guest-entry' ke div
        guestDiv.innerHTML = ` 
            <strong>No Antrian:</strong> ${guest.orderNumber} <br>
            <strong>Nama:</strong> ${guest.name} <br>
            <strong>Jumlah Pesanan:</strong> ${guest.orderQuantity} <br>
            <strong>Total Harga:</strong> Rp. ${totalPrice.toLocaleString()} <br> <!-- Menampilkan total harga -->
            <strong>Antar/Jemput:</strong> ${guest.pickup} <br>
            <br>
        `;
        guestList.appendChild(guestDiv); // Menambahkan div ke elemen daftar tamu
    }
});
