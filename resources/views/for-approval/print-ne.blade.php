<script>
function printCellphoneEnrollmentForm(modalId) {
    // Get the specific modal element
    const modal = document.getElementById(modalId);
    if (!modal) {
        console.error('Modal not found:', modalId);
        return;
    }

    // Extract data from the modal
    const formData = extractCellphoneEnrollmentFormData(modal);
    
    const printWindow = window.open('', '_blank', 'width=800,height=600');

    const printContent = `
    <!DOCTYPE html>
    <html>
    <head>
        <title>Cellphone Number Enrollment Form - Pascal Resources Energy Inc.</title>
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
                line-height: 1.6;
                font-size: 12px;
            }

            .header {
                text-align: center;
                margin-bottom: 30px;
                border-bottom: 1px solid black;
                padding-bottom: 15px;
            }

            .form-title {
                font-weight: bold;
                font-size: 18px;
                margin-bottom: 5px;
            }

            .form-code {
                font-size: 11px;
                color: #666;
            }

            .form-section {
                margin-bottom: 20px;
            }

            .field-row {
                display: flex;
                align-items: center;
                margin-bottom: 12px;
                flex-wrap: wrap;
            }

            .field-row.two-column {
                display: flex;
                gap: 30px;
            }

            .field-column {
                flex: 1;
            }

            .field-label {
                margin-right: 10px;
                font-weight: normal;
            }

            .field-input {
                border: none;
                border-bottom: 1px solid black;
                min-width: 200px;
                padding: 2px 5px;
                display: inline-block;
            }

            .field-input.short {
                min-width: 100px;
            }

            .field-input.medium {
                min-width: 150px;
            }

            .checkbox-section {
                margin: 20px 0;
            }

            .checkbox-item {
                display: flex;
                align-items: flex-start;
                margin-bottom: 10px;
                line-height: 1.4;
            }

            .checkbox {
                width: 12px;
                height: 12px;
                border: 1px solid black;
                margin-right: 10px;
                margin-top: 2px;
                flex-shrink: 0;
                display: inline-block;
            }

            .checkbox.checked {
                position: relative;
            }

            .checkbox.checked::after {
                content: '✓';
                position: absolute;
                top: -2px;
                left: 1px;
                font-size: 10px;
                font-weight: bold;
            }

            .allowance-table {
                width: 100%;
                border-collapse: collapse;
                margin: 15px 0;
                font-size: 11px;
            }

            .allowance-table th,
            .allowance-table td {
                border: 1px solid black;
                padding: 8px;
                text-align: center;
            }

            .allowance-table th {
                background-color: #f5f5f5;
                font-weight: bold;
            }

            .text-danger { color: #dc3545; }
            .text-info { color: #0dcaf0; }
            .text-success { color: #198754; }

            .signature-section {
                display: flex;
                justify-content: space-between;
                margin: 40px 0 20px 0;
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
                padding-top: 5px;
            }

            .signature-label {
                font-size: 12px;
                margin-bottom: 5px;
            }

            .form-footer {
                text-align: center;
                font-size: 10px;
                margin-top: 30px;
                border-top: 1px solid #ccc;
                padding-top: 10px;
            }

            .indent {
                margin-left: 20px;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <div class="form-title">CELLPHONE NUMBER ENROLLMENT FORM</div>
            <div class="form-code">HRD-TAD-FOR-006-000</div>
        </div>

        <div class="form-section">
            <div class="field-row">
                <span class="field-label">I,</span>
                <span class="field-input" style="min-width: 220px; text-align: center;">${formData.fullName}</span>
                <span class="field-label">, with the designation of</span>
                <span class="field-input" style="min-width: 245px; text-align: center;">${formData.positionDesignation}</span>
            </div>

            <div class="field-row">
                <span class="field-label">of</span>
                <span class="field-input" style="min-width: 200px; text-align: center;">${formData.location}</span>
                <span class="field-label">request enrollment of my cellphone number</span>
                <span class="field-input medium" style="text-align: center;">${formData.cellphoneNumber}</span>
            </div>

            <div class="field-row">
                
                <span class="field-label">as primary contact number.</span>
            </div>
        </div>

        <div class="checkbox-section">
            <div class="checkbox-item">
                <span class="checkbox checked"></span>
                <span>I understand that I will be receiving a monthly communication allowance and it is my responsibility to settle my bills (if postpaid) and load my sim (if prepaid).</span>
            </div>
        </div>

        <div class="allowance-table">
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th>Designation</th>
                        <th>Amount of Load Allowance*</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Managers</td>
                        <td class="text-danger">₱800.00</td>
                    </tr>
                    <tr>
                        <td>SS & Other Field Officers</td>
                        <td class="text-info">₱500.00</td>
                    </tr>
                    <tr>
                        <td>Rank & File (ABI/IADS) & Office Based Employees</td>
                        <td class="text-success">₱300.00</td>
                    </tr>
                </tbody>
            </table>
            <div style="font-size: 10px; font-style: italic;">
                *Prorated based on date of joining or date of separation. Payable every 2nd Pay Out
            </div>
        </div>

        <div class="checkbox-section">
            <div class="checkbox-item">
                <span class="checkbox checked"></span>
                <span>I understand that I am responsible to ensure that this elected cellphone number is personal and active.</span>
            </div>
            
            <div class="checkbox-item">
                <span class="checkbox checked"></span>
                <span>I understand that in case I am changing my account, I am responsible to submit another form to update company's file.</span>
            </div>
            
            <div class="checkbox-item">
                <span class="checkbox checked"></span>
                <span>I understand that aside from text and call, our company uses Viber as official communication channel and I am required to join Gaz Life Community Group Chat.</span>
            </div>
            
            <div class="checkbox-item">
                <span class="checkbox checked"></span>
                <span>I understand that failure to reply or answer phone call within working hours can lead to issuance of NTE.</span>
            </div>
        </div>

        <div class="signature-section">
            <div class="signature-block">
                <div class="signature-line">${formData.fullName}</div>
                <div class="signature-label">Signature above Printed Name</div>
            </div>
            <div class="signature-block">
                <div class="signature-line">${formData.currentDate}</div>
                <div class="signature-label">Date</div>
            </div>
        </div>

        <div class="form-footer">
            HRD-TAD-FOR-006-000 | Cellphone Number Enrollment Form
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
    
    printWindow.onload = function() {
        setTimeout(() => {
            printWindow.print();
        }, 500);
    };
}

function extractCellphoneEnrollmentFormData(modal) {
    // Helper function to get text content safely
    const getTextContent = (selector) => {
        const element = modal.querySelector(selector);
        return element ? element.textContent.trim() : '';
    };

    // Helper function to get input/select value safely
    const getValue = (selector) => {
        const element = modal.querySelector(selector);
        return element ? element.value : '';
    };

    // Helper function to get selected option text
    const getSelectedOptionText = (selector) => {
        const element = modal.querySelector(selector);
        if (element && element.selectedOptions && element.selectedOptions.length > 0) {
            return element.selectedOptions[0].textContent.trim();
        }
        return '';
    };

    // Extract form data
    const enrollmentType = getValue('select[name="enrollment_type"]');
    const other = getValue('input[name="other"]');
    const comment = getValue('textarea[name="comment"]');
    const employeeNumber = getValue('input[name="employee_number"]');
    const positionDesignation = getValue('input[name="position_designation"]');
    const firstName = getValue('input[name="first_name"]');
    const lastName = getValue('input[name="last_name"]');
    const location = getValue('input[name="location"]');
    const cellphoneNumber = getValue('input[name="cellphone_number"]');
    const networkProvider = getValue('select[name="network_provider"]');
    const employeeEmail = getValue('input[name="employee_email"]');

    // Get display text for enrollment type
    const enrollmentTypeDisplay = getSelectedOptionText('select[name="enrollment_type"]') || enrollmentType;
    
    // Get display text for network provider
    const networkProviderDisplay = getSelectedOptionText('select[name="network_provider"]') || networkProvider;

    // Format full name
    const fullName = `${firstName} ${lastName}`.trim();

    // Get current date
    const currentDate = new Date().toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: '2-digit'
    });

    // Convert enrollment type and network provider to display format
    const formatEnrollmentType = (type) => {
        switch(type) {
            case 'new_employee': return 'New Employee';
            case 'lost_sim': return 'Lost/Defective Sim';
            case 'allowance_based': return 'Transition to Allowance Based';
            case 'other': return 'Other';
            default: return type;
        }
    };

    const formatNetworkProvider = (provider) => {
        switch(provider) {
            case 'smart_tnt': return 'Smart / TnT';
            case 'globe_tm': return 'Globe / TM';
            case 'dito': return 'Dito';
            case 'sun': return 'Sun';
            case 'other': return 'Other';
            default: return provider;
        }
    };

    return {
        enrollmentType: enrollmentType,
        enrollmentTypeDisplay: formatEnrollmentType(enrollmentType),
        other: other,
        comment: comment,
        employeeNumber: employeeNumber,
        positionDesignation: positionDesignation,
        firstName: firstName,
        lastName: lastName,
        fullName: fullName,
        cellphoneNumber: cellphoneNumber,
        networkProvider: networkProvider,
        networkProviderDisplay: formatNetworkProvider(networkProvider),
        employeeEmail: employeeEmail,
        currentDate: currentDate,
        location: location, // You can add this field if needed
    };
}

// Function to be called from your modal print button
function printModalContentSameWindow(modalId) {
    printCellphoneEnrollmentForm(modalId);
}
</script>