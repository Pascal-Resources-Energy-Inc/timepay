<div class="modal fade" id="employeeWizard" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('employee.setup') }}" onsubmit='show()' enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Conformity to Policies Informed Consent Form</h5>
                </div>
                <div class="modal-body">
                    <!-- PROGRESS -->
                    <div class="progress mb-3">
                        <div class="progress-bar" id="wizardProgress" style="width: 33%"></div>
                    </div>
                    <!-- STEP 1 -->
                    <div class="wizard-step" id="step-1">
                        <h5><b>DRUG AND ALCOHOL ABUSE POLICY</b></h5>
                        <hr>
                        <p><strong>Pascal Resources Energy, Inc.</strong> is committed to a policy which involves its employees a working environment wherein safety is assured. While the Company has no intention of intruding into the private lives of its employees, it expects its employees to understand that the use of illegal drugs on or off the job has an impact on safety and performance which interferes with the Company's objectives of providing a safe working environment.</p>
                        <p>Pursuant to this objective, the Company has established this DRUG AND ALCOHOL ABUSE POLICY, which requires in essence, all employees to report for work drug and alcohol-free.</p>
                        <p>Employees are required to strictly abide to the guidelines listed below. In the event that any employee is found violating any of these guidelines, appropriate disciplinary action as prescribed in the Company's Employee Handbook, including suspension and termination, will be imposed.</p>
                        <p>Pursuant to this objective, the Company has established this DRUG AND ALCOHOL ABUSE POLICY, which requires in essence, all employees to report for work drug and alcohol-free.</p>
                        <p>Employees are required to strictly abide to the guidelines listed below. In the event that any employee is found violating any of these guidelines, appropriate disciplinary action as prescribed in the Company's Employee Handbook, including suspension and termination, will be imposed.</p>
                        <p>1. All Employees are strictly prohibited to use, sell, or possess alcohol or illegal and/or regulated drugs in the Company premises or while in the performance of their respective duties. The prohibition is likewise applicable during Company-related and/or sponsored activity such as, but not limited to, sports and recreational events, excursions and parties.</p>
                        <p>2. Any employees found to be under the influence of illegal drugs or alcohol shall be ordered to leave the Company premises immediately, or desist from continuing in the performance of his functions, in case he is outside the Company premises.</p>
                        <p>3. Where appropriate, testing will be conducted to determine the presence of illegal drugs and alcohol use.</p>
                        <p>4. The Company reserves the right to conduct inspections, searches, and seizures of an employee or his personal belongings when on the job or in other Company premises when appropriate under the circumstances. This shall be done as a means of enforcing the provision.</p>
                        <p>5. In the event that any visitor or employee of other companies doing business with the Company is found to be in violation of this policy, he will be refused entry or immediately removed from the Company premises.</p>
                        <p class="text-center"><b>ACKNOWLEDGEMENT - DABP</b></p>
                        <p>I hereby acknowledge having received, read and understood the Company's "DRUG AND ALCOHOL ABUSE POLICY." I am aware that any violation on my part of any of the provision as stated in the DRUG AND ALCOHOL ABUSE POLICY may subject me to disciplinary action, which can include suspension or termination of employment, as prescribed in our Employee Handbook.</p>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="dabp" value="Yes, I understand and agree on this." id="dapbRadios1" required>
                                <label class="form-check-label" for="dapbRadios1">
                                    Yes, I understand and agree on this.
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="dabp" value="No, I understand it but doesn't agree on this." id="dapbRadios2" required>
                                <label class="form-check-label" for="dapbRadios2">
                                    No, I understand it but doesn't agree on this.
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2 -->
                    <div class="wizard-step d-none" id="step-2">
                        <h5><b>ATTENDANCE & TIMEKEEPING POLICIES & PROCEDURES</b></h5>
                        <hr>
                        <p>The Company has explained this in detail during the New Employee Orientation which I am in attendance.</p>
                        <p>I was given opportunity to ask question to clarify my quries and I know whom to contact for any further clarification I might have in the future.</p>
                        <p class="text-center"><b>ACKNOWLEDGEMENT - ATKP</b></p>
                        <p>I hereby acknowledge having received, read and understood the Company's "ATTENDANCE AND TIMEKEEPING POLICY & PROCEUDRES." I am aware that any violation on my part of any of the provision as stated in the said policy may subject me to disciplinary action, which can include suspension or termination of employment, as prescribed in our Employee Handbook.</p>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="atkp" value="Yes, I understand and agree on this." id="atkpRadios1" required>
                                <label class="form-check-label" for="atkpRadios1">
                                    Yes, I understand and agree on this.
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="atkp" value="No, I understand it but doesn't agree on this." id="atkpRadios2" required>
                                <label class="form-check-label" for="atkpRadios2">
                                    No, I understand it but doesn't agree on this.
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3 -->
                    <div class="wizard-step" id="step-3">
                        <h5><b>CODE OF CONDUCT</b></h5>
                        <hr>
                        <p>The Company has explained this in detail during the New Employee Orientation which I am in attendance.</p>
                        <p>I was given opportunity to ask question to clarify my quries and I know whom to contact for any further clarification I might have in the future.</p>
                        <p class="text-center"><b>ACKNOWLEDGEMENT - COC</b></p>
                        <p>I hereby acknowledge having received, read and understood the Company's "CODE OF CONDUCT". I am aware that have the right to access our Company's Employee Handbook.</p>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="coc" value="Yes, I understand and agree on this." id="cocRadios1" required>
                                <label class="form-check-label" for="cocRadios1">
                                    Yes, I understand and agree on this.
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="coc" value="No, I understand it but doesn't agree on this." id="cocRadios2" required>
                                <label class="form-check-label" for="cocRadios2">
                                    No, I understand it but doesn't agree on this.
                                </label>
                            </div>
                        </div>                        
                    </div>

                    <div class="wizard-step d-none" id="step-4" align="center">
                        <h6><b>Digital Signature</b></h6>
                        <p>Please sign below to acknowledge your agreement.</p>
                        <canvas id="signatureCanvas" width="400" height="200" style="border: 1px solid #ccc;"></canvas>
                        <br>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="clearSignature">Clear Signature</button>
                        <input type="hidden" name="consent_signature" id="signatureInput">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" id="prevBtn">Previous</button>
                    <button type="button" class="btn btn-outline-primary" id="nextBtn">Next</button>
                    <button type="submit" class="btn btn-outline-success d-none" id="submitBtn">Finish</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .swal2-popup {
        padding: 0px !important;
    }
    .swal2-icon {
        margin: 0px !important;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
<script>
    $(document).ready(function(){

        let step = 1;
        const total = 4;

        function validateStep(n) {
            let inputs = $('#step-' + n + ' input[required]');
            let valid = true;

            let names = [];

            inputs.each(function(){
                let name = $(this).attr('name');
                if (!names.includes(name)) names.push(name);
            });

            names.forEach(function(name){
                if (!$('input[name="'+name+'"]:checked').length) {
                    valid = false;
                }
            });

            if (!valid) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Incomplete Step',
                    text: 'Please select an option before proceeding.',
                });
            }

            return valid;
        }

        function showStep(n) {
            $('.wizard-step').addClass('d-none');
            $('#step-' + n).removeClass('d-none');

            $('#prevBtn').toggle(n > 1);

            // IMPORTANT FIX HERE
            if (n === total) {
                $('#nextBtn').hide();
                $('#submitBtn').removeClass('d-none').show();
            } else {
                $('#nextBtn').show();
                $('#submitBtn').hide();
            }

            let percent = (n / total) * 100;
            $('#wizardProgress').css('width', percent + '%');
        }

        // NEXT BUTTON
        $('#nextBtn').click(function(){
            if (validateStep(step)) {
                step++;
                showStep(step);
            }
        });

        // PREVIOUS BUTTON
        $('#prevBtn').click(function(){
            if (step > 1) {
                step--;
                showStep(step);
            }
        });

        // Initialize Signature Pad
        const canvas = document.getElementById('signatureCanvas');
        const signaturePad = new SignaturePad(canvas);

        // Clear Signature Button
        $('#clearSignature').click(function(){
            signaturePad.clear();
        });

        $('#submitBtn').on('click', function (e) {
            e.preventDefault();

            if (!validateStep(step)) return;

            // ✅ CHECK SIGNATURE
            if (signaturePad.isEmpty()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Signature Required',
                    text: 'Please provide your signature before submitting.',
                });
                return;
            }

            // ✅ SAVE SIGNATURE
            const signatureData = signaturePad.toDataURL();
            $('#signatureInput').val(signatureData);

            let form = $(this).closest('form');

            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to submit your responses.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, submit',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.off('submit').submit(); // ✅ ensure real submit
                }
            });
        });

        // INIT
        showStep(step);

        // FORCE MODAL
        $('#employeeWizard').modal({
            backdrop: 'static',
            keyboard: false
        }).modal('show');

    });
</script>
