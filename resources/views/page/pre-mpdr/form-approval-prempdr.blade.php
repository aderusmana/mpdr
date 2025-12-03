<x-app-layout>
    @section('title')
        Form Approval PREMPDR
    @endsection

    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.0/css/responsive.bootstrap.min.css">
        <style>
            @media print {
                body {
                    zoom: 0.6; /* Mengubah skala menjadi 80% */
                    margin: 0px;
                }
                .no-print{
                    display: none;
                }
            }
            .no-resize{
                resize: none;
            }
        </style>
    @endpush


    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Form Approval PREMPDR</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{ route('dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Form Approval PREMPDR</li>
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

    <div id="prempdr-form" class="border border-4 border-black w-100 mb-4">
        <header id="prempdr-header" class="row">
            <div class="col-3">
                <img src="{{ asset('assets') }}/images/logos/logoputih.png" class="dark-logo img-fluid p-0" alt="Logo-Dark">
                <img src="{{ asset('assets') }}/images/logos/logohitam.png" class="light-logo img-fluid p-0" alt="Logo-light">
            </div>
            <div class="col-5 fw-bold border-start border-end border-3 border-black text-center d-flex flex-column justify-content-center align-items-center">
                <h4>Pre-MARKETING</h4>
                <h4>PRODUCT DEVELOPMENT REQUEST</h4>
            </div>
            <div class="col-3 d-flex flex-column justify-content-around">
                    <p class="my-auto">No : <span id="revision-no"></span></p>
                    <p class="my-auto">Revision : <span id="revision-count"></span></p>
                    <p class="my-auto">Date : <span id="revision-date"></span></p>
            </div>
        </header>
        <main id="prempdr-main" class="d-flex flex-column gap-3 border-top border-4 border-black p-2">
            <div class="row">  
                <div class="col-6">
                    <label for="projectName" class="form-label">Project Name:</label>
                    <input type="text" class="form-control" name="projectName" id="projectName"> 
                </div>
                <div class="col-2">
                    <label for="levelPriority" class="form-label">Level Priority:</label>
                    <input type="text" class="form-control" name="levelPriority" id="levelPriority"> 
                </div>
                <div class="col-4 d-flex justify-content-end">
                    <label for="projectName" class="form-label">No Reg: <span id="no_reg_text">25PREMPDR0000</span></label>
                    <input type="hidden" id="no_reg" name="no_reg" value="" readonly>
                </div>
            </div>  
            <div class="">
                <label for="brandName" class="form-label">Proposed BRAND Name:</label>
                <input type="text" class="form-control" name="brandName" id="brandName"> 
            </div>
            <div id="rational">
                <label class="form-label">Rational For Development: </label>
                <textarea class="form-control no-resize" name="rationalForDevelopment" id="rationalForDevelopment" rows="2"></textarea>
            </div>
            <div class="row">
                <div id="productCategory" class="col">
                    <label class="form-label">Product Category:</label>
                    <div class="">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="productCategory" id="Margarine">
                            <label class="form-check-label" for="Margarine">
                            Margarine
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="productCategory" id="Frying Fats">
                            <label class="form-check-label" for="Frying Fats">
                            Frying Fats
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="productCategory" id="Shortening">
                            <label class="form-check-label" for="Shortening">
                            Shortening
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="productCategory" id="Pastry">
                            <label class="form-check-label" for="Pastry">
                            Pastry
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="productCategory" id="Others">
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
                            <input class="form-check-input" type="radio" name="channel" id="International">
                            <label class="form-check-label" for="International">
                            International
                            </label>
                            <input type="text" class="form-control" name="country" id="country">
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="channel" id="Regional">
                            <label class="form-check-label" for="Regional">
                            Regional
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="channel" id="Industrial">
                            <label class="form-check-label" for="Industrial">
                            Industrial
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="channel" id="FSBC-Direct">
                            <label class="form-check-label" for="FSBC-Direct">
                            FSBC-Direct
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="channel" id="FSBC-Distributor">
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
                <textarea class="form-control no-resize" id="productDescription" rows="3"></textarea>
            </div>
            <div id="usage">
                <label class="form-label">Usage Description <span class="fw-normal">(Application in customer)</span>: </label>
                <input type="text" class="form-control" id="usageDescription">
            </div>
            <div class="row">
                <div class="col" id="storage">
                    <label class="form-label">Storage Temperature: </label>
                    <input type="text" class="form-control" name="storageTemperature" id="storageTemperature">
                </div>
                <div class="col" id="delivery">
                    <label class="form-label">Delivery Temperature: </label>
                    <input type="text" class="form-control" name="deliveryTemperature" id="deliveryTemperature">
                </div>
            </div>
            <div id="certification">
                <label class="form-label">Certification Requirement:</label>
                <div class="">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="certification" id="BPOM">
                        <label class="form-check-label" for="BPOM">
                        BPOM
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="certification" id="HALAL">
                        <label class="form-check-label" for="HALAL">
                        HALAL
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="certification" id="certification3">
                        <label class="form-check-label" for="certification3">
                            Others
                        </label>
                        <input type="text" class="form-control" id="certificationText" name="certificationText">
                    </div>
                </div>
            </div>
            <div id="competitor">
                <h5>Competitor's Product to Match or to Beat:</h5>
                <table id="competitorProduct" class="table table-striped nowrap">
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
                            <td><input type="text" class="form-control" name="productName1" id="productName1"></td>
                            <td><input type="text" class="form-control" name="size1" id="size1"></td>
                            <td><input type="text" class="form-control" name="packaging1" id="packaging1"></td>
                            <td><input type="text" class="form-control" name="priceIndication1" id="priceIndication1"></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td><input type="text" class="form-control" name="productName2" id="productName2"></td>
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
                            <input type="text" class="form-control" name="weightProduct" id="weightProduct"> 
                        </div>
                    </li>
                    <li>
                        <label class="form-label">Packaging:</label>
                        <div class="row">
                            <div class="col form-check">
                                <input class="form-check-input" type="radio" name="packaging" id="ExistingPackaging">
                                <label class="form-check-label" for="ExistingPackaging">
                                Existing
                                </label>
                                <textarea class="form-control no-resize" name="ExistingPackagingText" id="ExistingPackagingText" rows="2"></textarea>
                            </div>
                            <div class="col form-check">
                                <input class="form-check-input" type="radio" name="packaging" id="NewPackaging">
                                <label class="form-check-label" for="NewPackaging">
                                New
                                </label>
                                <textarea class="form-control no-resize" name="NewPackagingText" id="NewPackagingText" rows="2"></textarea>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div>
                            <label class="form-label">Product Variation List:</label>
                            <div>
                                <input type="text" class="form-control" name="productVariation" id="productVariation">
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
                            <input type="text" class="form-control" name="potentialVolume" id="potentialVolume"> 
                        </div>
                    </li>
                    <li>
                        <label class="form-label">Pricing Strategy</label>
                        <div class="row">
                            <div class="col form-check">
                                <label for="expectedMargin" class="form-label">Expected Margin (%):</label>
                                <input type="text" class="form-control" name="expectedMargin" id="expectedMargin">
                            </div>
                            <div class="col form-check">
                                <label for="priceEstimate" class="form-label">Price Estimate:</label>
                                <input type="text" class="form-control" name="priceEstimate" id="priceEstimate">
                            </div>
                        </div>
                    </li>
                </ol>
            </div>
            <div class="">
                <h5>TARGET LAUNCH (as Initiator request): </h5>
                <input type="text" class="form-control" name="targetLaunchText"  id="targetLaunch">
            </div>
        </main>
        <footer id="prempdr-footer" class="p-2 mb-1" style="border-top: 3px solid black ;">
            <table id="approver" class="table table-striped nowrap">
                <thead>
                    <tr>
                        <th>Approved by,</th>
                        <th class="text-center">Approved</th>
                        <th class="text-center">Approved with Review</th>
                        <th class="text-center">Not Approved</th>
                        <th class="text-center">Notes/Comments</th>
                    </tr>
                </thead>
                <tbody class="">
                    <tr id="initiator">
                        <td>Initiator</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                    </tr>
                    <tr id="salesManager">
                        <td>Dept. Head</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                    </tr>
                    <tr id="marketingManager">
                        <td>Ass. Product Manager / Marketing Manager</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                    </tr>
                    <tr id="deptHead">
                        <td>Dept. Head</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                    </tr>
                </tbody>
            </table>
        </footer>
    </div>
    
    <form id="form" action="{{ route('prempdr.form.approve', ['no_reg' => $no_reg]) }}" method="POST" class="w-full">
        @csrf
        <div id="aprove/reject" class="d-flex flex-column align-items-center">
            <input type="hidden" name="formId" value="">
            <textarea name="comment" id="comment" class="m-2 form-control rounded-lg w-100 border border-3" style="max-width:450px; resize: none;" rows="5" placeholder="Tulis komentar disini..."></textarea>
            <div id="textarea-warning" class="d-none fw-bold text-danger">Tolong isi kolom komentar.</div>
            <div class="w-full flex" style="justify-content: space-evenly;">
                <div class="flex sm:!flex-row flex-col mt-2">
                    <input type="hidden" name="action" id="action" value="">
                    <button type="button" name="action" value="approve" class="m-2 px-4 py-2 btn btn-outline-success" onclick="submitForm('approve')">
                        Approve
                    </button>
                    <button type="button" name="action" value="approve with review" class="m-2 px-4 py-2 btn btn-outline-warning" onclick="validateForm('approve with review')">
                        Approve with Review
                    </button>
                    <button type="button" name="action" value="not approve" class="m-2 px-4 py-2 btn btn-outline-danger" onclick="validateForm('not approve')">
                        Not Approve
                    </button>
                </div>
            </div>
        </div>
    </form>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.0/js/dataTables.responsive.js"></script>
    <script>
        var table = $('#competitorProduct').DataTable({
            responsive: true,
            ordering: false,
            paging: false, 
            searching: false,
            info: false 
        });
        var table = $('#approver').DataTable({
            responsive: true,
            ordering: false,
            paging: false, 
            searching: false,
            info: false 
        });

        function submitForm(value) {
            // SweetAlert2 confirmation dialog for submit action
            const input = document.getElementById('action');
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
        
        function validateForm(type) {
            var comment = document.getElementById('comment').value.trim();
            var warning = document.getElementById('textarea-warning');
            if (comment === '') {
                warning.classList.remove('d-none'); 
            }else{
                warning.classList.add('d-none'); 
                submitForm(type);
            }
        }

        
        document.addEventListener('DOMContentLoaded', function() {
            function makeAllReadonly() {
                // Menjadikan semua elemen input dan textarea readonly
                var inputs = document.querySelectorAll('#prempdr-form input, #prempdr-form textarea');  // Pilih semua input
                inputs.forEach(function(input) {
                    if (input.type === 'radio') {
                        if (!input.checked) {
                            input.disabled = true;  // Menonaktifkan radio button jika belum disabled
                        }
                    } else {
                        input.readOnly = true; // Setiap input dan textarea menjadi readonly
                    }
                
                });
            }
            makeAllReadonly();

            const no_reg = @json($no_reg);
            console.log("MELAKUKAN FETCH DATA:", no_reg);

            // ajax data form
            $.ajax({
                url: '{{ route('prempdr.form.data') }}', // URL ke controller
                method: 'GET',
                data: {
                    no_reg: no_reg
                },
                success: function(response) {
                    console.log("berhasil mengambil data", response);
                    // No_Reg
                    $('#no_reg_text').text(no_reg);
                    
                    // Revision
                    $('#revision-no').text(response.revision.no);
                    $('#revision-count').text(response.revision.revision);
                    $('#revision-date').text(response.revision.date);
                    
                    $('#projectName').val(response.project_name);
                    $('#levelPriority').val(response.level_priority);
                    $('#brandName').val(response.brand_name);

                    $('#rationalForDevelopment').html(response.detail.rational_for_development);
                    $('#targetLaunch').val(response.detail.target_launch);

                    $(`#${response.category.category}`).attr('checked', true).attr('disabled', false);
                    $('#productCategoryText').val(response.category.other);

                    $(`#${response.channel.category}`).attr('checked', true).attr('disabled', false);
                    $('#country').val(response.channel.country);

                    $('#productDescription').html(response.description.product_description);
                    $('#usageDescription').val(response.description.usage_description);
                    $('#storageTemperature').val(response.description.storage_temperature);
                    $('#deliveryTemperature').val(response.description.delivery_temperature);   
                    
                    $(`#${response.certification.category}`).attr('checked', true).attr('disabled', false);
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
                    $(`#${response.packaging.category}Packaging`).attr('checked', true).attr('disabled', false);
                    $(`#${response.packaging.category}PackagingText`).val(response.packaging.detail);
                    $('#productVariation').val(response.packaging.product_variation);

                    
                    $('#potentialVolume').val(response.market.potential_volume);
                    $('#expectedMargin').val(response.market.expected_margin);
                    $('#priceEstimate').val(response.market.price_estimate);
                    
                    var approvers = ["#initiator", "#salesManager", "#marketingManager", "#deptHead"];
                    var divId;
                    response.approved_detail.forEach(function(detail, index) {
                        divId = approvers[index];
                        
                        newDiv = '';
                        if (detail.status){
                            newDiv = document.createElement('div');
                            newDiv.classList.add('d-flex', 'flex-column');
                            newDiv.innerHTML = `
                                <div>${detail.status}</div>
                                <div>${detail.approved_date}</div>
                            `;
                        }
                        $(divId).find('td').eq(0).text(detail.name);
                        if(detail.status === 'approve'){
                            $(divId).find('td').eq(1).text(detail.approved_date);  // Kolom kedua
                        }else if(detail.status === 'approve with review'){
                            $(divId).find('td').eq(2).text(detail.approved_date); // Kolom ketiga
                            $(divId).find('td').eq(4).text(detail.comment); // Kolom kelima
                        }else{
                            $(divId).find('td').eq(3).text(detail.approved_date); // Kolom keempat
                            $(divId).find('td').eq(4).text(detail.comment); // Kolom kelima
                        }
                    });
                    
                },
                error: function() {
                    // Jika gagal, tampilkan pesan error
                    console.log('Error ketika mengambil data form');
                    // $('#formData').html('<p>There was an error fetching the data.</p>');
                }
            });
        });
    </script>
@endpush

</x-app-layout>