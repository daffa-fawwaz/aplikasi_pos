import { Html5Qrcode } from "html5-qrcode";

let html5QrCode = null;
let isScanning = false;

function showItemDetail(item) {
    const container = document.getElementById("item-detail");
    const content = document.getElementById("item-content");
    content.innerHTML = `
        <p><strong>Nama:</strong> ${item.nama_barang}</p>
        <p><strong>Tipe:</strong> ${item.tipe_barang ?? "-"}</p>
        <p><strong>Harga Jual:</strong> Rp ${new Intl.NumberFormat(
            "id-ID"
        ).format(item.harga_jual)}</p>
        <p><strong>Stok:</strong> ${item.stok}</p>
    `;
    container.style.display = "block";
}

function fetchItem(barcode) {
    fetch(`/barcode/search?barcode=${encodeURIComponent(barcode)}`)
        .then((response) => {
            if (!response.ok) throw new Error("Barang tidak ditemukan");
            return response.json();
        })
        .then((data) => showItemDetail(data))
        .catch((error) => alert(error.message));
}

async function startCamera() {
    if (!html5QrCode) {
        html5QrCode = new Html5Qrcode("reader");
    }

    const cameras = await Html5Qrcode.getCameras();
    if (cameras && cameras.length) {
        isScanning = true;
        document.getElementById("reader").style.display = "block";
        document.getElementById("stop-scan").classList.remove("hidden");
        document.getElementById("start-scan").classList.add("hidden");

        await html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: 250 },
            (decodedText) => {
                if (isScanning) {
                    isScanning = false;
                    stopCamera();
                    fetchItem(decodedText);
                }
            },
            (error) => console.warn(error)
        );
    } else {
        alert("Tidak ada kamera tersedia.");
    }
}

async function stopCamera() {
    if (html5QrCode) {
        await html5QrCode.stop();
        document.getElementById("reader").style.display = "none";
        document.getElementById("stop-scan").classList.add("hidden");
        document.getElementById("start-scan").classList.remove("hidden");
    }
}

// ⛳️ Jalankan langsung saat file dimuat
const startBtn = document.getElementById("start-scan");
const stopBtn = document.getElementById("stop-scan");

if (startBtn && stopBtn) {
    startBtn.addEventListener("click", startCamera);
    stopBtn.addEventListener("click", stopCamera);
} else {
    console.warn("Tombol Start/Stop Scan tidak ditemukan di DOM");
}
