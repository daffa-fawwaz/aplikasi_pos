function increaseStok(itemId) {
    const input = document.getElementById('stokInput');
    let currentValue = parseInt(input.value) || 0;
    let newValue = currentValue + 1;
    input.value = newValue;
    updateStokOnServer(itemId, newValue);
}

function decreaseStok(itemId) {
    const input = document.getElementById('stokInput');
    let currentValue = parseInt(input.value) || 0;

    if (currentValue > 0) {
        let newValue = currentValue - 1;
        input.value = newValue;
        updateStokOnServer(itemId, newValue);
    }
}

function updateStokOnServer(itemId, newStok) {
    fetch(`/items/${itemId}/update-stok`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ stok: newStok })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            alert('Gagal update stok!');
        } else if (data.deleted) {
            // Hapus elemen dari DOM (baris item)
            const row = document.getElementById(`item-row-${itemId}`);
            if (row) row.remove();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
