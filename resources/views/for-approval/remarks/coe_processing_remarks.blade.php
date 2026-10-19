<div class="modal fade" id="coe-processing-remarks-{{$coe->id}}" tabindex="-1" role="dialog" aria-labelledby="processingCOEremarks" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="processingCOEremarks">Process Request</h5>
                <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method='POST' action='process-coe/{{$coe->id}}' onsubmit="btnProcess.disabled = true; return true;" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="status" value="Processing">
                        <div class='col-md-12 form-group'>
                            <div class="border rounded p-2 mb-3" style="background: #f8f9fa;">
                                <table style="width: 100%; font-size: 12px; border-collapse: collapse;">
                                    <tr>
                                        <td style="padding: 2px 8px 2px 0; color: #6c757d; white-space: nowrap; vertical-align: top;"><strong>Reference No.</strong></td>
                                        <td class="text-primary" style="padding: 2px 0; font-weight: bold;">{{ $coe->reference_number }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 2px 8px 2px 0; color: #6c757d; white-space: nowrap; vertical-align: top;"><strong>Type:</strong></td>
                                        <td style="padding: 2px 0; color: #3399ff; font-weight: bold;">{{ $coe->reason_for_request }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 2px 8px 2px 0; color: #6c757d; white-space: nowrap; vertical-align: top;"><strong>Name:</strong></td>
                                        <td style="padding: 2px 0; color: #6c757d;">{{ $coe->first_name }} {{ $coe->last_name }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 2px 8px 2px 0; color: #6c757d; white-space: nowrap; vertical-align: top;"><strong>Designation:</strong></td>
                                        <td style="padding: 2px 0; color: #6c757d;">{{ $coe->designation }}</td>
                                    </tr>
                                    @if ($coe->email)
                                    <tr>
                                        <td style="padding: 2px 8px 2px 0; color: #6c757d; white-space: nowrap; vertical-align: top;"><strong>Email:</strong></td>
                                        <td style="padding: 2px 0; color: #6c757d;">{{ $coe->email }}</td>
                                    </tr>
                                    @endif

                                    @if ($coe->viber_number)
                                    <tr>
                                        <td style="padding: 2px 8px 2px 0; color: #6c757d; white-space: nowrap; vertical-align: top;"><strong>Viber:</strong></td>
                                        <td style="padding: 2px 0; color: #6c757d;">{{ $coe->viber_number }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td style="padding: 2px 8px 2px 0; color: #6c757d; white-space: nowrap; vertical-align: top;"><strong>Delivery method:</strong></td>
                                        <td style="padding: 2px 0; color: #6c757d;">{{ $coe->receive_method }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 2px 8px 2px 0; color: #6c757d; white-space: nowrap; vertical-align: top;"><strong>Additional notes:</strong></td>
                                        <td style="padding: 2px 0; color: #6c757d;">{{ $coe->additional_notes ?? '-'}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div style="font-size: 14px; margin-bottom: 3px;">Remarks:</div>
                            <textarea class="form-control" name="approval_remarks" cols="30" rows="3" placeholder="(optional)"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-dismiss="modal">Close</button>
                    <button type="submit" name="btnProcess" class="btn btn-info">Process</button>
                </div>
            </form>
        </div>
    </div>
</div>
