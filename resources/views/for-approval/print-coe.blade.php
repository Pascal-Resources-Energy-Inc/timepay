<script>
function printCertificateOfEmployment(modalId) {
    // Get the specific modal element
    const modal = document.getElementById(modalId);
    if (!modal) {
        console.error('Modal not found:', modalId);
        return;
    }

    // Extract data from the modal
    const formData = extractCOEFormData(modal);
    
    const printWindow = window.open('', '_blank', 'width=800,height=600');

    const printContent = `
    <!DOCTYPE html>
    <html>
    <head>
        <title>Certificate of Employment - Pascal Resources Energy Inc.</title>
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
                padding: 30px;
                background: white;
                color: black;
                line-height: 1.4;
                font-size: 12px;
            }

            .company-logo {
                float: left;
                width: 200px;
                margin-right: 20px;
                margin-top: -5px;
            }

            .company-info {
                text-align: right;
                margin-bottom: 60px;
                font-size: 11px;
                line-height: 1.3;
                color: #333;
                overflow: hidden;
                margin-left: 220px;
            }

            .company-name-header {
                font-weight: bold;
                font-size: 12px;
                margin-bottom: 8px;
            }

            .clear {
                clear: both;
            }

            .certificate-title {
                text-align: center;
                font-weight: bold;
                font-size: 25px;
                margin: 80px 0 60px 0;
                text-decoration: underline;
            }

            .certificate-body {
                margin: 60px 0;
                text-align: justify;
                line-height: 1.8;
                font-size: 14px;
            }

            .certificate-paragraph {
                margin-bottom: 30px;
                font-size: 14px;
                text-indent: 0;
                line-height: 1;
            }

            .date-location {
                margin: 60px 0;
                font-size: 14px;
            }

            .signature-section {
                margin: 100px 0 60px 0;
                line-height: 1;
                font-size: 14px;
            }

            .signature-name {
                font-weight: bold;
                margin-bottom: 5px;
            }

            .signature-title {
                margin-bottom: 3px;
                font-weight: bold;
            }

            .signature-contact {
                color: #0066cc;
                text-decoration: underline;
                margin-bottom: 3px;
            }

            .reference-section {
                margin-top: 80px;
                font-size: 12px;
            }

            .bold {
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class="company-logo">
            <div style="color: #666; font-size: 24px; line-height: 1; margin-bottom: 10px;">
                <span style="font-size: 38px; font-weight: bold;">PASCAL</span><br>
                RESOURCES<br>
                ENERGY, INC.
            </div>
        </div>

        <div class="company-info">
            <div><strong>PASCAL RESOURCES ENERGY, INC.</strong></div>
            <div>Principal Address: Barangay San Isidro, Lubao, Pampanga</div>
            <div>Corporate Office: 93 West Capitol Drive, Barangay Kapitolyo, Pasig City</div>
            <div>TIN # 009-628-713-0000</div>
            <div>Telephone number: 8244-1784</div>
        </div>

        <div class="clear"></div>

        <div class="certificate-title">CERTIFICATE OF EMPLOYMENT</div>

        <div class="certificate-body">
            <div class="certificate-paragraph">
                This is to certify that <span class="bold">${formData.salutation} ${formData.fullName}</span> is an employee of 
                Pascal Resources Energy Inc. as <span class="bold">${formData.designation}</span> at <span class="bold">${formData.assignedLocation || 'Assigned'}</span> from 
                <span class="bold">${formData.hiringDateFormatted}</span> ${formData.employmentStatus === 'Separated' ? 'to present' : 'up to present'}.
            </div>

            <div class="certificate-paragraph">
                This certification is being issued for <span class="bold">${formData.purpose}</span> purposes only.
            </div>
        </div>

        <div class="date-location">
            Issued this <span class="bold">1st</span> day of <span class="bold">${formData.currentMonth} ${formData.currentYear}</span> at <span class="bold">Kapitolyo, Pasig City</span>.
        </div>

        <div class="signature-section" >
            <div class="signature-name">Matthew Par</div>
            <div class="signature-title">VP – Marketing and Branding</div>
            <div class="signature-contact">hr@pascalresources.com.ph</div>
            <div>(02) 8244-1784</div>
        </div>

        <div class="reference-section">
            Ref: «Ref_no»
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

function extractCOEFormData(modal) {
    // Helper function to get input/select/textarea value safely
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
    const reasonForRequest = getValue('select[name="reason_for_request"]');
    const employmentStatus = getValue('select[name="employment_status"]');
    const hiringDate = getValue('input[name="hiring_date"]');
    const designation = getValue('input[name="designation"]');
    const firstName = getValue('input[name="first_name"]');
    const lastName = getValue('input[name="last_name"]');
    const purpose = getValue('textarea[name="purpose"]');
    const receiveMethod = getValue('select[name="receive_method"]');
    const email = getValue('input[name="email"]');
    const additionalNotes = getValue('textarea[name="additional_notes"]');

    // Format full name
    const fullName = `${firstName} ${lastName}`.trim();

    // Format hiring date exactly as shown in template
    const formatDate = (dateString) => {
        if (!dateString) return '«Date_Hired»';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    };

    // Get current date components
    const currentDate = new Date();
    const currentDay = currentDate.getDate();
    const currentMonth = currentDate.toLocaleDateString('en-US', { month: 'long' });
    const currentYear = currentDate.getFullYear();

    // Determine salutation (you can customize this logic)
    const salutation = firstName.toLowerCase().includes('mr') ? 'Mr.' : 
                      firstName.toLowerCase().includes('ms') || firstName.toLowerCase().includes('mrs') ? 'Ms.' : 
                      '';

    // Convert employment status to display format
    const formatEmploymentStatus = (status) => {
        switch(status) {
            case 'Active': return 'Still Employed';
            case 'Separated': return 'Former Employee';
            default: return status;
        }
    };

    // Convert reason for request to display format
    const formatReasonForRequest = (reason) => {
        switch(reason) {
            case 'Plain': return 'Plain';
            case 'With Salary': return 'With Salary Details';
            default: return reason;
        }
    };

    return {
        reasonForRequest: reasonForRequest,
        reasonForRequestDisplay: formatReasonForRequest(reasonForRequest),
        employmentStatus: employmentStatus,
        employmentStatusDisplay: formatEmploymentStatus(employmentStatus),
        hiringDate: formatDate(hiringDate),
        hiringDateFormatted: formatDate(hiringDate),
        hiringDateRaw: hiringDate,
        designation: designation || '«Position»',
        firstName: firstName || '«Employee_name»',
        lastName: lastName || '',
        fullName: fullName || '«Employee_name»',
        salutation: salutation || '«Salutation»',
        purpose: purpose || '«Purpose»',
        receiveMethod: receiveMethod,
        email: email,
        additionalNotes: additionalNotes,
        currentDay: currentDay,
        currentMonth: currentMonth,
        currentYear: currentYear,
        assignedLocation: '«Assigned»' // Matching the template placeholder
    };
}

// Function to be called from your modal print button
function printModalContentSameWindow(modalId) {
    printCertificateOfEmployment(modalId);
}
</script>