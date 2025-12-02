<script>
function printModalContentSameWindow(modalId) {

    const modal = document.getElementById(modalId);
    const formData = extractFormData(modal);
    
    const printWindow = window.open('', '_blank', 'width=800,height=600');
    
    const printContent = `
    <!DOCTYPE html>
    <html>
    <head>
        <title></title>
        <style>
            @media print {
                body { margin: 0; }
                .no-print { display: none; }
            }

            @page {
            size: A4; /* or 'letter', 'legal', 'A3', etc. */
            margin: 10mm;
            }

            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                margin: 20px;
                margin-top: 43px;
                line-height: 1.2;
            }
            
            .header {
                margin-bottom: 20px;
            }
            
            .company-name {
                font-size: 16px;
                font-weight: bold;
            }
            
            .instruction {
                font-size: 10px;
                margin-bottom: 15px;
            }
            
            .travel-order-header {
                text-align: right;
                margin-top: -40px;
            }
            
            .travel-order-title {
                font-size: 14px;
                font-weight: bold;
                margin-bottom: 10px;
            }
            
            .date-to-box {
                display: flex;
                justify-content: flex-end; /* push to the right */
                border: 1px solid black;
                width: fit-content;
                margin-left: auto; /* align the whole box to the right */
                font-size: 10px;
                margin-top: 5px;
            }

            .date-half {
                padding: 6px 12px;
                text-align: center;
                min-width: 90px;
            }

            .border-left {
                border-left: 1px solid black;
            }

            /* Main tables container */
            .tables-container {
                display: flex;
                gap: 0;
                margin-bottom: 0;
                width: 100%;
            }
            
            .itinerary-container {
                flex: 0 0 60%; /* 60% width for itinerary */
                color: black;
            }
            
            .cash-advance-container {
                flex: 0 0 40%;
                border-left: 1px solid black;
            }

            
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 0;
            }
            
            th, td {
                border: 1px solid black;
                padding: 6px 4px;
                text-align: center;
                vertical-align: middle;
                font-size: 10px;
            }
            
            .section-header {
                background-color: #3490dc;
                color: white;
                font-weight: bold;
                text-align: center;
                padding: 8px 4px;
            }
            
            .itinerary-table th,
            .itinerary-table td {
                font-size: 9px;
            }
            
            .cash-advance-table th,
            .cash-advance-table td {
                font-size: 9px;
                padding: 4px 2px;
            }
            
            /* Remove right border from itinerary table */
            .itinerary-table td:last-child,
            .itinerary-table th:last-child {
                border-right: none;
            }
            
            /* Remove left border from cash advance table */
            .cash-advance-table td:first-child,
            .cash-advance-table th:first-child {
                border-left: none;
            }
            
            .purpose-section {
                width: 678px;
                border: 1px solid black;
                border-top: none;
                min-height: 80px;
                display: flex;
            }
            
            .purpose-left {
                flex: 1;
                padding: 10px;
                border-right: 1px solid black;
                font-size: 9px;
            }
            
            .purpose-right {
                width: 271px;
                padding: 0;
                display: flex;
                flex-direction: column;
            }

            /* Payment type section */
            .payment-type-section {
                padding: 6px 10px;
                font-size: 10px;
                display: flex;
                justify-content: space-between;
                border-bottom: 1px solid black;
            }

            .payment-type-half {
                flex: 1;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .payment-type-half:first-child {
                border-right: 1px solid black;
                margin-right: 0;
                padding-right: 10px;
            }

            .payment-type-half:last-child {
                padding-left: 10px;
            }
            
            /* Payment content area - flexible based on payment type */
            .payment-content-area {
                flex: 1;
                display: flex;
                flex-direction: column;
            }
            
            /* Mode of payment container - when cash advance is selected */
            .mode-of-payment-container {
                display: flex;
                flex-direction: column;
            }
            
            .payment-option {
                border-bottom: 1px solid black;
                padding: 6px 10px;
                font-size: 10px;
            }
            
            .payment-option:last-child {
                border-bottom: 1px solid black;
            }
            
            /* Container for reimbursement - other instructions goes to second row */
            .reimbursement-container {
                display: flex;
                flex-direction: column;
                height: 100%;
            }
            
            .reimbursement-spacer {
                flex: 1;
                min-height: 91px;
            }
            
            /* Other instructions positioning */
            .other-instructions {
                padding: 8px;
                font-size: 10px;
                border-top: 1px solid black;
            }
            
            /* When reimbursement is selected, other instructions appears earlier */
            .other-instructions-reimbursement {
                padding: 8px;
                font-size: 10px;
                border-top: 1px solid black;
                border-bottom: 1px solid black;
            }
            
            .authorization {
                border: 1px solid black;
                border-top: none;
                padding: 8px;
                font-size: 9px;
                text-align: justify;
                line-height: 1.3;
                width: 662px;
            }
            
            .approval-section {
                border: 1px solid black;
                border-top: none;
                display: flex;
                width: 678px;
            }
            
            .approval-box {
                flex: 1;
                border-right: 1px solid black;
                text-align: center;
                padding: 5px;
            }
            
            .approval-box:last-child {
                border-right: none;
            }
            
            .approval-title {
                font-weight: bold;
                padding: 6px 2px;
                margin-bottom: 5px;
                font-size: 9px;
            }

            .signature-image {
            max-width: 200px;
            height: auto;
            display: block;
            margin-top: -25px;
            margin-left: -40px;
            }
            
            .signature-line {
                border-bottom: 1px solid black;
                height: 25px;
                margin: 8px 3px 3px 3px;
                font-size: 10px;
                padding-top: 5px;
            }
            
            .signature-label {
                font-size: 8px;
                text-align: center;
                margin-top: 2px;
            }

            .status-stamp {
                max-width: 120px;
                max-height: 60px;
                position: absolute;
                top: 5px;
                left: 50%;
                transform: translateX(-50%);
                opacity: 0.5;
                z-index: 10;
            }
            
            .treasury-section {
                border-bottom: 1px solid black;
                border-right: 1px solid black;
                border-left: 1px solid black;
                width: 677.5px;
            }
            
            .treasury-header {
                border-bottom: 1px solid black;
                background-color: #3490dc;
                color: black;
                font-weight: bold;
                text-align: center;
                padding: 8px;
                font-size: 11px;
            }
            
            .treasury-content {
                display: flex;
                padding: 0;
            }
            
            .treasury-left {
                flex: 1;
                padding: 8px;
                border-right: 1px solid black;
                font-size: 10px;
                word-wrap: break-word;
                overflow-wrap: break-word;
            }
            
            .treasury-middle {
                width: 150px;
                padding: 8px;
                font-size: 10px;
            }
            
            .treasury-right {
                width: 120px;
                padding: 8px;
                font-size: 10px;
            }
            
            .footer {
                margin-top: 10px;
                font-size: 8px;
            }
            
            .checkbox {
                display: inline-block;
                width: 12px;
                height: 12px;
                border: 1px solid black;
                margin-right: 5px;
                vertical-align: middle;
            }
            
            .checked {
                background-color: black;
            }
            
            .text-left { text-align: left; padding-left: 8px;}
            .text-left.left { font-size: 6.4px; }
            .text-right { text-align: right; }
            
            /* Specific styling for table cells */
            .destination-col { width: 30%; }
            .date-col { width: 17.5%; }
            .time-col { width: 17.5%; }
            
            .particulars-col { width: 45%; }
            .amount-col { width: 18%; }
            .days-col { width: 18%; }
            .total-col { width: 19%; }

            .checkbox {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 1px solid #000;
            text-align: center;
            line-height: 16px;
            font-weight: bold;
            margin-right: 5px;
            }

            /* Hide elements when not needed */
            .hidden {
                display: none !important;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <div class="company-name" style="margin-bottom: 25px;">PASCAL RESOURCES ENERGY, INC.</div>
            <div class="travel-order-header">
                <div class="travel-order-title">TRAVEL ORDER</div>
                <div class="date-to-box">
                  <div class="date-half">Date: ${formData.appliedDate}</div>
                  <div class="date-half border-left">T.O. No. : ${formData.tonumber}</div>
              </div>
            </div>
        </div>

        <!-- Main Tables Container -->
        <div class="tables-container">
            <!-- Itinerary Table -->
            <div class="itinerary-container">
                <table class="itinerary-table">
                    <tr class="section-header border-right border-dark">
                        <td colspan="5" style="color: black;">ITINERARY</td>
                    </tr>
                    <tr>
                        <td rowspan="2" class="destination-col">DESTINATION</td>
                        <td colspan="2">DEPARTURE</td>
                        <td colspan="2">EXP. ARRIVAL</td>
                    </tr>
                    <tr>
                        <td class="date-col">DATE</td>
                        <td class="time-col">TIME</td>
                        <td class="date-col">DATE</td>
                        <td class="time-col">TIME</td>
                    </tr>
                    <tr>
                        <td class="text-left">${formData.destination || 'Destination'}</td>
                        <td>${formData.dateFrom || 'mm/dd/yyyy'}</td>
                        <td>${formData.departureTime || '--:-- --'}</td>
                        <td>${formData.dateTo || 'mm/dd/yyyy'}</td>
                        <td>${formData.arrivalTime || '--:-- --'}</td>
                    </tr>
                    <tr style="height: 24.2px;">
                        <td class="text-left">${formData.destination2 || ''}</td>
                        <td>${formData.dateFrom2 || ''}</td>
                        <td>${formData.departureTime2 || ''}</td>
                        <td>${formData.dateTo2 || ''}</td>
                        <td>${formData.arrivalTime2 || ''}</td>
                    </tr>
                    <tr style="height: 23.2px;">
                        <td class="text-left">${formData.destination3 || ''}</td>
                        <td>${formData.dateFrom3 || ''}</td>
                        <td>${formData.departureTime3 || ''}</td>
                        <td>${formData.dateTo3 || ''}</td>
                        <td>${formData.arrivalTime3 || ''}</td>
                    </tr>
                    <tr style="height: 24.2px;">
                        <td class="text-left">${formData.destination4 || ''}</td>
                        <td>${formData.dateFrom4 || ''}</td>
                        <td>${formData.departureTime4 || ''}</td>
                        <td>${formData.dateTo4 || ''}</td>
                        <td>${formData.arrivalTime4 || ''}</td>
                    </tr>
                    <tr style="height: 24.2px;">
                        <td class="text-left">${formData.destination5 || ''}</td>
                        <td>${formData.dateFrom5 || ''}</td>
                        <td>${formData.departureTime5 || ''}</td>
                        <td>${formData.dateTo5 || ''}</td>
                        <td>${formData.arrivalTime5 || ''}</td>
                    </tr>
                </table>
            </div>

            <!-- Cash Advance Computation Table -->
            <div class="cash-advance-container">
                <table class="cash-advance-table">
                    <tr class="section-header" style="height: 24px;">
                        <td colspan="4" style="color: black;">TRAVEL EXPENSE COMPUTATION</td>
                    </tr>
                    <tr style="height: 47px;">
                        <td class="particulars-col">PARTICULARS<br>(ALLOWANCES)</td>
                        <td class="amount-col">AMOUNT/<br>LIMIT</td>
                        <td class="days-col">NUMBER<br>OF DAYS</td>
                        <td class="total-col">TOTAL</td>
                    </tr>
                    <tr style="height: 24px;">
                        <td class="text-left left">PER DIEM/FIXED</td>
                        <td>${formData.perdiemAmount || '0.00'}</td>
                        <td>${formData.perdiemDays || '0.00'}</td>
                        <td>${formData.perdiemTotal || '0.00'}</td>
                    </tr>
                    <tr style="height: 24px;">
                        <td class="text-left left">HOTEL/LODGING</td>
                        <td>${formData.hotelAmount || '0.00'}</td>
                        <td>${formData.hotelDays || '0.00'}</td>
                        <td>${formData.hotelTotal || '0.00'}</td>
                    </tr>
                    <tr>
                        <td class="text-left left">TRANSPORTATION /<br>GASOLINE</td>
                        <td>${formData.transpoAmount || '0.00'}</td>
                        <td>${formData.transpoDays || '0.00'}</td>
                        <td>${formData.transpoTotal || '0.00'}</td>
                    </tr>
                    <tr>
                        <td class="text-left left" style="height: 15px;">TOTAL FEES</td>
                        <td>${formData.totalFeesAmount || '0.00'}</td>
                        <td>${formData.totalFeesDays || '0.00'}</td>
                        <td>${formData.totalFeesTotal || '0.00'}</td>
                    </tr>
                    <tr style="font-weight: bold; height: 23.2px;">
                        <td class="text-left left"><strong>TOTAL AMOUNT</strong></td>
                        <td><strong></strong></td>
                        <td><strong></strong></td>
                        <td><strong>${formData.grandTotal || '0.00'}</strong></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Purpose and Payment Section -->
        <div class="purpose-section">
            <div class="purpose-left">
                <strong>PURPOSE</strong><br><br>
                ${formData.purpose || ''}
            </div>
            <div class="purpose-right">
                <!-- Payment Type Selection (Cash Advance or Reimbursement) -->
                <div class="payment-type-section">
                    <div class="payment-type-half">
                        <span class="checkbox">${formData.paymentType === 'cash_advance' ? '✔' : ''}</span> CASH ADVANCE
                    </div>
                    <div class="payment-type-half">
                        <span class="checkbox">${formData.paymentType === 'reimbursement' ? '✔' : ''}</span> REIMBURSEMENT
                    </div>
                </div>
                
                <!-- Payment Content Area - Dynamic based on payment type -->
                <div class="payment-content-area">
                    ${formData.paymentType === 'cash_advance' ? `
                        <!-- Cash Advance: Show mode of payment options -->
                        <div class="mode-of-payment-container">
                            <div class="payment-option">
                                <span class="checkbox">${formData.modePayment === 'cash' ? '✔' : ''}</span> CASH
                            </div>
                            <div class="payment-option">
                                <span class="checkbox">${formData.modePayment === 'check' ? '✔' : ''}</span> CHECK
                            </div>
                            <div class="payment-option">
                                <span class="checkbox">${formData.modePayment === 'payroll' ? '✔' : ''}</span> CREDIT TO EMPLOYEE'S PAYROLL ACCOUNT
                            </div>
                        </div>
                        
                        <!-- Other Instructions at bottom for cash advance -->
                        <div class="other-instructions">
                            <strong>OTHER INSTRUCTIONS</strong><br>
                            ${formData.otherInstructions || ''}
                        </div>
                    ` : `
                        <!-- Reimbursement: Other instructions appears as second row -->
                        <div class="reimbursement-container">
                            <div class="other-instructions-reimbursement">
                                <strong>OTHER INSTRUCTIONS</strong><br>
                                ${formData.otherInstructions || ''}
                            </div>
                            <div class="reimbursement-spacer"></div>
                        </div>
                    `}
                </div>
            </div>
        </div>

        <!-- Authorization Text (Always show) -->
        <div class="authorization">
            I hereby authorize PASCAL RESOURCES ENERGY, INC. to deduct from my salary all Unliquidated Cash Advance without further notice if I fail to comply with my liquidation date, set seven (7) working days after my expected arrival date.
        </div>

        <!-- Approval Section -->
        <div class="approval-section">
            <div class="approval-box">
                <div class="approval-title">REQUESTING DIVISION/DEPARTMENT</div>
                <div class="signature-line" style="margin-top: -8px;">${formData.departmentName}</div>
            </div>
            <div class="approval-box">
                <div class="approval-title">Cost Center</div>
                <div class="signature-line">${formData.costCenter}</div>
            </div>
            <div class="approval-box">
                <div class="approval-title">REQUESTED BY</div>
                ${formData.sigImageUrl 
                    ? `<img src="data:image/png;base64,${formData.sigImageUrl}" style="position: absolute;" alt="Signature" class="signature-image" />`
                    : ''}
                <div class="signature-line">${formData.requestorName}</div>
                <div class="signature-label">(Requestor's Signature Over Printed Name)</div>
            </div>
            <div class="approval-box">
                <div class="approval-title">CHECKED BY</div>
                <div style="position: relative;">
                    <span style="position: absolute; top: -5px; right: 7px; font-size: 0.3rem;">
                        System Approved Date: ${formData.displayApprovedDate}
                    </span>
                </div>
                ${
                    formData.showFinalApprover === false || formData.showFinalApprover === '0'
                        ? (formData.status === 'Approved'
                            ? `<img src="signed/APPROVED.png" alt="Approved" style="position: absolute; transform: translateX(-50%); top: 47%; width: 140px; opacity: 0.5; pointer-events: none; z-index: 10;">`
                            : formData.status === 'Declined'
                            ? `<img src="signed/DENIED.png" alt="Declined" style="position: absolute; transform: translateX(-50%); top: 47%; width: 140px; opacity: 0.5; pointer-events: none; z-index: 10;">`
                            : ''
                        )
                        : ''
                }
                <div class="signature-line">${formData.checkedBy}</div>
                <div class="signature-label">Immediate Supervisor</div>
            </div>
            <div class="approval-box">
                <div class="approval-title">APPROVED BY</div>
                <div style="position: relative;">
                    <span style="position: absolute; top: -5px; right: 7px; font-size: 0.3rem;">
                        System Approved Date: ${formData.displayApprovedFinal}
                    </span>
                </div>
                ${
                    formData.showFinalApprover === true || formData.showFinalApprover === '1'
                        ? (formData.status === 'Approved'
                            ? `<img src="signed/APPROVED.png" alt="Approved" style="position: absolute; transform: translateX(-50%); top: 47%; width: 140px; opacity: 0.5; pointer-events: none; z-index: 10;">`
                            : formData.status === 'Declined'
                            ? `<img src="signed/DENIED.png" alt="Declined" style="position: absolute; transform: translateX(-50%); top: 47%; width: 140px; opacity: 0.5; pointer-events: none; z-index: 10;">`
                            : ''
                        )
                        : ''
                }
                <div class="signature-line">${formData.approvedBy}</div>
                <div class="signature-label">Division/Cluster Head</div>
            </div>
        </div>

        <div class="treasury-section">
            <div class="treasury-header">REMARKS AND LIQUIDATION DETAILS</div>
            <div class="treasury-content">
                <div class="treasury-left">
                    <div style="margin-bottom: 6px;">
                        <strong>REMARKS</strong>
                    </div>
                    ${formData.approvalRemarks.length > 0 
                        ? formData.approvalRemarks.map(remark => `
                            <div style="margin-bottom: 6px; padding: 4px 6px; background-color: #f5f5f5; border-left: 2px solid #3490dc;">
                                <div style="font-size: 8px;">
                                    <strong>${remark.approver}</strong>
                                </div>
                                <div style="font-size: 7px; color: #6c757d; margin-top: 2px;">
                                    ${remark.date}
                                </div>
                                ${remark.remark ? `
                                    <div style="font-size: 8px; margin-top: 3px;">
                                        ${remark.remark}
                                    </div>
                                ` : '<div style="font-size: 7px; color: #999; margin-top: 3px;"><em>No remarks</em></div>'}
                            </div>
                        `).join('')
                        : '<div style="padding: 4px;"><small style="color: #6c757d; font-size: 8px;"><em>No remarks yet</em></small></div>'
                    }
                </div>
                <div class="treasury-middle">
                    <div style="margin-bottom: 6px;">
                        <strong>LIQUIDATION DUE ON:</strong>
                    </div>
                    <div style="margin-top: 10px; font-size: 9px;">
                        ${formData.liquidationDate || ''}
                    </div>
                </div>
            </div>
        </div>
        <div style="display: flex; justify-content: space-between;">
            <small>Distribution: 1 Copy attached to RFP upon liquidation.</small>
            <small>Travel Order Form | OOP-HRD-FOR-016-001</small>
        </div>
       
        <div class="no-print" style="text-align: center; margin-top: 20px;">
            <button onclick="window.print()" style="padding: 10px 20px; font-size: 14px;">Print</button>
            <button onclick="window.close()" style="padding: 10px 20px; font-size: 14px; margin-left: 10px;">Close</button>
        </div>
    </body>
    </html>`;

printWindow.document.write(printContent);
    printWindow.document.close();
    printWindow.focus();
    
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
    let printDialogOpen = false;
    
    const closePrintWindow = () => {
        if (isClosingHandled) return;
        isClosingHandled = true;
        
        console.log('Closing print window...');
        
        if (printWindow && !printWindow.closed) {
            try {
                printWindow.close();
            } catch (e) {
                console.warn('Error closing print window:', e);
            }
        }
        
        clearInterval(focusCheckInterval);
        document.removeEventListener('visibilitychange', handleVisibilityChange);
        window.removeEventListener('blur', handleWindowBlur);
        window.removeEventListener('focus', handleWindowFocus);
        document.removeEventListener('click', handleParentClick);
        
        if (window.activePrintWindow === printWindow) {
            window.activePrintWindow = null;
        }
    };

    const detectPrintDialog = () => {
        try {
            if (printWindow && !printWindow.closed) {
                const parentFocused = document.hasFocus();
                const printWindowFocused = printWindow.document.hasFocus();
                
                if (!parentFocused && !printWindowFocused) {
                    printDialogOpen = true;
                } else if (parentFocused || printWindowFocused) {
                    printDialogOpen = false;
                }
            }
        } catch (e) {
            printDialogOpen = true;
        }
        
        return printDialogOpen;
    };

    const handleWindowBlur = () => {
        setTimeout(() => {
            if (!printWindow || printWindow.closed) return;
            
            try {
                const parentHasFocus = document.hasFocus();
                const printWindowHasFocus = printWindow.document.hasFocus();
                
                if (!parentHasFocus && !printWindowHasFocus && !detectPrintDialog()) {
                    closePrintWindow();
                }
            } catch (e) {
                closePrintWindow();
            }
        }, 1000);
    };

    const handleWindowFocus = () => {
        printDialogOpen = false;
        
        if (printWindow && !printWindow.closed) {
        }
    };

    const handleVisibilityChange = () => {
        if (document.hidden && printWindow && !printWindow.closed) {
            setTimeout(() => {
                if (document.hidden && !detectPrintDialog()) {
                    closePrintWindow();
                }
            }, 1000);
        }
    };

    const handleParentClick = (e) => {
        if (printWindow && !printWindow.closed && !detectPrintDialog()) {
            if (e.target !== document && e.target !== document.body) {
                setTimeout(() => {
                    if (printWindow && !printWindow.closed) {
                        closePrintWindow();
                    }
                }, 200);
            }
        }
    };

    const startFocusMonitoring = () => {
        focusCheckInterval = setInterval(() => {
            if (!printWindow || printWindow.closed) {
                clearInterval(focusCheckInterval);
                if (!isClosingHandled) {
                    closePrintWindow();
                }
                return;
            }

            try {
                const parentHasFocus = document.hasFocus();
                const printWindowHasFocus = printWindow.document.hasFocus();
                
                detectPrintDialog();
                
                if (!parentHasFocus && !printWindowHasFocus && !printDialogOpen) {
                    closePrintWindow();
                }
            } catch (e) {
                closePrintWindow();
            }
        }, 1500);
    };

    setTimeout(() => {
        if (isClosingHandled || !printWindow || printWindow.closed) return;
        
        window.addEventListener('blur', handleWindowBlur);
        window.addEventListener('focus', handleWindowFocus);
        document.addEventListener('visibilitychange', handleVisibilityChange);

        setTimeout(() => {
            if (!isClosingHandled && printWindow && !printWindow.closed) {
                startFocusMonitoring();

                setTimeout(() => {
                    if (!isClosingHandled) {
                        document.addEventListener('click', handleParentClick);
                    }
                }, 2000);
            }
        }, 1000);
        
    }, 500);

    const checkWindowClosed = setInterval(() => {
        if (!printWindow || printWindow.closed) {
            clearInterval(checkWindowClosed);
            closePrintWindow();
        }
    }, 2000);

    try {
        printWindow.addEventListener('beforeunload', closePrintWindow);
        printWindow.addEventListener('unload', closePrintWindow);
        
        printWindow.addEventListener('blur', () => {
            setTimeout(() => {
                if (!printWindow.closed && !document.hasFocus() && !printWindow.document.hasFocus()) {
                    if (!detectPrintDialog()) {
                        closePrintWindow();
                    }
                }
            }, 1000);
        });
        
        printWindow.addEventListener('afterprint', () => {
            setTimeout(closePrintWindow, 1000);
        });
        
    } catch (e) {
        console.warn('Print window event listeners not accessible:', e);
    }

    window.activePrintWindow = printWindow;
    
    return closePrintWindow;
}

function extractFormData(modal) {
    const getValue = (selector) => {
        const element = modal.querySelector(selector);
        return element ? element.value : '';
    };
    
    const getCheckedValue = (name) => {
        const element = modal.querySelector(`input[name="${name}"]:checked`);
        return element ? element.value : '';
    };

    const extractApprovalRemarks = () => {
        const remarksContainer = modal.querySelector('.col-md-7.col-sm-12.border-right.border-dark.p-1');
        if (!remarksContainer) return [];
        
        const remarkBlocks = remarksContainer.querySelectorAll('.mb-2.p-2');
        const remarks = [];
        
        remarkBlocks.forEach(block => {
            const approverName = block.querySelector('strong')?.textContent || 'N/A';
            
            const dateElement = block.querySelector('.text-muted');
            const dateText = dateElement?.textContent?.replace(/\s+/g, ' ').trim() || 'N/A';
            
            const allSmalls = Array.from(block.querySelectorAll('small'));
            const remarkText = allSmalls
                .filter(el => !el.classList.contains('text-muted') && !el.querySelector('strong'))
                .map(el => el.textContent.trim())
                .join(' ') || '';
            
            remarks.push({
                approver: approverName,
                date: dateText,
                remark: remarkText
            });
        });
        
        return remarks;
    };

    const showFinalApproverValue = getValue('input[name="show_final_approver"]');
    
    return {
        appliedDate: getValue('input[name="applied_date"]'),
        destination: getValue('input[name="destination"]'),
        destination2: getValue('input[name="destination_2"]'),
        destination3: getValue('input[name="destination_3"]'),
        destination4: getValue('input[name="destination_4"]'),
        destination5: getValue('input[name="destination_5"]'),
        dateFrom: getValue('input[name="date_from"]'),
        dateFrom2: getValue('input[name="date_from_2"]'),
        dateFrom3: getValue('input[name="date_from_3"]'),
        dateFrom4: getValue('input[name="date_from_4"]'),
        dateFrom5: getValue('input[name="date_from_5"]'),
        dateTo: getValue('input[name="date_to"]'),
        dateTo2: getValue('input[name="date_to_2"]'),
        dateTo3: getValue('input[name="date_to_3"]'),
        dateTo4: getValue('input[name="date_to_4"]'),
        dateTo5: getValue('input[name="date_to_5"]'),
        departureTime: getValue('input[name="departure_time"]'),
        departureTime2: getValue('input[name="departure_time_2"]'),
        departureTime3: getValue('input[name="departure_time_3"]'),
        departureTime4: getValue('input[name="departure_time_4"]'),
        departureTime5: getValue('input[name="departure_time_5"]'),
        arrivalTime: getValue('input[name="arrival_time"]'),
        arrivalTime2: getValue('input[name="arrival_time_2"]'),
        arrivalTime3: getValue('input[name="arrival_time_3"]'),
        arrivalTime4: getValue('input[name="arrival_time_4"]'),
        arrivalTime5: getValue('input[name="arrival_time_5"]'),
        perdiemAmount: getValue('input[name="perdiem_amount"]'),
        perdiemDays: getValue('input[name="perdiem_numofday"]'),
        perdiemTotal: getValue('input[name="perdiem_total"]'),
        hotelAmount: getValue('input[name="hotellodging_amount"]'),
        hotelDays: getValue('input[name="hotellodging_numofday"]'),
        hotelTotal: getValue('input[name="hotellodging_total"]'),
        transpoAmount: getValue('input[name="transpo_amount"]'),
        transpoDays: getValue('input[name="transpo_numofday"]'),
        transpoTotal: getValue('input[name="transpo_total"]'),
        totalFeesAmount: getValue('input[name="totalfees_amount"]'),
        totalFeesDays: getValue('input[name="totalfees_numofday"]'),
        totalFeesTotal: getValue('input[name="totalfees_total"]'),
        totalAmount: getValue('input[name="totalamount_amount"]'),
        totalDays: getValue('input[name="totalamount_numofday"]'),
        grandTotal: getValue('input[name="totalamount_total"]'),
        purpose: getValue('textarea[name="purpose"]'),
        paymentType: getCheckedValue('caradio'),
        modePayment: getCheckedValue('mode_payment'),
        otherInstructions: getValue('input[name="other_instruct"]'),
        departmentName: getValue('input[name="department"]'),
        costCenter: getValue('input[name="cost_center"]'),
        requestorName: getValue('input[name="requestor_name"]'),
        checkedBy: getValue('input[name="checked_by"]'),
        approvedBy: getValue('input[name="approved_by"]'),
        liquidationDate: getValue('input[name="liquidation_date"]'),
        verifiedBy: getValue('input[name="verified_by"]'),
        status: getValue('input[name="status"]'),
        tonumber: getValue('input[name="to_number"]'),
        sigImageUrl: getValue('input[name="sig_image"]'),
        displayApprovedDate: getValue('input[name="display_approved_date"]'),
        displayApprovedFinal: getValue('input[name="display_approved_final"]'),
        showFinalApprover: showFinalApproverValue === '1' || showFinalApproverValue === 'true' || showFinalApproverValue === true,
        approvalRemarks: extractApprovalRemarks()
    };
}
</script>