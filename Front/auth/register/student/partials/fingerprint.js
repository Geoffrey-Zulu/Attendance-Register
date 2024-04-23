// event listener for the scan button
secugen_lic = ""
document.getElementById("scanButton").addEventListener("click", scanFingerprint);
async function scanFingerprint() {
    const uri = "https://localhost:8443/SGIFPCapture"; 

    try {
        const response = await fetch(uri, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: new URLSearchParams({
                licstr: secugen_lic, 
            }),
        });

        if (response.ok) {
            const fpobject = await response.json();
            if (fpobject.ErrorCode === 0) {
                // "Scanning..."
                document.getElementById("scanningStatus").value = "Scanning...";
                // Get the fingerprint data
                const fingerprintData = fpobject.TemplateBase64;
                if (fingerprintData) {
                    // Send fingerprint data to PHP script using AJAX
                    sendFingerprintDataToPHP(fingerprintData);
                } else {
                    // Update scanning status to indicate an error
                    document.getElementById("scanningStatus").value = "Error: Fingerprint data is empty.";
                }
            } else {
                // Handle error
                document.getElementById("scanningStatus").value = "Error: Fingerprint Capture Error Code: " + fpobject.ErrorCode;
            }
        } else {
            // Handle error
            document.getElementById("scanningStatus").value = "Error occurred while capturing fingerprint.";
        }
    } catch (error) {
        // Handle error
        document.getElementById("scanningStatus").value = "Error occurred while capturing fingerprint: " + error.message;
    }
}

async function sendFingerprintDataToPHP(fingerprintData) {
    try {
        // Log the fingerprint data to verify
        console.log("Fingerprint data to be sent:", fingerprintData);

        // Send fingerprint data to PHP script using AJAX
        const response = await fetch('students.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                fingerprint: fingerprintData
            })
        });

        if (response.ok) {
            // Fingerprint data sent successfully
            document.getElementById("scanningStatus").value =  fingerprintData;  
        } else {
            // Handle error
            document.getElementById("scanningStatus").value = "Error: Failed to send fingerprint data.";
        }
    } catch (error) {
        // Handle error
        document.getElementById("scanningStatus").value = "Error occurred while sending fingerprint data: " + error.message;
    }
}

function spinner() {
    document.getElementById("overlay").style.display = "block";
    // set delay
    setTimeout(function() {
        window.location.href = "students.php";
    }, 2000); // 2 seconds delay
}
