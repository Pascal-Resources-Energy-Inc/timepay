<div class="modal fade" id="coe-approved-remarks-{{$coe->id}}" tabindex="-1" role="dialog" aria-labelledby="approvedCOEremarks" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="approvedCOEremarks">Approve this COE Request?</h5>
                    <p style="color: #e6e6e6;">HRD-LPD-FOR-00X-000</p>
                </div>
                <button type="button" class="btn-close btn-danger" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method='POST' action='approve-coe/{{$coe->id}}' onsubmit="btnApprove.disabled = true; return true;" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="status" value="Approved">
                        <div class='col-md-12 form-group'>
                            @if($coe->attachment || $coe->email || $coe->user)
                            <div class="border rounded p-2 mb-3" style="background: #f8f9fa;">
                                <table style="width: 100%; font-size: 12px; border-collapse: collapse;">
                                    <tr>
                                        <td style="padding: 2px 8px 2px 0; color: #6c757d; white-space: nowrap; vertical-align: top;"><strong>Ref No.:</strong></td>
                                        <td class="text-primary font-weight-bold" style="padding: 2px 0; color: #6c757d;">{{ $coe->reference_number }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 2px 8px 2px 0; color: #6c757d; white-space: nowrap; vertical-align: top;"><strong>Requestor:</strong></td>
                                        <td style="padding: 2px 0; color: #6c757d;">{{ $coe->user->name ?? $coe->first_name . ' ' . $coe->last_name }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 2px 8px 2px 0; color: #6c757d; white-space: nowrap; vertical-align: top;"><strong>Send:</strong></td>
                                        <td style="padding: 2px 0; color: #6c757d;">
                                            @if($coe->receive_method === 'Viber')
                                                {{ $coe->viber_number }} 
                                            @elseif($coe->receive_method === 'Hard Copy')
                                                <span style="background: #ffc107; color: #000; padding: 1px 6px; border-radius: 3px; font-size: 11px;">Pick up</span>
                                            @else
                                                {{ $coe->email ?? optional($coe->user)->email }}
                                            @endif
                                        </td>
                                    </tr>
                                    @if($coe->attachment)
                                    <tr>
                                        <td style="padding: 2px 8px 2px 0; color: #6c757d; white-space: nowrap; vertical-align: top;"><strong>Attachment:</strong></td>
                                        <td style="padding: 2px 0; color: #6c757d;">
                                            <a href="{{ asset($coe->attachment) }}" target="_blank" style="text-decoration: underline; color: #007bff;">
                                                {{ basename($coe->attachment) }}
                                            </a>
                                        </td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                            @endif
                            <div style="font-size: 14px; margin-bottom: 3px;">Remarks:</div>
                            <textarea class="form-control" name="approval_remarks" id="" cols="30" rows="2" placeholder="(optional)"></textarea>

                            @if ($coe->receive_method === 'Viber')
                              <p style="font-size 13px; background-color: #fffbeb; color: #92400e; margin-top: 15px; padding: 10px; border-radius: 4px;">
                                  You need to manually send the COE Certificate to the requestor's Viber.
                              </p>
                            @endif

                            @if ($coe->receive_method === 'Hard Copy')
                              <p style="font-size 13px; background-color: #fffbeb; color: #92400e; margin-top: 15px; padding: 10px; border-radius: 4px;">
                                    Don't forget to attach the <b>Proof of Delivery</b> right after approving this request.
                              </p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-dismiss="modal">Close</button>
                    <button type="submit" name="btnApprove" class="btn btn-success">Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>
