import { Html5Qrcode } from "html5-qrcode";

let html5QrCode = null;
let isScanning = false;

function showItemDetail(item) {
    const container = document.getElementById("item-detail");
    const content = document.getElementById("item-content");
    const backdrop = document.getElementById("modal-backdrop");

    content.innerHTML = `
        <div class="space-y-4">
            <div class="flex justify-between py-2 border-b dark:border-gray-700">
                <span class="text-gray-600 dark:text-gray-400">Nama Barang</span>
                <span class="font-medium text-gray-900 dark:text-white">${
                    item.nama_barang
                }</span>
            </div>
            <div class="flex justify-between py-2 border-b dark:border-gray-700">
                <span class="text-gray-600 dark:text-gray-400">Tipe</span>
                <span class="font-medium text-gray-900 dark:text-white">${
                    item.tipe_barang ?? "-"
                }</span>
            </div>
            <div class="flex justify-between py-2 border-b dark:border-gray-700">
                <span class="text-gray-600 dark:text-gray-400">Harga</span>
                <span class="font-medium text-gray-900 dark:text-white">Rp ${new Intl.NumberFormat(
                    "id-ID"
                ).format(item.harga_jual)}</span>
            </div>
            <div class="flex justify-between py-2 border-b dark:border-gray-700">
                <span class="text-gray-600 dark:text-gray-400">Stok</span>
                <span class="font-medium text-gray-900 dark:text-white">${
                    item.stok
                }</span>
            </div>
        </div>
    `;

    // Show backdrop and modal
    backdrop.classList.remove("hidden");
    container.classList.remove("hidden");
    container.classList.add("flex");
}

function closeDetail() {
    const container = document.getElementById("item-detail");
    const backdrop = document.getElementById("modal-backdrop");

    // Hide backdrop and modal
    backdrop.classList.add("hidden");
    container.classList.add("hidden");
    container.classList.remove("flex");
}

// Add this to your existing functions
function addToCart() {
    alert("Fitur akan segera tersedia!");
    closeDetail();
}

function fetchItem(barcode) {
    fetch(`/barcode/search?barcode=${encodeURIComponent(barcode)}`)
        .then((response) => response.json())
        .then((data) => {
            if (data.message) {
                // Jika ada pesan error
                throw new Error(data.message);
            }
            showItemDetail(data); // Data langsung adalah item
        })
        .catch((error) => {
            alert(error.message || "Terjadi kesalahan saat mencari barang");
            console.error("Error:", error);
        });
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
                console.log("Scanned code:", decodedText);

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
