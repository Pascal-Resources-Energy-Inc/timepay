<script>
function printAuthorityToDeductForm(modalId) {
    // Get the specific modal element
    const modal = document.getElementById(modalId);
    if (!modal) {
        console.error('Modal not found:', modalId);
        return;
    }

    // Extract data from the modal
    const formData = extractAuthorityDeductFormData(modal);
    
    const printWindow = window.open('', '_blank', 'width=800,height=600');

    const printContent = `
    <!DOCTYPE html>
    <html>
    <head>
        <title>Authority to Deduct - Pascal Resources Energy Inc.</title>
        <style>
            @media print {
                body { margin: 0; }
                .no-print { display: none; }
            }

            @page {
                size: A4;
                margin: 15mm;
            }

            body {
                font-family: Arial, sans-serif;
                max-width: 8.5in;
                margin: 0 auto;
                padding: 20px;
                background: white;
                color: black;
                line-height: 1.4;
                font-size: 12px;
                position: relative;
            }

            .header {
                border: 1px solid black;
                display: flex;
                align-items: center;
                margin-bottom: 30px;
            }

            .company-info {
                border-right: 1px solid black;
                padding: 10px;
                flex: 0 0 110px;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            .company-name {
                font-weight: bold;
                font-size: 25px;
                margin: 0;
                line-height: 1.2;
            }

            .company-subtitle {
                font-size: 15px;
                font-weight: bold;
                margin: 0;
                line-height: 1.2;
            }

            .form-title {
                flex: 1;
                text-align: center;
                font-weight: bold;
                font-size: 20px;
                padding: 15px;
            }

            .field-row {
                display: flex;
                align-items: center;
                margin-bottom: 15px;
            }

            .field-row.right-align {
                justify-content: flex-end;
                margin-bottom: 25px;
            }

            .field-label {
                margin-right: 10px;
            }

            .field-input {
                border: none;
                border-bottom: 1px solid black;
                min-width: 200px;
                padding: 2px 0;
                display: inline-block;
                font-weight: bold;
                text-align: center;
            }

            .field-input.wide {
                min-width: 100px;
            }

            .table-container {
                margin: 25px 0;
            }

            .deduction-table {
                width: 100%;
                border-collapse: collapse;
                border: 1px solid black;
            }

            .deduction-table th,
            .deduction-table td {
                border: 1px solid black;
                padding: 12px;
                text-align: center;
                font-weight: bold;
            }

            .deduction-table th {
                background-color: #f5f5f5;
            }

            .deduction-table td {
                height: 40px;
                font-weight: normal;
            }

            .terms {
                margin: 25px 0;
                font-size: 12px;
                line-height: 1.5;
            }

            .voluntary-notice {
                font-style: italic;
                font-weight: bold;
                margin: 20px 0;
                font-size: 12px;
            }

            .signature-section {
                display: flex;
                justify-content: space-between;
                margin: 40px 0 20px 0;
                position: relative;
            }

            .signature-block {
                text-align: center;
                flex: 1;
            }

            .signature-line {
                border-bottom: 1px solid black;
                width: 200px;
                margin: 0 auto 5px auto;
                height: 20px;
                font-weight: bold;
                padding-top: 5px;
                position: relative;
            }

            .signature-image {
                position: absolute;
                top: -20px;
                left: 50%;
                transform: translateX(-50%);
                height: 40px;
                max-width: 180px;
            }

            .signature-label {
                font-size: 12px;
                margin-bottom: 5px;
            }

            .status-stamp {
                position: absolute;
                top: -75%;
                right: -2%;
                transform: translateX(-50%);
                width: 170px;
                opacity: 0.5;
                pointer-events: none;
                z-index: 10;
            }

            .form-number {
                text-align: right;
                font-size: 10px;
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <div class="company-info">
                <div class="company-name">PASCAL</div>
                <div class="company-subtitle">RESOURCES</div>
                <div class="company-subtitle">ENERGY, INC.</div>
            </div>
            <div class="form-title">AUTHORITY TO DEDUCT</div>
        </div>

        <div class="field-row right-align">
            <span class="field-label">Series no:</span>
            <span class="field-input">${formData.seriesNo || ''}</span>
        </div>

        <div class="field-row right-align">
            <span class="field-label">Date received:</span>
            <span class="field-input">${formData.dateReceived || ''}</span>
        </div>

        <br>
        <div class="field-row">
            <span class="field-label">I,</span>
            <span class="field-input">${formData.employeeName} </span>
            <span class="field-label">&nbsp; hereby authorize <strong>PASCAL RESOURCES ENERGY INC.</strong> to deduct from my</span>
        </div>

        <div class="field-row">
            <span class="field-label">wages for</span>
            <span class="field-input wide">${formData.particular}</span>
            <span class="field-label">&nbsp; with the total amount of</span>
            <span class="field-input wide">${formData.amount}</span> <span class="field-label">&nbsp; only.</span>
        </div>

        <div class="table-container">
            <table class="deduction-table">
                <thead>
                    <tr>
                        <th>Particular</th>
                        <th>Payment</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>${formData.particular}</td>
                        <td>${formData.deductible}</td>
                        <td>${formData.amount}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="terms">
            <p>I understand that the cost of the items will be deducted from my wages. I further understand that it is my responsibility to pay for any cost associated with returning items.</p>
            <p>If I terminate my position prior to the purchase being paid in full, I authorize Pascal Resources Energy Inc. to deduct the unpaid balance from my wages.</p>
        </div>

        <div class="voluntary-notice">
            THIS IS A VOLUNTARY PROGRAM AND IS NOT A CONDITION OF EMPLOYMENT WITH PASCAL RESOURCES ENERGY INC.
        </div>

        <div class="signature-section">
            <div class="signature-block">
                <div class="signature-line">
                    ${formData.signatureImage ? `<img src="data:image/png;base64,${formData.signatureImage}" alt="Signature" class="signature-image">` : ''}
                    ${formData.employeeName}
                </div>
                <span style="color: lightgrey; position: absolute; margin-top: -50px; font-size: 0.3rem;">
                        Date: ${formData.appliedDate}
                </span>
                <div class="signature-label">Employee</div>
                <div class="signature-label">(Signature over printed name)</div>
            </div>
            <div class="signature-block">
                <div class="signature-line">${formData.appliedDate}</div>
                <div class="signature-label">Date issued</div>
            </div>
            ${formData.status === 'Approved' ? 
                `<img src="signed/APPROVED.png" alt="Approved" class="status-stamp">` : 
                formData.status === 'Declined' ? 
                `<img src="signed/DENIED.png" alt="Declined" class="status-stamp">` : 
                ''
            }
        </div>

        <div class="form-number">
            HRD-CBD-FOR-002-000 | ATD Form
        </div>

        <div class="no-print" style="text-align: center; margin-top: 20px;">
            <button onclick="window.print()" style="padding: 10px 20px;">Print</button>
            <button onclick="window.close()" style="padding: 10px 20px; margin-left: 10px;">Close</button>
        </div>
    </body>
    </html>
    `;

    printWindow.document.write(printContent);
    printWindow.document.close();
    printWindow.focus();
    
    // Store reference for cleanup
    window.activePrintWindow = printWindow;
    
    // Enhanced focus detection and window management
    setupPrintWindowClosingBehavior(printWindow);
    
    printWindow.onload = function() {
        setTimeout(() => {
            printWindow.print();
        }, 500);
    };
}

function setupPrintWindowClosingBehavior(printWindow) {
    let focusCheckInterval;
    let isClosingHandled = false;
    
    // Function to safely close the print window
    const closePrintWindow = () => {
        if (isClosingHandled) return;
        isClosingHandled = true;
        
        if (printWindow && !printWindow.closed) {
            printWindow.close();
            window.activePrintWindow = null;
        }
        
        // Clear all event listeners and intervals
        clearInterval(focusCheckInterval);
        document.removeEventListener('visibilitychange', handleVisibilityChange);
        window.removeEventListener('blur', handleWindowBlur);
        window.removeEventListener('focus', handleWindowFocus);
        document.removeEventListener('click', handleParentClick);
    };

    // Handle window focus/blur events (Alt+Tab detection)
    const handleWindowBlur = () => {
        // When parent window loses focus, set a timer to check if print window is still active
        setTimeout(() => {
            if (!printWindow.closed && !printWindow.document.hasFocus()) {
                // Neither parent nor print window has focus - user likely switched to another app
                closePrintWindow();
            }
        }, 500);
    };

    const handleWindowFocus = () => {
        // When parent window gains focus, check if user came back from another application
        if (printWindow && !printWindow.closed) {
            // Optional: You can add logic here if you want different behavior when returning
        }
    };

    // Handle page visibility changes (browser tab switching)
    const handleVisibilityChange = () => {
        if (document.hidden && printWindow && !printWindow.closed) {
            // User switched to another tab or minimized browser
            setTimeout(() => {
                if (document.hidden) {
                    closePrintWindow();
                }
            }, 500);
        }
    };

    const handleParentClick = (e) => {
        if (printWindow && !printWindow.closed) {
            setTimeout(() => {
                if (printWindow && !printWindow.closed) {
                    closePrintWindow();
                }
            }, 100);
        }
    };

    const startFocusMonitoring = () => {
        focusCheckInterval = setInterval(() => {
            if (!printWindow || printWindow.closed) {
                clearInterval(focusCheckInterval);
                isClosingHandled = true;
                window.activePrintWindow = null;
                return;
            }

            try {
                const parentHasFocus = document.hasFocus();
                const printWindowHasFocus = printWindow.document.hasFocus();
                
                if (!parentHasFocus && !printWindowHasFocus) {

                    closePrintWindow();
                }
            } catch (e) {
                closePrintWindow();
            }
        }, 1000);
    };

    // Set up all event listeners
    window.addEventListener('blur', handleWindowBlur);
    window.addEventListener('focus', handleWindowFocus);
    document.addEventListener('visibilitychange', handleVisibilityChange);
    
    // Start monitoring after a short delay to allow print dialog to appear
    setTimeout(() => {
        if (!isClosingHandled && printWindow && !printWindow.closed) {
            startFocusMonitoring();
            
            // Add click listener to parent window after initial delay
            setTimeout(() => {
                if (!isClosingHandled) {
                    document.addEventListener('click', handleParentClick);
                }
            }, 2000);
        }
    }, 1000);

    // Cleanup when print window closes naturally
    const checkClosed = setInterval(() => {
        if (!printWindow || printWindow.closed) {
            clearInterval(checkClosed);
            closePrintWindow();
        }
    }, 1000);

    // Also handle print window's own events if accessible
    try {
        printWindow.addEventListener('beforeunload', () => {
            closePrintWindow();
        });
        
        printWindow.addEventListener('blur', () => {
            // Print window lost focus
            setTimeout(() => {
                if (!printWindow.closed && !document.hasFocus() && !printWindow.document.hasFocus()) {
                    closePrintWindow();
                }
            }, 500);
        });
    } catch (e) {
        // Might not be accessible due to security restrictions
        console.log('Print window event listeners not accessible');
    }
}

// Simplified version of the print function (keeping for compatibility)
function printAuthorityToDeductFormSimple(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) {
        console.error('Modal not found:', modalId);
        return;
    }

    const formData = extractAuthorityDeductFormData(modal);
    const printWindow = window.open('', '_blank', 'width=800,height=600');
    
    // ... (same HTML content as above)
    
    setupPrintWindowClosingBehavior(printWindow);
    
    printWindow.onload = function() {
        setTimeout(() => {
            printWindow.print();
        }, 500);
    };
}

function extractAuthorityDeductFormData(modal) {
    // Helper function to get text content safely
    const getTextContent = (selector) => {
        const element = modal.querySelector(selector);
        return element ? element.textContent.trim() : '';
    };

    // Helper function to get attribute value safely
    const getAttributeValue = (selector, attribute) => {
        const element = modal.querySelector(selector);
        return element ? element.getAttribute(attribute) : '';
    };

    // FIXED: Extract employee name more specifically
    // Look for the employee name in the "I, ..." paragraph
    let employeeName = '';
    const formFieldsParagraphs = modal.querySelectorAll('.form-fields p');
    if (formFieldsParagraphs.length > 0) {
        // Get the first paragraph which contains "I, [name] hereby authorize..."
        const firstParagraph = formFieldsParagraphs[0];
        const strongInParagraph = firstParagraph.querySelector('strong');
        if (strongInParagraph) {
            employeeName = strongInParagraph.textContent.trim();
        }
    }
    
    // Fallback method if the above doesn't work
    if (!employeeName) {
        // Look for strong elements that contain typical name patterns (has spaces, not just numbers)
        const employeeNameElements = modal.querySelectorAll('strong');
        employeeNameElements.forEach(el => {
            const text = el.textContent.trim();
            // Check if it's likely a name (contains spaces and letters, not just numbers/codes)
            if (text && 
                text !== 'PASCAL RESOURCES ENERGY INC.' && 
                text.includes(' ') && // Names typically have spaces
                /[a-zA-Z]/.test(text) && // Contains letters
                !/^\d+$/.test(text) && // Not just numbers (like AD number)
                !employeeName) {
                employeeName = text;
            }
        });
    }

    // Extract table data
    const tableRows = modal.querySelectorAll('.deduct-table tbody tr');
    let particular = '';
    let deductible = '';
    let amount = '';
    
    if (tableRows.length > 0) {
        const cells = tableRows[0].querySelectorAll('td');
        if (cells.length >= 3) {
            particular = cells[0].textContent.trim();
            deductible = cells[1].textContent.trim();
            amount = cells[2].textContent.trim();
        }
    }

    // Extract signature image if present
    let signatureImage = '';
    const sigImg = modal.querySelector('img[alt="Signature"]');
    if (sigImg) {
        const src = sigImg.getAttribute('src');
        if (src && src.startsWith('data:image/png;base64,')) {
            signatureImage = src.replace('data:image/png;base64,', '');
        }
    }

    // Extract applied date
    let appliedDate = '';
    const dateElements = modal.querySelectorAll('strong');
    dateElements.forEach(el => {
        const text = el.textContent.trim();
        // Look for date pattern (assuming format like "17 Jun, 2025")
        if (text.match(/\d{1,2}\s+\w{3},?\s+\d{4}/)) {
            appliedDate = text;
        }
    });

    // Extract approved date
    let approvedDate = '';
    const approvedDateInput = modal.querySelector('input[name="approvedDate"]');

    function formatDateToDDMMMYYYY(dateStr) {
        const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        const date = new Date(dateStr);
        if (isNaN(date)) return ''; // invalid date

        const day = String(date.getDate()).padStart(2, '0');
        const month = months[date.getMonth()];
        const year = date.getFullYear();

        return `${day}-${month}-${year}`;
    }

    if (approvedDateInput) {
        approvedDate = approvedDateInput.value || '';
        approvedDate = formatDateToDDMMMYYYY(approvedDate);
    } else {
        const approvedDateSpan = modal.querySelector('span[name="approved"]');
        if (approvedDateSpan) {
            const text = approvedDateSpan.textContent;
            const dateMatch = text.match(/(\d{1,2}-\w{3}-\d{4})/);
            if (dateMatch) {
                approvedDate = dateMatch[1];
            }
        }
    }

    // Extract status from hidden input or status image
    let status = '';
    const statusInput = modal.querySelector('input[name="status"]');
    if (statusInput) {
        status = statusInput.value || '';
    } else {
        // Alternative: check for status images
        const approvedImg = modal.querySelector('img[alt="Approved"]');
        const declinedImg = modal.querySelector('img[alt="Declined"]');
        if (approvedImg) {
            status = 'Approved';
        } else if (declinedImg) {
            status = 'Declined';
        }
    }

    let seriesNo = '';
    const seriesNoInput = modal.querySelector('input[name="seriesNo"]');
    if (seriesNoInput) {
        seriesNo = seriesNoInput.value || '';
    } else {
        // Alternative: extract from the span with data-value attribute
        const seriesSpan = modal.querySelector('span[data-value]');
        if (seriesSpan) {
            seriesNo = seriesSpan.getAttribute('data-value') || seriesSpan.textContent.trim();
        }
    }

    // Extract Date Received
    let dateReceived = '';
    // Look for the date received in the div with class "dat"
    const dateReceivedDiv = modal.querySelector('.dat strong');
    if (dateReceivedDiv) {
        dateReceived = dateReceivedDiv.textContent.trim();
    } else {
        // Alternative: look for input with name dateReceived if you add one
        const dateReceivedInput = modal.querySelector('input[name="dateReceived"]');
        if (dateReceivedInput) {
            dateReceived = dateReceivedInput.value || '';
        }
    }

    return {
        employeeName: employeeName,
        particular: particular,
        deductible: deductible,
        amount: amount,
        appliedDate: appliedDate,
        signatureImage: signatureImage,
        status: status,
        seriesNo: seriesNo,
        dateReceived: dateReceived, 
    };
}

// Function to be called from your modal print button
function printModalContentSameWindow(modalId) {
    printAuthorityToDeductForm(modalId);
}
    </script>