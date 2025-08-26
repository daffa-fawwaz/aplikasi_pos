import { Html5Qrcode } from "html5-qrcode";

let html5QrCode = null;
let isScanning = false;

window.startCamera = async function () {
    if (!html5QrCode) html5QrCode = new Html5Qrcode("reader");

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
                    document.getElementById("barcode").value = decodedText;
                    stopCamera();
                }
            },
            (error) => console.warn(error)
        );
    } else alert("Tidak ada kamera tersedia.");
};

window.stopCamera = async function () {
    if (html5QrCode) {
        await html5QrCode.stop();
        document.getElementById("reader").style.display = "none";
        document.getElementById("stop-scan").classList.add("hidden");
        document.getElementById("start-scan").classList.remove("hidden");
    }
};
