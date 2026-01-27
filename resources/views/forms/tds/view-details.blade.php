@foreach($tdsRecords as $record)
<div class="modal fade" id="viewDetails{{ $record->id }}" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      
      <div class="modal-header text-black">
        <h5 class="modal-title">Dealer Details - {{ $record->customer_name }}</h5>
        <button type="button" class="close text-white" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <div class="modal-body">
        
        <div class="row">
          
          <div class="col-md-6">
            <h5 class="text-primary mb-3">General Information</h5>
            <table class="table table-borderless table-sm">
              <tr>
                <th width="45%">Date Registered:</th>
                <td>{{ \Carbon\Carbon::parse($record->date_of_registration)->format('M d, Y') }}</td>
              </tr>
              <tr>
                <th>Employee Name:</th>
                <td>{{ $record->user->name ?? 'N/A' }}</td>
              </tr>
              <tr>
                <th>Area:</th>
                <td>
                  @if($record->region)
                    {{ $record->region->region }} - {{ $record->region->province }}{{ $record->region->district ? ' - ' . $record->region->district : '' }}
                  @else
                    N/A
                  @endif
                </td>
              </tr>
              <tr>
                <th>Status:</th>
                <td>
                  @if($record->status == 'Delivered')
                    <span class="badge badge-success">Delivered</span>
                  @elseif($record->status == 'For Delivery')
                    <span class="badge badge-warning">For Delivery</span>
                  @elseif($record->status == 'Interested')
                    <span class="badge badge-info">Interested</span>
                  @elseif($record->status == 'Decline')
                    <span class="badge badge-danger">Decline</span>
                  @endif
                </td>
              </tr>
              @if($record->timeline)
              <tr>
                <th>Timeline:</th>
                <td>{{ \Carbon\Carbon::parse($record->timeline)->format('M d, Y') }}</td>
              </tr>
              @endif
              @if($record->delivery_date)
              <tr>
                <th>Delivery Date:</th>
                <td>{{ \Carbon\Carbon::parse($record->delivery_date)->format('M d, Y') }}</td>
              </tr>
              @endif
            </table>
          </div>

          <div class="col-md-6">
            <h5 class="text-primary mb-3">Customer Information</h5>
            <table class="table table-borderless table-sm">
              <tr>
                <th width="45%">Customer Name:</th>
                <td>{{ $record->customer_name }}</td>
              </tr>
              <tr>
                <th>Contact Number:</th>
                <td>{{ $record->contact_no }}</td>
              </tr>
              <tr>
                <th>Business Name:</th>
                <td>{{ $record->business_name }}</td>
              </tr>
              <tr>
                <th>Business Type:</th>
                <td>{{ $record->business_type }}</td>
              </tr>
              <tr>
                <th>Business Location:</th>
                <td style="word-wrap: break-word; word-break: break-word; white-space: normal;">{{ $record->location }}</td>
              </tr>
              @if($record->awarded_area)
              <tr>
                <th>Awarded Area:</th>
                <td>{{ $record->awarded_area }}</td>
              </tr>
              @endif
            </table>
          </div>

        </div>

        <hr class="my-4">

        @if($record->business_image)
        <div class="row mb-4">
          <div class="col-md-12">
            <h5 class="text-primary mb-3">Business Image</h5>
            <div class="text-center">
              <img src="{{ url('/tds-images/' . $record->business_image) }}" 
                  alt="Business Image" 
                  class="img-fluid img-thumbnail"
                  style="max-height: 400px; cursor: pointer;"
                  onclick="window.open(this.src, '_blank')">
              <p class="text-muted mt-2"><small>Click image to view full size</small></p>
            </div>
          </div>
        </div>
        <hr class="my-4">
        @endif

        <div class="row">
          
          <div class="col-md-6">
            <h5 class="text-primary mb-3">Package Details</h5>
            <table class="table table-borderless table-sm">
              <tr>
                <th width="45%">Package Type:</th>
                <td>
                  @if($record->package_type == 'EU')
                    <span class="badge badge-secondary">EU - End User</span>
                  @elseif($record->package_type == 'D')
                    <span class="badge badge-info">D - Dealer</span>
                  @elseif($record->package_type == 'MD')
                    <span class="badge badge-warning">MD - Mega Dealer</span>
                  @elseif($record->package_type == 'AD')
                    <span class="badge badge-primary">AD - Area Distributor</span>
                  @endif
                </td>
              </tr>
              <tr>
                <th>Purchase Amount:</th>
                <td><strong class="text-success">₱{{ number_format($record->purchase_amount, 2) }}</strong></td>
              </tr>
              @if($record->program_type)
              <tr>
                <th>Program Type:</th>
                <td><span class="badge badge-light">{{ $record->program_type }}</span></td>
              </tr>
              @endif
              @if($record->program_area)
              <tr>
                <th>Program Area:</th>
                <td>{{ $record->program_area }}</td>
              </tr>
              @endif
              <tr>
                <th>Lead Generator:</th>
                <td>{{ $record->lead_generator }}</td>
              </tr>
              <tr>
                <th>Lead Generator:</th>
                <td>{{ $record->lead_reference ?? 'N/A' }}</td>
              </tr>
              <tr>
                <th>Supplier Name:</th>
                <td>{{ $record->supplier_name }}</td>
              </tr>
            </table>
          </div>

          <div class="col-md-6">
            <h5 class="text-primary mb-3">Additional Information</h5>
            
            @if($record->document_attachment)
              <div class="alert alert-success mb-3">
                <strong><i class="ti-file"></i> Document:</strong><br>
                <a href="{{ asset('storage/tds_documents/' . $record->document_attachment) }}" 
                   target="_blank" 
                   class="btn btn-sm btn-info mt-2">
                  <i class="ti-download"></i> View Document
                </a>
              </div>
            @endif

            @if($record->additional_notes)
              <div class="alert alert-info">
                <strong><i class="ti-info-alt"></i> Notes:</strong><br>
                {{ $record->additional_notes }}
              </div>
            @else
              <p class="text-muted"><em>No additional notes available.</em></p>
            @endif
            
            <div class="mt-3">
              <small class="text-muted">
                <i class="ti-time"></i> Created: {{ \Carbon\Carbon::parse($record->created_at)->format('M d, Y h:i A') }}
              </small>
            </div>
          </div>

        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
@endforeach