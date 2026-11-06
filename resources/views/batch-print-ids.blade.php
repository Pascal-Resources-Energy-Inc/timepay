<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batch Print IDs</title>

    <!-- inter font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/vendor/sweetalert2/sweetalert2.min.css') }}">

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<style>
    body {
        font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }

    img {
        max-width: 100%;
        display: block;
    }

    .id-card {
        width: 2.362in;
        height: 3.6in;
    }

    .blue {
        position: relative;
        overflow: hidden;
        -webkit-print-color-adjust: exact;
    }

    .position {
        position: absolute;
        left: 35%;
        bottom: 0;
        transform-origin: left center;
        transform: rotate(-90deg);
        white-space: nowrap;
        color: white !important;
        margin: 0;
    }

    .id-row {
        display: flex;
        gap: 0.15in;
        justify-content: center;
        margin-bottom: 0.08in;
    }

    @media print {
        @page {
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .id-card {
            width: 2.362in;
            height: 3.6in;
        }

        .position {
            color: white !important;
        }

        #action-bar {
            display: none !important;
        }

        .id-row {
            page-break-inside: avoid;
            margin-bottom: 0.08in;
        }

        .id-row.page-break-after {
            page-break-after: always;
        }

        .gray_text {
            color: #6b7280 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .text-gray-500 {
            color: #6b7280 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>


<body>
    <div class="flex flex-col items-center gap-0 w-full m-auto px-4">

        @foreach($iurs as $index => $iur)
        @php
            $isPageEnd = ($index + 1) % 3 === 0 && $index + 1 < $iurs->count();
        @endphp
        <div class="id-row {{ $index === 0 ? 'mt-3' : '' }} {{ $isPageEnd ? 'page-break-after' : '' }}">

            <!-- front id -->
            <div class="id-card flex border border-gray-600 overflow-hidden">
                <div class="w-[0.35in] shrink-0 blue">
                    <p class="position">{{ $iur->user->position ?? 'Position' }}</p>
                    <img src="/images/blue.png" class="w-full h-full object-cover" alt="blue">
                </div>

                <div class="flex flex-col w-full">
                    <div class="flex flex-col flex-1 mx-auto gap-1 w-full px-2">
                        <div class="m-auto mt-6 mx-3">
                            <img src="/images/PASCAL_RESOURCES_ENERGY_INC.png" class="object-cover" alt="pascal_resources_energy_inc">
                        </div>

                        <div class="mt-1 m-auto border border-gray-900 w-[0.9in] h-[0.9in] flex items-center justify-center overflow-hidden">
                            @if($iur->id_picture)
                                <img src="{{ asset($iur->id_picture) }}" class="w-full h-full object-cover" alt="photo">
                            @else
                                <span class="text-[8px] text-gray-400">No Photo</span>
                            @endif
                        </div>

                        <div class="mt-[-2px]">
                            <p class="text-[#5BC2E7] text-sm text-center">{{ $iur->user->nick_name ?? 'N/A' }}</p>
                        </div>

                        <div class="mt-[-4px]">
                            <p class="text-[#DA291C] text-center text-[12px] font-semibold leading-tight">{{ $iur->user->first_name }} {{ $iur->user->last_name }}</p>
                        </div>

                        <div class="mt-[-2px]">
                            <p class="text-gray-800 font-semibold text-center text-[12px]">PREI - {{ $iur->user->employee_code ?? '' }}</p>
                        </div>

                        <div class="flex flex-col gap-2 mt-auto mx-auto mb-1.5">
                            <div class="mt-2">
                                <p class="text-gray-900 font-bold text-[8.5px] leading-tight">Principal Office :</p>
                                <p class="text-gray-900 font-bold text-[8.5px] leading-tight">Barangay San Isidro, Lubao, Pampanga</p>
                                <p class="text-gray-900 font-bold text-[8.5px] leading-tight">Corporate Office:</p>
                                <p class="text-gray-900 font-bold text-[8.5px] leading-tight">93 West Capitol Drive Barangay</p>
                                <p class="text-gray-900 font-bold text-[8.5px] leading-tight">Kapitolyo, Pasig City</p>
                            </div>
                            <div class="mt-auto">
                                <p class="text-gray-900 font-bold text-center text-[9px]">(632) 8244-1784</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- back id -->
            <div class="id-card flex flex-col border border-gray-700">
                <div class="w-full shrink-0 mt-2">
                    <img src="/images/red.png" class="w-full h-full object-cover" alt="blue">
                </div>

                <div class="flex flex-col flex-1 gap-4 px-2 w-full mx-auto">
                    <div class="flex flex-col gap-2">
                        <div class="mt-2 mx-auto">
                            <p class="text-gray-900 text-[11px] leading-tight pr-5">SSS No: {{ $iur->user->sss_number ?? '-' }}</p>
                            <p class="text-gray-900 text-[11px] leading-tight pr-5 mb-2">TIN: {{ $iur->user->tax_number ?? '-' }}</p>
                            <p class="text-gray-900 text-[11px] leading-tight pr-5">Birthday: {{ $iur->user->birth_date ? date('F d, Y', strtotime($iur->user->birth_date)) : '-' }}</p>
                            <p class="text-gray-900 text-[11px] leading-tight pr-5">Date Hired: {{ $iur->user->original_date_hired ? date('F d, Y', strtotime($iur->user->original_date_hired)) : '-' }}</p>
                        </div>
                    </div>

                    <p class="gray_text text-gray-500 text-[10px] text-center font-bold">In case of emergency please notify:</p>

                    <div>
                        <p class="text-center font-semibold leading-tight text-[10px]">{{ $iur->user->contact_person->name ?? '' }}</p>
                        <p class="text-center font-semibold leading-tight text-[10px]">{{ $iur->user->permanent_address ?? '' }}</p>
                        <p class="text-center font-semibold leading-tight text-[10px]">{{ $iur->user->contact_person->contact_number ?? '' }}</p>
                    </div>

                    <div>
                        <p class="gray_text text-gray-500 text-center text-[8px] font-semibold leading-tight">This card is temporary and non-transferable. It must be surrendered upon termination of employment.</p>
                    </div>

                    <div class="mt-auto mx-auto w-[100px] mb-5">
                        <img src="{{ route('confid') }}" alt="Authorized signature">
                    </div>
                </div>
            </div>

        </div>
        @endforeach

    </div>

    <div id="action-bar" class="w-full flex flex-col items-center mt-5 gap-3 mb-5">
        <div class="flex justify-center gap-3">
            <button
                type="button"
                onclick="openPrintDialog()"
                class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition shadow cursor-pointer"
            >
                Print
            </button>

            <button
                type="button"
                onclick="history.back()"
                class="bg-gray-500 hover:bg-gray-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition shadow cursor-pointer"
            >
                Back
            </button>
        </div>

        <form
            id="confirm-printed-form"
            method="POST"
            action="{{ route('iur.ids.confirm-printed') }}"
            class="hidden"
        >
            @csrf

            @foreach ($iurs as $iur)
                <input type="hidden" name="refs[]" value="{{ $iur->iur_reference }}">
            @endforeach
        </form>
    </div>
<script src="{{ asset('/vendor/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
    function showPrintConfirmation() {
        Swal.fire({
            title: 'Did the IDs print successfully?',
            text: 'Please check the printed ID cards before confirming. Confirming will mark all selected IDs as printed.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, mark as printed',
            cancelButtonText: 'No',
            reverseButtons: true,
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then(function(result) {
            if (result.isConfirmed) {
                document.getElementById('confirm-printed-form').submit();
                return;
            }
        });
    }

    function openPrintDialog() {
        window.print();
    }

    window.addEventListener('afterprint', function () {
        showPrintConfirmation();
    });
</script>
</body>


</html>

