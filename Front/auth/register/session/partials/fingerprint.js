var template_1 = "";
var studentId = "";
secugen_lic = "";

// Function to handle fingerprint capture
function CallSGIFPGetData(successCall, failCall) {
    var uri = "https://localhost:8443/SGIFPCapture";
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var fpobject = JSON.parse(xmlhttp.responseText);
            successCall(fpobject);
        } else if (xmlhttp.status == 404) {
            failCall(xmlhttp.status);
        }
    };
    xmlhttp.onerror = function () {
        failCall(xmlhttp.status);
    };
    var params = "Timeout=10000&Quality=50&licstr=" + encodeURIComponent(secugen_lic) + "&templateFormat=ISO";
    xmlhttp.open("POST", uri, true);
    xmlhttp.send(params);
}

// Success function for fingerprint capture
function SuccessFunc1(result) {
    if (result.ErrorCode == 0) {
        document.getElementById('FPImage1').src = "data:image/bmp;base64," + result.BMPBase64;
        template_1 = result.TemplateBase64;
        // Automatically match fingerprints after capture
        matchScore(succMatch, failureFunc);
    } else {
        alert("Fingerprint Capture Error: " + result.ErrorCode + " - " + ErrorCodeToString(result.ErrorCode));
    }
}

// Error function for fingerprint capture
function ErrorFunc(status) {
    alert("Check if SGIBIOSRV is running; status = " + status);
}

// Function to call SecuGen API for matching fingerprints
function matchScore(succFunction, failFunction) {
    studentId = document.getElementById('studentId').value;
    if (template_1 === "") {
        alert("Please scan a fingerprint to verify!!");
        return;
    }

    // Fetch stored fingerprint data
    fetch('get_fingerprint.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ studentId: studentId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.fingerprint) {
            var template_2 = data.fingerprint;

            var uri = "https://localhost:8443/SGIMatchScore";
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    var fpobject = JSON.parse(xmlhttp.responseText);
                    succFunction(fpobject);
                } else if (xmlhttp.status == 404) {
                    failFunction(xmlhttp.status);
                }
            };
            xmlhttp.onerror = function () {
                failFunction(xmlhttp.status);
            };
            var params = "template1=" + encodeURIComponent(template_1) + "&template2=" + encodeURIComponent(template_2) + "&licstr=" + encodeURIComponent(secugen_lic) + "&templateFormat=ISO";
            xmlhttp.open("POST", uri, false);
            xmlhttp.send(params);
        } else {
            document.getElementById('result').innerText = "Stored fingerprint not found or error occurred.";
        }
    })
    .catch(error => console.error('Error fetching stored fingerprint:', error));
}

// Success function for matching result
function succMatch(result) {
    var idQuality = document.getElementById("quality").value;
    if (result.ErrorCode == 0) {
        if (result.MatchingScore >= idQuality) {
            document.getElementById('result').innerText = "MATCHED! (" + result.MatchingScore + ")";
        } else {
            document.getElementById('result').innerText = "NOT MATCHED! (" + result.MatchingScore + ")";
        }
    } else {
        alert("Error Matching Fingerprints: " + result.ErrorCode);
    }
}

// Failure function for matching result
function failureFunc(error) {
    alert("On Match Process, failure has been called. Status: " + error);
}
