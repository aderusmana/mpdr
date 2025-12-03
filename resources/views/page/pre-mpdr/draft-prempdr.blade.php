<x-app-layout>
    @section('title')
        Form Draft PREMPDR
    @endsection

    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.0/css/responsive.bootstrap.min.css">
        <style>
            @media print {
                
                body {
                    zoom: 0.6; 
                    margin: 0px;
                }
                .no-print, .topbar, .left-sidebar{
                    display: none !important;
                }
                
            }
            #competitorProduct>tbody>tr>th, #competitorProduct>tbody>tr>td, #approver>tbody>tr>th, #approver>tbody>tr>td{
                padding: 8px 10px !important;
            }
            .no-resize{
                resize:none;
            }
        </style>
    @endpush


    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Form Draft PREMPDR</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Form Draft PREMPDR</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="{{ asset('assets') }}/images/breadcrumb/ChatBc.png" alt="modernize-img"
                            class="img-fluid mb-n4">
                    </div>
                </div>
                <div class="col-3">
                    <button onclick="history.back()" class="btn btn-sm btn-primary flex-end">Back</button>
                </div>
            </div>
        </div>
    </div>

    
    <div id="content" class="w-100">
        <form id="form" action="{{ route('prempdr.draft.update') }}" method="POST">
            @csrf
            <main id="prempdr-main" class="d-flex flex-column gap-3 p-2">
                <div class="d-flex justify-content-end">
                    <label for="no_reg" class="form-label">No Reg: <span id="no_reg_text"></span></label>
                    <input type="hidden" id="no_reg" name="no_reg" value="" readonly>
                </div>
                <div class="row">
                    <div class="col-12 col-md-5">
                        <label for="projectName" class="form-label">Project Name:</label>
                        <input type="text" class="form-control" name="projectName" id="projectName" required> 
                    </div>
                    <div class="col-12 col-md-2">
                        <label for="levelPriority" class="form-label">Level Priority:</label>
                        <select class="form-select" name="levelPriority" id="levelPriority" required>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-5">
                        <label for="brandName" class="form-label">Proposed BRAND Name:</label>
                        <input type="text" class="form-control" name="brandName" id="brandName" required> 
                    </div>
                </div>
                <div id="rational">
                    <label class="form-label">Rational For Development: </label>
                    <textarea class="form-control no-resize" name="rationalForDevelopment" id="rationalForDevelopment" rows="2" required></textarea>
                </div>
                <div class="row">
                    <div id="productCategory" class="col">
                        <label class="form-label">Product Category:</label>
                        <div class="">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="productCategory" id="Margarine" value="Margarine" required>
                                <label class="form-check-label" for="Margarine">
                                Margarine
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="productCategory" id="Frying Fats" value="Frying Fats">
                                <label class="form-check-label" for="Frying Fats">
                                Frying Fats
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="productCategory" id="Shortening" value="Shortening">
                                <label class="form-check-label" for="Shortening">
                                Shortening
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="productCategory" id="Pastry" value="Pastry">
                                <label class="form-check-label" for="Pastry">
                                Pastry
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="productCategory" id="Others" value="Others">
                                <label class="form-check-label" for="Others">
                                    Others
                                </label>
                                <input type="text" class="form-control" name="productCategoryText" id="productCategoryText">
                            </div>
                        </div>
                    </div>
                    <div id="channel" class="col">
                        <label class="form-label">Channel:</label>
                        <div class="">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="channel" id="International" value="International" required>
                                <label class="form-check-label" for="International">
                                International
                                </label>
                                <input type="text" class="form-control" name="country" id="country">
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="channel" id="Regional" value="Regional">
                                <label class="form-check-label" for="Regional">
                                Regional
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="channel" id="Industrial" value="Industrial">
                                <label class="form-check-label" for="Industrial">
                                Industrial
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="channel" id="FSBC-Direct" value="FSBC-Direct">
                                <label class="form-check-label" for="FSBC-Direct">
                                FSBC-Direct
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="channel" id="FSBC-Distributor" value="FSBC-Distributor">
                                <label class="form-check-label" for="FSBC-Distributor">
                                FSBC-Distributor
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <h5>GENERAL PRODUCT DESCRIPTION AND FUNCTION</h5>
                <div id="productDescriptionDiv">
                    <label class="form-label">Product Description <span class="fw-normal">(Form/Color/Flavor brief)</span>: </label>
                    <textarea class="form-control no-resize" name="productDescription" id="productDescription" rows="3" required></textarea>
                </div>
                <div id="usage">
                    <label class="form-label">Usage Description <span class="fw-normal">(Application in customer)</span>: </label>
                    <input type="text" class="form-control" name="usageDescription" id="usageDescription" required>
                </div>
                <div class="row">
                    <div class="col" id="storage">
                        <label class="form-label">Storage Temperature: </label>
                        <input type="text" class="form-control" name="storageTemperature" id="storageTemperature" required>
                    </div>
                    <div class="col" id="delivery">
                        <label class="form-label">Delivery Temperature: </label>
                        <input type="text" class="form-control" name="deliveryTemperature" id="deliveryTemperature" required>
                    </div>
                </div>
                <div id="certification">
                    <label class="form-label">Certification Requirement:</label>
                    <div class="">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="certification" id="BPOM" value="BPOM" required>
                            <label class="form-check-label" for="BPOM">
                            BPOM
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="certification" id="HALAL" value="HALAL">
                            <label class="form-check-label" for="HALAL">
                            HALAL
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="certification" id="certificationOthers" value="Others">
                            <label class="form-check-label" for="Others">
                                Others
                            </label>
                            <input type="text" class="form-control" id="certificationText" name="certificationText" placeholder="(Others)">
                        </div>
                    </div>
                </div>
                <div id="competitor">
                    <h5>Competitor's Product to Match or to Beat:</h5>
                    <table id="competitorProduct" class="table table-striped nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>PRODUCT NAME</th>
                                <th>SIZE</th>
                                <th>PACKAGING</th>
                                <th>Price Indication</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><input type="text" class="form-control" name="productName1" id="productName1" placeholder="Product Name"></td>
                                <td><input type="text" class="form-control" name="size1" id="size1"></td>
                                <td><input type="text" class="form-control" name="packaging1" id="packaging1"></td>
                                <td><input type="text" class="form-control" name="priceIndication1" id="priceIndication1"></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td><input type="text" class="form-control" name="productName2" id="productName2" placeholder="Product Name"></td>
                                <td><input type="text" class="form-control" name="size2" id="size2" ></td>
                                <td><input type="text" class="form-control" name="packaging2" id="packaging2"></td>
                                <td><input type="text" class="form-control" name="priceIndication2" id="priceIndication2"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="detailedPackaging">
                    <h5>Detailed Packaging Required:</h5>
                    <ol>
                        <li>
                            <div class="">
                                <label for="weightProduct" class="form-label">Weight of Product (kg/carton):</label>
                                <input type="number" class="form-control" name="weightProduct" id="weightProduct" required> 
                            </div>
                        </li>
                        <li>
                            <label class="form-label">Packaging:</label>
                            <div class="row">
                                <div class="form-check col">
                                    <input class="form-check-input" type="radio" name="packaging" id="ExistingPackaging" value="Existing" required>
                                    <label class="form-check-label" for="existingPackaging">
                                    Existing
                                    </label>
                                    <textarea class="form-control no-resize" name="ExistingPackagingText" id="ExistingPackagingText" placeholder="Details" rows="2"></textarea>
                                </div>
                                <div class="form-check col">
                                    <input class="form-check-input" type="radio" name="packaging" id="NewPackaging" value="New">
                                    <label class="form-check-label" for="newPackaging">
                                    New
                                    </label>
                                    <textarea class="form-control no-resize" name="NewPackagingText" id="NewPackagingText" placeholder="Details" rows="2"></textarea>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div>
                                <label class="form-label">Product Variation List:</label>
                                <div>
                                    <input type="text" class="form-control" name="productVariation" id="productVariation" required>
                                </div>
                            </div>
                        </li>
                    </ol>
                </div>
                <div id="potential">
                    <h5>MARKET UPDATE</h5>
                    <ol>
                        <li>
                            <div class="form-chcek">
                                <label for="potentialVolume" class="form-label">Potential Volume (Mt/Annually):</label>
                                <input type="number" class="form-control" name="potentialVolume" id="potentialVolume" required> 
                            </div>
                        </li>
                        <li>
                            <label class="form-label">Pricing Strategy</label>
                            <div class="row">
                                <div class="form-check col">
                                    <label for="expectedMargin" class="form-label">Expected Margin (%):</label>
                                    <input type="text" class="form-control" name="expectedMargin" id="expectedMargin" required>
                                </div>
                                <div class="form-check col">
                                    <label for="priceEstimate" class="form-label">Price Estimate:</label>
                                    <input type="text" class="form-control" name="priceEstimate" id="priceEstimate" required>
                                </div>
                            </div>
                        </li>
                    </ol>
                </div>
                <div class="">
                    <h5>TARGET LAUNCH (as Initiator request): </h5>
                    <input type="text" class="form-control" name="targetLaunchText" id="targetLaunch" placeholder="End wk of January - ETD Shipment (for first trial 1 FCL-2 FCL)" required>
                </div>
            </main>
    
            <footer class="border-top border-3 border-black mt-2">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Choose: </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Initiator</td>
                            <td>
                                <select id="initiator" name="initiator" class="form-select form-select-sm" required>
                                    <option disabled value="">Select</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Sales Manager</td>
                            <td>
                                <select id="salesManager" name="salesManager" class="form-select form-select-sm" required>
                                    <option disabled value="">Select</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Marketing Manager</td>
                            <td>
                                <select id="marketingManager" name="marketingManager" class="form-select form-select-sm" required>
                                    <option disabled value="">Select</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Sales & Marketing Dept. Head</td>
                            <td>
                                <select id="deptHead" name="deptHead" class="form-select form-select-sm" required>
                                    <option disabled value="">Select</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </footer>

            <div class="w-100 d-flex align-items-center justify-content-center mb-5">
                <input type="hidden" name="form_status" id="form_status" value="">
                <button type="submit" name="form_status" value="Draft" class="btn btn-primary me-3">Save</button>
                <button type="button" name="form_status" value="Submit" class="btn btn-success" onclick="submitForm('Submit')">Submit</button>
            </div>
        </form>
    </div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.0/js/dataTables.responsive.js"></script>
    <script>
        function submitForm(value) {
            // SweetAlert2 confirmation dialog for submit action
            const input = document.getElementById('form_status');
            input.value = value;
            Swal.fire({
                title: "Form " + value,
                text: "Are you sure you want to " + value + "?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#26D639',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#form").submit();
                }
            });
        }

        // Ketika html sudah dimuat
        document.addEventListener('DOMContentLoaded', function() {

            const no_reg = @json($no_reg);
            var form;
            // fetch data form
            $.ajax({
                url: '{{ route('prempdr.form.data') }}', // URL ke controller
                method: 'GET',
                data: {
                    no_reg: no_reg
                },
                success: function(response) {
                    form = response;
                    // console.log("berhasil mengambil data", response);
                    // No_Reg
                    $('#no_reg_text').text(no_reg);
                    $('#no_reg').val(no_reg);
                    
                    // Revision
                    $('#revision-no').text(response.revision.no);
                    $('#revision-count').text(response.revision.revision);
                    $('#revision-date').text(response.revision.date);
                    
                    $('#projectName').val(response.project_name);
                    $('#levelPriority').val(response.level_priority);
                    $('#brandName').val(response.brand_name);

                    $('#rationalForDevelopment').text(response.detail.rational_for_development);
                    $('#targetLaunch').val(response.detail.target_launch);

                    $(`[id="${response.category.category}"]`).attr('checked', true).attr('disabled', false);
                    $('#productCategoryText').val(response.category.other);

                    $(`#${response.channel.category}`).prop('checked', true).prop('disabled', false);
                    $('#country').val(response.channel.country);

                    $('#productDescription').text(response.description.product_description);
                    $('#usageDescription').val(response.description.usage_description);
                    $('#storageTemperature').val(response.description.storage_temperature);
                    $('#deliveryTemperature').val(response.description.delivery_temperature);
                    
                    var category = response.certification.category === 'Others' ? 'certificationOthers' : response.certification.category;
                    $(`#${category}`).prop('checked', true).prop('disabled', false);
                    $('#certificationText').val(response.certification.other);

                    // Competitor's Product
                    response.competitor.forEach(function(value, index) {
                        $(`#productName${index+1}`).val(response.competitor[index].name);
                        $(`#size${index+1}`).val(response.competitor[index].size);
                        $(`#packaging${index+1}`).val(response.competitor[index].packaging);
                        $(`#priceIndication${index+1}`).val(response.competitor[index].price);
                    });

                    // Detailed Packaging
                    $('#weightProduct').val(response.packaging.weight);
                    $(`#${response.packaging.category}Packaging`).prop('checked', true).prop('disabled', false);
                    $(`#${response.packaging.category}PackagingText`).val(response.packaging.detail);
                    $('#productVariation').val(response.packaging.product_variation);

                    
                    $('#potentialVolume').val(response.market.potential_volume);
                    $('#expectedMargin').val(response.market.expected_margin);
                    $('#priceEstimate').val(response.market.price_estimate);
                    
                },
                error: function() {
                    // Jika gagal, tampilkan pesan error
                    console.log('Error ketika mengambil data form');
                    // $('#formData').html('<p>There was an error fetching the data.</p>');
                }
            });


            // mengambil list approver
            $.ajax({
                url: '{{ route('prempdr.approver.list.data') }}',
                method: 'GET',
                success: function(response) {
                    response.forEach(item => {
                        $('#initiator').append($('<option>', {
                            value: item.nik, 
                            text: item.name,
                            selected: item.nik ==  form.approver.initiator ? true : false
                        }));
                        $('#salesManager').append($('<option>', {
                            value: item.nik, 
                            text: item.name, 
                            selected: item.nik ==  form.approver.sales_manager ? true : false
                        }));
                        $('#marketingManager').append($('<option>', {
                            value: item.nik,
                            text: item.name,
                            selected: item.nik ==  form.approver.marketing_manager ? true : false
                        }));
                        $('#deptHead').append($('<option>', {
                            value: item.nik, 
                            text: item.name,
                            selected: item.nik ==  form.approver.department_head ? true : false
                        }));
                    });
                },
                error: function() {
                    // Jika gagal, tampilkan pesan error
                    console.log('Error ketika mengambil approver list');
                    // $('#formData').html('<p>There was an error fetching the data.</p>');
                }
            });
        });
    </script>
@endpush

</x-app-layout>