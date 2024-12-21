// Skrip untuk menangani fitur interaktivitas seperti modal, validasi form, atau efek tampilan dinamis

// Contoh: Menampilkan alert jika form disubmit
document.querySelector('form').addEventListener('submit', function(event) {
    alert("Form telah disubmit!");
});

// Contoh: Validasi input
document.querySelector('form').addEventListener('submit', function(event) {
    let title = document.getElementById('title').value;
    if (title.trim() === '') {
        event.preventDefault();  // Menghentikan submit jika judul kosong
        alert("Judul harus diisi!");
    }
});
