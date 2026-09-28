<div class="modal fade" id="employeeWizard" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('employee.setup') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Conformity to Policies Informed Consent Form</h5>
                </div>
                <div class="modal-body">
                    <!-- PROGRESS -->
                    <div class="progress mb-3">
                        <div class="progress-bar" id="wizardProgress" style="width: 16.67%"></div>
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
                        <p class="ml-3">1. All Employees are strictly prohibited to use, sell, or possess alcohol or illegal and/or regulated drugs in the Company premises or while in the performance of their respective duties. The prohibition is likewise applicable during Company-related and/or sponsored activity such as, but not limited to, sports and recreational events, excursions and parties.</p>
                        <p class="ml-3">2. Any employees found to be under the influence of illegal drugs or alcohol shall be ordered to leave the Company premises immediately, or desist from continuing in the performance of his functions, in case he is outside the Company premises.</p>
                        <p class="ml-3">3. Where appropriate, testing will be conducted to determine the presence of illegal drugs and alcohol use.</p>
                        <p class="ml-3">4. The Company reserves the right to conduct inspections, searches, and seizures of an employee or his personal belongings when on the job or in other Company premises when appropriate under the circumstances. This shall be done as a means of enforcing the provision.</p>
                        <p class="ml-3">5. In the event that any visitor or employee of other companies doing business with the Company is found to be in violation of this policy, he will be refused entry or immediately removed from the Company premises.</p>
                        <p class="text-center"><b>ACKNOWLEDGEMENT - DABP</b></p>
                        <p>I hereby acknowledge having received, read and understood the Company's "DRUG AND ALCOHOL ABUSE POLICY." I am aware that any violation on my part of any of the provision as stated in the DRUG AND ALCOHOL ABUSE POLICY may subject me to disciplinary action, which can include suspension or termination of employment, as prescribed in our Employee Handbook.</p>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="dabp" value="Yes, I understand and agree on this." id="dapbRadios1" required {{ old('dabp', auth()->user()->dabp) === 'Yes, I understand and agree on this.' ? 'checked' : '' }}>
                                <label class="form-check-label" for="dapbRadios1">
                                    Yes, I understand and agree on this.
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="dabp" value="No, I understand it but doesn't agree on this." id="dapbRadios2" required {{ old('dabp', auth()->user()->dabp) === "No, I understand it but doesn't agree on this." ? 'checked' : '' }}>
                                <label class="form-check-label" for="dapbRadios2">
                                    No, I understand it but doesn't agree on this.
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2 -->
                    <div class="wizard-step d-none" id="step-2">
                        <h5><b>PREVENTION OF SEXUAL EXPLOITATION, ABUSE, AND HARASSMENT POLICY </b></h5>
                        <hr>
                        <p>The Company has explained this in detail during the New Employee Orientation or Policy Cascade (whichever is applicable), which I am in attendance.</p>
                        <p class="text-center"><b>ACKNOWLEDGEMENT - PSEAH</b></p>
                        <p>I acknowledge that I have read, understood, and agree to comply with the organization’s Prevention of Sexual Exploitation, Abuse, and Harassment (PSEAH) Policy, including the standards of conduct, reporting requirements, and responsibilities set out in the policy.</p>
                        <p>I understand where and how to access the PSEAH Policy and related procedures, guidelines, reporting mechanisms, and supporting documents for future reference. I also understand whom to contact within the organization should I have questions, require clarification, need guidance, or wish to raise a concern relating to PSEAH.</p>
                        <p>I further acknowledge the following key principles regarding PSEAH:</p>
                        <ol class="ml-3">
                            <li><b>Zero tolerance for sexual exploitation, abuse, and harassment.</b> Sexual exploitation, sexual abuse, and sexual harassment are prohibited and may result in disciplinary or other appropriate action in accordance with organizational policy and applicable requirements.</li>
                            <li><b>Duty to report concerns.</b> I understand that concerns, suspicions, allegations, or incidents relating to sexual exploitation, abuse, or harassment should be reported promptly through the organization’s designated and confidential reporting channels, in accordance with the PSEAH Policy.</li>
                            <li><b>Respect, dignity, and appropriate conduct.</b> I am expected to maintain professional boundaries and treat all individuals with dignity and respect, particularly children, vulnerable persons, beneficiaries, community members, colleagues, and other persons with whom I interact in connection with my work.</li>
                            <li><b>Protection against retaliation and confidentiality.</b> I understand that reports and concerns should be handled with appropriate confidentiality and sensitivity, and that retaliation against a person who raises a concern in good faith or participates in a PSEAH process is not acceptable.</li>
                            <li><b>Personal Responsibility.</b> I understand that compliance with the PSEAH Policy is my personal responsibility and that I should seek guidance from the designated PSEAH focal person, safeguarding officer, Human Resources representative, or other authorized contact whenever I am uncertain about appropriate conduct, reporting obligations, or applicable procedures.</li>
                        </ol>
                        <p>By signing this acknowledgement, I confirm that I understand my responsibilities under the PSEAH Policy and commit to uphold its principles and requirements.</p>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pseah" value="Yes, I understand and agree on this." id="pseahRadios1" required {{ old('pseah', auth()->user()->pseah) === 'Yes, I understand and agree on this.' ? 'checked' : '' }}>
                                <label class="form-check-label" for="pseahRadios1">
                                    Yes, I understand and agree on this.
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pseah" value="No, I understand it but doesn't agree on this." id="pseahRadios2" required {{ old('pseah', auth()->user()->pseah) === "No, I understand it but doesn't agree on this." ? 'checked' : '' }}>
                                <label class="form-check-label" for="pseahRadios2">
                                    No, I understand it but doesn't agree on this.
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3 -->
                    <div class="wizard-step d-none" id="step-3">
                        <h5><b>CHILD PROTECTION POLICY</b></h5>
                        <hr>
                        <p>The Company has explained this in detail during the New Employee Orientation or Policy Cascade (whichever is applicable), which I am in attendance.</p>
                        <p class="text-center"><b>ACKNOWLEDGEMENT - CPC</b></p>
                        <p>I acknowledge that I have read and understood the organization’s Child Protection Policy and recognize my responsibility to comply with its provisions, standards, and procedures. I understand where and how to access the relevant policy documents and supporting materials for future reference. I also know whom to contact within the organization should I have any questions, require clarification, or need further guidance regarding the Child Protection Policy.</p>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="cpc" value="Yes, I understand and agree on this." id="cpcRadios1" required {{ old('cpc', auth()->user()->cpc) === 'Yes, I understand and agree on this.' ? 'checked' : '' }}>
                                <label class="form-check-label" for="cpcRadios1">
                                    Yes, I understand and agree on this.
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="cpc" value="No, I understand it but doesn't agree on this." id="cpcRadios2" required {{ old('cpc', auth()->user()->cpc) === "No, I understand it but doesn't agree on this." ? 'checked' : '' }}>
                                <label class="form-check-label" for="cpcRadios2">
                                    No, I understand it but doesn't agree on this.
                                </label>
                            </div>
                        </div>                        
                    </div>

                    <!-- STEP 4 -->
                    <div class="wizard-step d-none" id="step-4">
                        <h5><b>ATTENDANCE & TIMEKEEPING POLICIES & PROCEDURES</b></h5>
                        <hr>
                        <p>The Company has explained this in detail during the New Employee Orientation which I am in attendance.</p>
                        <p>I was given opportunity to ask question to clarify my quries and I know whom to contact for any further clarification I might have in the future.</p>
                        <p class="text-center"><b>ACKNOWLEDGEMENT - ATKP</b></p>
                        <p>I hereby acknowledge having received, read and understood the Company's "ATTENDANCE AND TIMEKEEPING POLICY & PROCEUDRES." I am aware that any violation on my part of any of the provision as stated in the said policy may subject me to disciplinary action, which can include suspension or termination of employment, as prescribed in our Employee Handbook.</p>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="atkp" value="Yes, I understand and agree on this." id="atkpRadios1" required {{ old('atkp', auth()->user()->atkp) === 'Yes, I understand and agree on this.' ? 'checked' : '' }}>
                                <label class="form-check-label" for="atkpRadios1">
                                    Yes, I understand and agree on this.
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="atkp" value="No, I understand it but doesn't agree on this." id="atkpRadios2" required {{ old('atkp', auth()->user()->atkp) === "No, I understand it but doesn't agree on this." ? 'checked' : '' }}>
                                <label class="form-check-label" for="atkpRadios2">
                                    No, I understand it but doesn't agree on this.
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 5 -->
                    <div class="wizard-step d-none" id="step-5">
                        <h5><b>CODE OF CONDUCT</b></h5>
                        <hr>
                        <p>The Company has explained this in detail during the New Employee Orientation which I am in attendance.</p>
                        <p>I was given opportunity to ask question to clarify my quries and I know whom to contact for any further clarification I might have in the future.</p>
                        <p class="text-center"><b>ACKNOWLEDGEMENT - COC</b></p>
                        <p>I hereby acknowledge having received, read and understood the Company's "CODE OF CONDUCT". I am aware that have the right to access our Company's Employee Handbook.</p>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="coc" value="Yes, I understand and agree on this." id="cocRadios1" required {{ old('coc', auth()->user()->coc) === 'Yes, I understand and agree on this.' ? 'checked' : '' }}>
                                <label class="form-check-label" for="cocRadios1">
                                    Yes, I understand and agree on this.
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="coc" value="No, I understand it but doesn't agree on this." id="cocRadios2" required {{ old('coc', auth()->user()->coc) === "No, I understand it but doesn't agree on this." ? 'checked' : '' }}>
                                <label class="form-check-label" for="cocRadios2">
                                    No, I understand it but doesn't agree on this.
                                </label>
                            </div>
                        </div>                        
                    </div>

                    <!-- signature step ID -->
                    <div class="wizard-step d-none" id="step-6" align="center">
                        @php
                            $signatureValue = old('consent_signature', auth()->user()->consent_signature);

                            if (!empty($signatureValue)) {
                                try {
                                    $signatureValue = Crypt::decryptString($signatureValue);
                                } catch (\Throwable $e) {
                                    try {
                                        $signatureValue = Crypt::decrypt($signatureValue);
                                    } catch (\Throwable $e) {
                                        // The signature is already stored as a data URL or base64 value.
                                    }
                                }
                            }

                            $signatureSrc = null;
                            if (!empty($signatureValue)) {
                                $signatureValue = trim($signatureValue);
                                if (strpos($signatureValue, 'data:image') === 0) {
                                    $signatureSrc = $signatureValue;
                                } elseif (base64_decode($signatureValue, true) !== false) {
                                    $signatureSrc = 'data:image/png;base64,' . $signatureValue;
                                }
                            }
                        @endphp

                        <h6><b>Digital Signature</b></h6>
                        <p>{{ $signatureSrc ? 'Your saved digital signature is shown below. Sign on the canvas only if you want to replace it.' : 'Please sign below to acknowledge your agreement.' }}</p>
                        @if($signatureSrc)
                            <div id="signaturePreviewWrapper" class="mb-3">
                                <img id="signaturePreview" src="{{ $signatureSrc }}" alt="Saved digital signature" style="max-height: 120px; max-width: 100%;">
                            </div>
                        @endif
                        <canvas id="signatureCanvas" width="400" height="200" style="border: 1px solid #ccc;"></canvas>
                        <br>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="clearSignature">Clear Signature</button>
                        <input type="hidden" name="consent_signature" id="signatureInput" value="{{ $signatureSrc }}">
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
    $(function () {
        let step = 1;
        const total = 6;
        let isSubmitting = false;

        const form = $('#employeeWizard form');
        const canvas = document.getElementById('signatureCanvas');
        const signaturePad = new SignaturePad(canvas);

        function validateStep(stepNumber) {
            const currentStep = $('#step-' + stepNumber);
            const radioNames = new Set();

            currentStep.find('input[type="radio"][required]').each(function () {
                radioNames.add(this.name);
            });

            for (const name of radioNames) {
                const selected = currentStep.find(
                    'input[type="radio"][name="' + name + '"]:checked'
                );

                // Confirms a radio is selected and has a value.
                if (!selected.length || !selected.val()) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Incomplete Step',
                        text: 'Please select an option before proceeding.'
                    });
                    return false;
                }
            }

            return true;
        }

        function showStep(stepNumber) {
            $('.wizard-step').addClass('d-none');
            $('#step-' + stepNumber).removeClass('d-none');

            $('#prevBtn').toggle(stepNumber > 1);
            $('#nextBtn').toggle(stepNumber < total);
            $('#submitBtn').toggleClass('d-none', stepNumber !== total);

            $('#wizardProgress').css(
                'width',
                ((stepNumber / total) * 100) + '%'
            );
        }

        $('#nextBtn').on('click', function () {
            if (validateStep(step) && step < total) {
                step++;
                showStep(step);
            }
        });

        $('#prevBtn').on('click', function () {
            if (step > 1) {
                step--;
                showStep(step);
            }
        });

        $('#clearSignature').on('click', function () {
            signaturePad.clear();
            $('#signatureInput').val('');
            $('#signaturePreviewWrapper').remove();
        });

        $(canvas).on('pointerdown mousedown touchstart', function () {
            $('#signaturePreviewWrapper').remove();
        });

        form.on('submit', function (event) {
            if (isSubmitting) {
                return;
            }

            event.preventDefault();

            if (!validateStep(step)) {
                return;
            }

            if (step !== total) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Incomplete Step',
                    text: 'Please complete all policy acknowledgements before submitting.'
                });
                return;
            }

            if (signaturePad.isEmpty() && !$('#signatureInput').val()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Signature Required',
                    text: 'Please provide your signature before submitting.'
                });
                return;
            }

            if (!signaturePad.isEmpty()) {
                $('#signatureInput').val(signaturePad.toDataURL());
            }

            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to submit your responses.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, submit',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    isSubmitting = true;
                    $('#prevBtn, #nextBtn, #submitBtn, #clearSignature').prop('disabled', true);
                    $('#preloaderHera').css('display', 'flex');

                    requestAnimationFrame(function () {
                        form[0].submit();
                    });
                }
            });
        });

        showStep(step);

        $('#employeeWizard').modal({
            backdrop: 'static',
            keyboard: false
        }).modal('show');
    });
</script>
